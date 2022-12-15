@extends('layouts.app')
@section('title', 'Preload Setting')


@section('content')
<style>
    .h100vh {
        height: 100vh !important;
    }
</style>
<div class="container">
    <div class="x_content">
        <div class="row align-items-center justify-content-center h100vh">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title text-center">Perbarui Konfigurasi</h4>
                        <p class="card-text">Sistem belum terkonfigurasi, agar dapat mengakses sistem pertama kali,
                            <b>harap</b> lengkapi beberapa konfigurasi berikut.
                        </p>
                        <hr>

                        @if ($allowed)
                        <form action="" method="POST">
                            @csrf
                            <div id="inputs"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="base_lat">Latitude</label>
                                        <input type="number" class="form-control" readonly required name="base_lat" id="base_lat"
                                            aria-describedby="base_lat" placeholder="Latitude...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="base_long">Longitude</label>
                                        <input type="number" class="form-control" readonly required name="base_long"
                                            id="base_long" aria-describedby="base_long" placeholder="Longitude...">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-info btn-sm" id="btn-location" type="button">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i> <span>Dapatkan Lokasi</span>
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <button class="btn btn-primary btn-sm" type="button" id="submit-btn">
                                    Simpan
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="alert alert-secondary text-center" role="alert">
                            <i class="fa fa-info-circle mb-3" style="font-size: 2rem" aria-hidden="true"></i>
                            <h4 class="alert-heading mb-3">Anda tidak diberikan akses mengkonfigurasi Sistem. Minta
                                Administrator agar memperbarui konfigurasi sistem.</h4>
                            <a href="{{ route('logout') }}" class="btn btn-primary">
                                Logout
                            </a>
                        </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($allowed)
<script>
    class Preload {
        constructor() {
            this.init();
            this.base_lat = $('#base_lat').val();
            this.base_long = $('#base_long').val();
            $('#submit-btn').click(() => this.submitForm());
            $('#btn-location').click(() => this.getLocation())
        }

        formInput = [
            {
                name: 'jatah_cuti_tahunan',
                label: 'Jatah Cuti Tahunan',
                type: 'number',
            },
            {
                name: 'jatah_cuti_besar',
                label: 'Jatah Cuti Besar',
                type: 'number',
            },
            {
                name: 'jatah_cuti_melahirkan',
                label: 'Jatah Cuti Melahirkan',
                type: 'number',
            },
            {
                name: 'jatah_cuti_penting',
                label: 'Jatah Cuti Karena Alasan Penting',
                type: 'number',
            },
            {
                name: 'jatah_cuti_ctln',
                label: 'Jatah Cuti Diluar Tanggungan Negara',
                type: 'number',
            },
        ];

        renderInputs() {
            return this.formInput.map(input => `<div class="form-group">
                        <label for="${input.name}">${input.label} (Hari)</label>
                        <input type="${input.type}"
                        class="form-control" name="${input.name}" id="${input.name}" value="0" required>
                    </div>`).join('');
        }

        init() {
            $('#inputs').html(() => {
                return this.renderInputs();
            });
        }

        setVal(id, val) {
            this[id] = val;
            $(`#${id}`).val(val);
        }

        getLocation() {
            if (navigator.geolocation) {
                const showPos = pos => {
                    const { latitude, longitude } = pos.coords;
                    this.setVal('base_lat', latitude);
                    this.setVal('base_long', longitude);
                }
                const showErr = () => {
                    showErrorAlert('Harap izinkan akses lokasi.');
                }
                navigator.geolocation.getCurrentPosition(showPos, showErr)
            } else {
                showErrorAlert('Browser tidak mendukung akses lokasi.');
            }
        }

        submitForm() {
            const intParser = $str => parseInt($str, 10);
            if (isNaN(intParser(this.base_lat)) || isNaN(intParser(this.base_long))) {
                showErrorAlert('Harap dapatkan lokasi dahulu.');
                return;
            }
            $('form').submit();
        }

    }

    new Preload();
</script>
@endif

@endsection