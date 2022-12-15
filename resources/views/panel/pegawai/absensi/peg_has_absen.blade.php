<div class="x_content">
    <div
        class="alert @if($has_absen->status == 'masuk') alert-info @endif @if($has_absen->status == 'keluar') alert-success @endif">
        <h4 class="text-white text-center">Selamat, Anda sudah LOG ABSEN @if($has_absen->status == 'masuk') MASUK @endif
            @if($has_absen->status == 'keluar') KELUAR @endif HARI INI.</h4>
    </div>

    {{-- CSRF TOKEN --}}
    @csrf
    {{-- END CSRF TOKEN --}}

    <div class="row">
        <input type="hidden" name="absen-type" value="keluar" id="absen-type">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title text-center">Absensi Masuk</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Waktu Masuk <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{
                                        date_format(date_create($has_absen->waktu_masuk),
                                        'H:i')
                                        }} WIB</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Jam Absen <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ $has_absen->formatted_shift }}</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Tanggal <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ date_format(date_create($has_absen->tanggal), 'd F Y') }}
                                    </h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Lokasi Absen <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ $has_absen->lokasi_masuk }}</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Jarak ke Kantor <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ $has_absen->jarak_masuk }} Meter</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Dokumentasi <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <img src="{{ Storage::url('public/uploads/'.$has_absen->dok_masuk) }}"
                                        alt="Dokumentasi Masuk" class="img-thumbnail"
                                        style="aspect-ratio: 1; object-fit: contain; cursor: pointer;"
                                        onclick="window.open(event.target.src)">
                                    <p class="text-sm text-info text-center">klik untuk memperbesar</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title text-center">Absensi Keluar</h4>
                </div>
                <div class="card-body">
                    <div class="text-center p-3" id="no-data-keluar">
                        <img src="/img/not_found.png" alt="No Data" class="img-fluid w-50">
                        <h4 class="mb-3">Belum ada data.</h4>
                        <button class="btn btn-success d-block mx-auto mb-3" id="log-keluar-btn">Log Absen
                            Keluar</button>
                    </div>
                    <ul class="list-group d-none" id="data-keluar-before">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Waktu <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ date('H:i') }} WIB</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Tanggal <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ date('d/m/Y') }}</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Jam Absen <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ $jam_kerja->formatted }}</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Lokasi <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 id="location" class="text-right"><button class="btn btn-primary btn-sm"
                                            id="btn-req-location">Dapatkan Lokasi</button></h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Jarak Ke Kantor <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 id="distance" class="text-right">Klik tombol 'Dapatkan Lokasi' terlebih
                                        dahulu.</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="card card-captures d-none">
                                <div class="card-body">
                                    <div class="captures-container">
                                        <h4 class="mb-3 text-danger text-center">Harap ambil
                                            gambar foto
                                            diri Anda sebagai dokumentasi.</h4>
                                        <div id="stream">
                                            <video autoplay height="500"></video>
                                            <img class="mb-2 mx-auto" height="500">
                                            <canvas class="mb-3" height="500"></canvas>
                                        </div>

                                        <div class="capture-btn-container">
                                            <button class="btn btn-primary btn-sm" id="capture-btn">
                                                <i class="fas fa-camera"></i>
                                                Ambil Gambar
                                            </button>
                                            <button class="btn btn-warning btn-sm" id="retake-btn">
                                                <i class="fas fa-refresh"></i>
                                                Ulangi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item text-center d-none" id="list-rekam-keluar">
                            @csrf
                            <button class="btn btn-primary" disabled id="btn-rekam-keluar">Rekam Sekarang</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>