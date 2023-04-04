<style>
    h5 {
        font-size: 1rem;
    }
</style>
<div class="card-body" id="record-in">
    <div class="text-center p-3 no-data">
        <img src="/img/not_found.png" alt="No Data" class="img-fluid w-50">
        <h4 class="mb-3">Belum ada data.</h4>
        <button class="btn btn-primary d-block mx-auto mb-3 show-record" @if ($disable_log) disabled @endif>Log
            Absen Masuk</button>
    </div>
    <div class="record d-none">
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
                        <h5 class="text-right">{{ $jam_kerja->formatted ?? '-' }}</h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Lokasi <span class="float-right">:</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-right location">
                            <button class="btn btn-primary btn-sm">Dapatkan Lokasi</button>
                            <div class="result"></div>
                        </h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Jarak Ke Kantor <span class="float-right">:</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-right distance">Klik tombol 'Dapatkan
                            Lokasi' terlebih
                            dahulu.</h5>
                    </div>
                </div>
            </li>
            <li class="list-group-item capture d-none">
                <div class="precapture-box">
                    <div class="inner">
                        <div class="text-center">
                            <h5 class="mb-3">Ambil Foto</h5>
                            <img src="/img/camera.png" alt="Camera" class="img-fluid w-50">
                        </div>
                    </div>
                </div>
                <video autoplay></video>
                <canvas class="d-none img-fluid"></canvas>
                <div class="actions d-flex justify-content-center">
                    <button class="btn btn-primary btn-sm btn-start">
                        <i class="fa fa-camera mr-2" aria-hidden="true"></i> Mulai</button>
                    </button>
                    {{-- capture --}}
                    <button class="btn btn-primary btn-sm btn-capture d-none">
                        <i class="fa fa-camera mr-2" aria-hidden="true"></i> Ambil Gambar</button>
                    </button>
                    {{-- refresh --}}
                    <button class="btn btn-warning btn-sm btn-retake d-none">
                        <i class="fa fa-refresh mr-2" aria-hidden="true"></i> Ulangi</button>
                    </button>
                    {{-- check --}}
                    <button class="btn btn-success btn-sm btn-finish d-none">
                        <i class="fa fa-check mr-2" aria-hidden="true"></i> Selesai</button>
                    </button>
                </div>
            </li>
        </ul>
        <div class="text-center">
            @csrf
            <button class="btn btn-success btn-submit d-none">
                <i class="fa fa-upload mr-2" aria-hidden="true"></i>
                <span>Submit</span>
            </button>
        </div>
    </div>
</div>
