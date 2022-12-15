<style>
    h5 {
        font-size: 1rem;
    }
</style>

@if ($current)
    <x-absensi.detail-keluar :current="$current" />
@else
<div class="card-body">
    <div class="text-center p-3" id="no-data-out">
        <img src="/img/not_found.png" alt="No Data" class="img-fluid w-50">
        <h4 class="mb-3">Belum ada data.</h4>
        <button class="btn btn-primary d-block mx-auto mb-3" @if ($disable_log) disabled @endif id="btn-log-out">Log
            Absen Keluar</button>
    </div>
    <div id="record-out">
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
                        <h5 id="location-out" class="text-right"><button class="btn btn-primary btn-sm"
                                id="btn-req-location-out">Dapatkan Lokasi</button></h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Jarak Ke Kantor <span class="float-right">:</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h5 id="distance-out" class="text-right">Klik tombol 'Dapatkan
                            Lokasi' terlebih
                            dahulu.</h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item" id="capture-container-out">
                <x-absensi.capture-out />
            </li>
        </ul>
        <div class="text-center">
            @csrf
            <button class="btn btn-success" id="btn-record-out">
                <i class="fa fa-upload mr-2" aria-hidden="true"></i>Submit</button>
        </div>
    </div>
</div>
@endif


<script>
    class LogKeluar {
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
            $('#record-out').hide();
            $('#capture-container-out').hide();
            this.getSavedData();
        }

        log() {
            $('#record-out').show();
            $('#no-data-out').hide();
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
            $('#location-out').text('Loading...');
            $('#distance-out').text('Loading...');
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
                console.log(err);
                showErrorAlert('Ups, ada kesalahan saat menginisialisasi lokasi.');
            }
        }
        
        renderAfterLocation(success = true) {
            if (success) {
                $('#location-out').text(this.location);
                $('#distance-out').text(`${this.distance} Meter.`);
                $('#capture-container-out').show();
            } else {
                const message = 'Ups, terjadi kesalahan saat mendapatkan lokasi.'
                $('#location-out').addClass('text-danger').text(message);
                $('#distance-out').addClass('text-danger').text(message);
            }
        }
        submit() {
            const gambar = captureOut.getCanvasURL();
            const payload = {
                id_user: `{{ $user->id }}`,
                forgotten: `{{ $absensi->terlewat ?? '' }}`,
                _token: $('input[name=_token]').val(),
                waktu_keluar: `{{ date('H:i:s') }}`,
                dok_keluar: gambar,
                lat_keluar: this.lat,
                long_keluar: this.long,
                jarak_keluar: this.distance,
                lokasi_keluar: this.location,
            }
            const idAbsensi = `{{ $absensi->id ?? '' }}`;
            const formURL = `${baseURL}/panel/absensi/${idAbsensi}`;

            $.ajax({
                url: formURL,
                method: 'PUT',
                data: payload,
                success: res => {
                    console.log(res);
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

    const keluar = new LogKeluar();

    keluar.init();

    $('#btn-log-out').click(keluar.log);
    $('#btn-req-location-out').click(() => {
        keluar.initLocation();
    });

    $('#btn-record-out').click(() => {
        if (captureOut.done && keluar.locationDone) {
            keluar.submit();
        } else if(!keluar.locationDone) {
            showErrorAlert('Harap tentukan lokasi terlebih dahulu.');
        } else if(!captureOut.done) {
            showErrorAlert('Harap ambil gambar terlebih dahulu.');
        }
    });
</script>