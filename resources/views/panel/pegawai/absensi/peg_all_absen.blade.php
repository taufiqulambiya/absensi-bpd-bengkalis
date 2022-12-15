<div class="x_content">
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
                                    <h4 class="text-right">{{ $has_absen->waktu_masuk }}</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Jam Absen <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ $has_absen->formatted_shift }}
                                    </h4>
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
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Waktu Keluar <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ $has_absen->waktu_keluar }}</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Jam Absen <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ $has_absen->formatted_shift }}
                                    </h4>
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
                                    <h4 class="text-right">{{ $has_absen->lokasi_keluar }}</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Jarak ke Kantor <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-right">{{ $has_absen->jarak_keluar }} Meter</h4>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Dokumentasi <span class="float-right">:</span></h4>
                                </div>
                                <div class="col-md-6">
                                    <img src="{{ Storage::url('public/uploads/'.$has_absen->dok_keluar) }}"
                                        alt="Dokumentasi Keluar" class="img-thumbnail"
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
    </div>
</div>