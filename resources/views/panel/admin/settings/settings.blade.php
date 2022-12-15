@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
    integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
    crossorigin="" />

<style>
    #map {
        width: auto;
        height: 300px;
        margin-bottom: 32px;
    }
</style>

<div class="container body">
    <div class="main_container">
        <!-- sidebar -->
        @include('layouts.sidebar')
        @include('layouts.topbar')

        <!-- /page content -->
        <div class="right_col" role="main">
            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Umum</h4>
                            </div>
                            <form action="#" onsubmit="submitGeneralForm(event)" method="POST">
                                @csrf
                                @method("POST")
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="jenis_jatah">Pilih Jenis Jatah</label>
                                        <select name="" id="jenis-jatah" class="form-control">
                                            <option value="jatah_cuti_tahunan" data-label="TAHUNAN" data-value="{{ $data->jatah_cuti_tahunan }}">Cuti Tahunan</option>
                                            <option value="jatah_cuti_besar" data-label="BESAR" data-value="{{ $data->jatah_cuti_besar }}">Cuti Besar</option>
                                            <option value="jatah_cuti_melahirkan" data-label="MELAHIRKAN" data-value="{{ $data->jatah_cuti_melahirkan }}">Cuti Melahirkan</option>
                                            <option value="jatah_cuti_penting" data-label="ALASAN PENTING" data-value="{{ $data->jatah_cuti_penting }}">Cuti Alasan Penting</option>
                                            <option value="jatah_cuti_ctln" data-label="DILUAR TANGGUNGAN NEGARA" data-value="{{ $data->jatah_cuti_ctln }}">Cuti Diluar Tanggungan Negara</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jatah_cuti_tahunan">Jatah cuti <span id="cuti-label">TAHUNAN</span> pegawai (Hari)</label>
                                        <input type="number" class="form-control" name="jatah_cuti_tahunan" id="jatah-cuti"
                                         value="{{ $data->jatah_cuti_tahunan }}"
                                            placeholder="Masukan angka">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Perbarui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-3 card-title">Atur Lokasi Kantor</h4>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <h4>Anda bisa mencari lokasi atau memilih lokasi melalui peta.</h4>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="input-search">Cari Alamat</label>
                                    <div class="input-group">
                                        <input type="text" placeholder="Cari Alamat..." id="input-search"
                                            class="form-control">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-primary" id="search-btn"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div id="search-result" class="mb-3"></div>
                                <div id="map-wrapper">
                                    <div id="map"
                                        class="border bg-secondary mb-3 d-flex align-items-center justify-content-center">
                                        <h3 class="text-center text-white">Memuat peta...</h3>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h4>Latitude : <span id="lat">Loading...</span></h4>
                                    <h4>Longitue : <span id="long">Loading...</span></h4>
                                    <h4 id="location">Loading...</h4>
                                </div>
                                @csrf
                                <button class="btn btn-primary" id="submit-location-btn">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Sistem Informasi Absensi Kab. Bengkalis &copy 2022
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
    integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
    crossorigin=""></script>

<script>
    class Settings {
        changeJenisCuti(){
            const name = $(this).val();
            const value = $(this).find('option:selected').data('value');
            const label = $(this).find('option:selected').data('label');
            $('#cuti-label').text(label);
            $('#jatah-cuti').attr('name', name).val(value);
        }
    }

    const setting = new Settings();

    $('#jenis-jatah').change(setting.changeJenisCuti)

    // $(document).ready(function () {
        const apiKey = '31df3da80fa84a71af42f092cb0070ff';

        const getDeviceLocation = () => {
            return new Promise((resolve, reject) => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(async ({ coords: { latitude, longitude } }) => {
                        resolve({ latitude, longitude });
                    })
                } else {
                    showErrorAlert('Lokasi tidak didukung di perangkat.');
                    reject('Lokasi tidak didukung di perangkat.');
                }
            })
        }

        const saveCoordAndRenderMap = () => {
            return new Promise((resolve) => {
                getDeviceLocation()
                    .then(async ({ latitude, longitude}) => {
                        const location = await getActualLocation(latitude, longitude);
                        const stringifyNewCoord = JSON.stringify({
                            lat: latitude,
                            long: longitude,
                        });
                        resolve({ latitude, longitude });
                        localStorage.setItem('saved_coord', stringifyNewCoord);
                        renderMap(true);
                    })
            })
        }

        let lat = `{{ $data->base_lat ?? 0 }}`;
        let long = `{{ $data->base_long ?? 0 }}`;
        const savedCoord = localStorage.getItem('saved_coord');

        if (savedCoord) {
            const parsedSavedCoord = JSON.parse(savedCoord);
            const toFixed = num => parseFloat(num).toFixed(3);
            if (toFixed(parsedSavedCoord.lat) === toFixed(lat) && toFixed(parsedSavedCoord.long) === toFixed(long)) {
                renderMap();
            } else {
                saveCoordAndRenderMap().then(res => {
                    lat = res.latitude;
                    long = res.longitude;
                });
            }
        } else {
            saveCoordAndRenderMap().then(res => {
                lat = res.latitude;
                long = res.longitude;
            });
        }

        function getActualLocation(lat, long){
            const apiURL = `https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${long}&apiKey=${apiKey}`;
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await fetch(apiURL);
                    const json = await response.json();
                    const { address_line1, address_line2 } = json.features[0].properties;
                    const newLoc = [address_line1, address_line2].join(', ');
                    resolve(newLoc);
                } catch (err) {
                    reject('Gagal mendapatkan lokasi.');
                }
            })
        }

        async function renderMap(newLocation = false) {
            $('#map-wrapper #map').remove();
            $('#map-wrapper').append('<div id="map"></div>');

            const map = L.map('map', {
                center: [lat, long],
                zoom: 16,
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            const marker = L.marker([lat, long]).addTo(map);
            
            $('#lat').text(lat);
            $('#long').text(long);

            const savedCoord = localStorage.getItem('saved_coord');
            const parsed = JSON.parse(savedCoord);
            if (newLocation) {
                getActualLocation(lat, long)
                    .then(res => {
                        parsed.location = res;
                        marker.bindPopup(res).openPopup();
                        $('#location').text(res);
                        localStorage.setItem('saved_coord', JSON.stringify(parsed));
                    })
            } else {
                marker.bindPopup(parsed.location).openPopup();
                $('#location').text(parsed.location);
            }

            map.on('click', handleMapClick);
        }

        function handleMapClick(e) {
            const { lat: eLat, lng: eLong } = e.latlng;
            lat = eLat;
            long = eLong;
            renderMap();
        }

        async function searchLocation() {
            const searchValue = $('#input-search').val();
            if(searchValue === '') {
                showErrorAlert('Mohon masukkan keyword pencarian.');
                return;
            }
            try {
                const response = await fetch(`https://api.geoapify.com/v1/geocode/search?text=${searchValue}&format=json&filter=countrycode:id&apiKey=${apiKey}`);
                const json = await response.json();
                const template = `
                    <h4>Hasil Pencarian</h4>
                    <ol>
                        ${json.results.map(item => `<li style="font-size: 16px;">${[item.address_line1, item.address_line2].join(', ')} (${item.lat} - ${item.lon})\n
                            <button class="btn btn-primary btn-sm d-block mark-btn" data-item='${JSON.stringify(item)}'><i class="fas fa-map-marker"></i> <small>tandai</small> </button></li>`).join('')}
                    </ol>
                `;
                if(json.results.length === 0 ){
                    $('#search-result').html('<h4>Hasil tidak ditemukan.</h4>');
                } else {
                    $('#search-result').html(template);
                }
            } catch (error) {
                console.log(error);
            }
        }

        async function submitLocationForm() {
            const _token = $('input[name=_token]').val();
            const payload = {
                base_lat: lat,
                base_long: long,
                _token,
            }
            $.ajax(`{{ route('settings.store') }}`, {
                method: 'POST',
                data: payload,
                success: res => {
                    if(res.success) {
                        showSuccessAlert(res.success, () => window.location.reload());
                    }
                },
                error: err => {
                    console.log(err);
                }
            })
        }

        async function submitGeneralForm(e) {
            e.preventDefault();
            const inputs = e.target.querySelectorAll('input');
            const formData = new FormData();
            Array.from(inputs).forEach(el => {
                formData.append(el.getAttribute('name'), el.value);
            });
            try {
                const response = await fetch(`{{ route('settings.store') }}`, {
                    method: 'POST',
                    body: formData,
                });
                const json = await response.json();
                if (json.success) {
                    showSuccessAlert(json.success, () => window.location.reload());
                }
            } catch (error) {
                showErrorAlert('Ups, terjadi kesalahan.');
                console.log(error);
            }
        }
        
        $('#search-btn').click(searchLocation);
        $('#search-result').on('click', '.mark-btn', function () {
            const item = $(this).data("item");
            lat = item.lat;
            long = item.lon;
            renderMap();
            $('#search-result').html('');
            $('#input-search').val('');
        })

        $('#submit-location-btn').click(submitLocationForm);
    // })

</script>
@endsection