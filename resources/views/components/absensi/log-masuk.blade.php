<style>
    h5 {
        font-size: 1rem;
    }
</style>
<div class="card-body">
    <div class="text-center p-3" id="no-data">
        <img src="/img/not_found.png" alt="No Data" class="img-fluid w-50">
        <h4 class="mb-3">Belum ada data.</h4>
        <button class="btn btn-primary d-block mx-auto mb-3" @if ($disable_log) disabled @endif id="log-masuk-btn">Log
            Absen Masuk</button>
    </div>
    <div id="record-masuk">
        <ul class="list-group mb-3">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Waktu <span class="float-right">:</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-right">{{ date('H:i') }} WIB</h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Tanggal <span class="float-right">:</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-right">{{ date('d/m/Y') }}</h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Jam Absen <span class="float-right">:</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-right">{{ $jam_kerja->formatted }}</h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Lokasi <span class="float-right">:</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h5 id="location" class="text-right"><button class="btn btn-primary btn-sm"
                                id="btn-req-location">Dapatkan Lokasi</button></h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Jarak Ke Kantor <span class="float-right">:</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h5 id="distance" class="text-right">Klik tombol 'Dapatkan
                            Lokasi' terlebih
                            dahulu.</h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item" id="capture-container">
                <x-absensi.capture />
            </li>
        </ul>
        <div class="text-center">
            @csrf
            <button class="btn btn-success" id="btn-record">
                <i class="fa fa-upload mr-2" aria-hidden="true"></i>Submit</button>
        </div>
    </div>
</div>

<script>
    class LogMasuk {
        constructor() {
            this.lat = '';        
            this.long = '';
            this.location = '';
            this.distance = '';
            this.apiKey = '31df3da80fa84a71af42f092cb0070ff';
            this.baseLat = `<?= $setting->base_lat ?>`;    
            this.baseLong = `<?= $setting->base_long ?>`;
            this.locationDone = false;
        }
    
        init() {
            $('#record-masuk').hide();
            $('#capture-container').hide();
            this.getSavedData();
        }

        log() {
            $('#record-masuk').show();
            $('#no-data').hide();
        }

        getSavedData() {
            const saved = localStorage.getItem('location');
            if (saved) {
                const { lat, long, location, distance, savedAt } = JSON.parse(saved);
                const diff = moment().diff(moment(savedAt), 'minutes');
                console.log(diff);
                if (diff <= 10) {
                    this.location = location;
                    this.distance = distance;
                    this.lat = lat;
                    this.long = long;
                    this.locationDone = true;
                    this.renderAfterLocation();
                }
            }
        }

        saveLocation() {
            const toSave = {
                lat: this.lat,
                long: this.long,
                location: this.location,
                distance: this.distance,
                savedAt: new Date().toISOString(),
            }
            localStorage.setItem('location', JSON.stringify(toSave));
        }

        getLocation() {
            return new Promise((resolve, reject) => {
                if ('navigator' in window) {
                    navigator.geolocation.getCurrentPosition((pos) => {
                        const lat = pos.coords.latitude;
                        const long = pos.coords.longitude;
                        resolve({ lat, long });
                    }, (err) => {
                        showErrorAlert('Harap izinkan akses lokasi.');
                        console.log(err);
                        reject(err);
                    });
                }
            })
        }

        getActualLocation($lat, $long) {
            const apiURL = `https://api.geoapify.com/v1/geocode/reverse?lat=${$lat}&lon=${$long}&apiKey=${this.apiKey}`;
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await fetch(apiURL);
                    const json = await response.json();
                    const { address_line1, address_line2 } = json.features[0].properties;
                    const location = [address_line1, address_line2].join(', ');
                    resolve(location);
                } catch (err) {
                    reject('Gagal mendapatkan lokasi.');
                }
            })
        }

        getDistance($lat, $long) {
            const apiURL = `https://api.geoapify.com/v1/routing?waypoints=${$lat},${$long}|${this.baseLat},${this.baseLong}&mode=light_truck&apiKey=${this.apiKey}`;
            return new Promise(async(resolve, reject) => {
                try {
                    const response = await fetch(apiURL);
                    const json = await response.json();
                    const distance = json.features[0].properties.distance;
                    resolve(distance);
                } catch (error) {
                    reject(error.message);
                }
            })
        }

        async initLocation() {
            $('#location').text('Loading...');
            $('#distance').text('Loading...');
            try {
                const { lat, long } = await this.getLocation();
                this.lat = lat;
                this.long = long;
                console.log(lat, long);
                const [location, distance] = await Promise.all([this.getActualLocation(lat, long), this.getDistance(lat, long)]);
                this.location = location;
                this.distance = distance;
                this.locationDone = true;
                this.saveLocation();
                this.renderAfterLocation();
            } catch(err) {
                this.renderAfterLocation(false);
                showErrorAlert('Ups, ada kesalahan saat menginisialisasi lokasi.');
            }
        }
        
        renderAfterLocation(success = true) {
            if (success) {
                $('#location').text(this.location);
                $('#distance').text(`${this.distance} Meter.`);
                $('#capture-container').show();
            } else {
                const message = 'Ups, terjadi kesalahan saat mendapatkan lokasi.'
                $('#location').addClass('text-danger').text(message);
                $('#distance').addClass('text-danger').text(message);
            }
        }

        submit() {
            const gambar = capture.getCanvasURL();
            const payload = {
                id_user: `{{ $user->id }}`,
                id_jam: `{{ $jam_kerja->id }}`,
                tanggal: `{{ date('Y-m-d') }}`,
                _token: $('input[name=_token]').val(),
                waktu_masuk: `{{ date('H:i:s') }}`,
                dok_masuk: gambar,
                lat_masuk: this.lat,
                long_masuk: this.long,
                jarak_masuk: this.distance,
                lokasi_masuk: this.location,
            }

            const formURL = `${baseURL}/panel/absensi`;

            $.ajax({
                url: formURL,
                method: 'POST',
                data: payload,
                success: res => {
                    if(res?.success) {
                        showSuccessAlert('Rekam absen berhasil.', () => {
                            window.location.reload();
                        })
                    }
                    if(res?.error) {
                        showErrorAlert('Gagal merekam absen.');
                    }
                },
                error: () => {
                    showErrorAlert('Gagal merekam absen.');
                }
            })
        }
    }

    const masuk = new LogMasuk();

    masuk.init();

    $('#log-masuk-btn').click(masuk.log);
    $('#btn-req-location').click(() => {
        masuk.initLocation();
    });

    $('#btn-record').click(() => {
        if (capture.done && masuk.locationDone) {
            masuk.submit();
        } else if(!masuk.locationDone) {
            showErrorAlert('Harap tentukan lokasi terlebih dahulu.');
        } else if(!capture.done) {
            showErrorAlert('Harap ambil gambar terlebih dahulu.');
        }
    });
</script>