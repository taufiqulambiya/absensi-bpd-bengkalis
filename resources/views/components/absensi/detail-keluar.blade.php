<style>
    h4:not(.card-title) {
        font-size: 1rem;
    }
</style>

<div class="card-body">
    @if ($current_absensi->status == 'dinas')
    <div class="alert alert-secondary" role="alert">
        <strong>ABSENSI TERISI OTOMATIS KARENA DINAS LUAR</strong>
    </div>
    @endif
    <ul class="list-group">
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <h4>Waktu Keluar <span class="float-right">:</span></h4>
                </div>
                <div class="col-md-6">
                    <h4 class="text-right">{{
                        date_format(date_create($current_absensi->waktu_keluar),
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
                    <h4 class="text-right">{{ $current_absensi->formatted_shift }}</h4>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <h4>Tanggal <span class="float-right">:</span></h4>
                </div>
                <div class="col-md-6">
                    <h4 class="text-right">{{ date_format(date_create($current_absensi->tanggal), 'd F Y') }}
                    </h4>
                </div>
            </div>
        </li>
        @if ($current_absensi->status == 'dinas')
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <h4>Lokasi Absen <span class="float-right">:</span></h4>
                </div>
                <div class="col-md-6">
                    <h4 class="text-right">-- ABSENSI TERISI KARENA DINAS LUAR --</h4>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <h4>Jarak ke Kantor <span class="float-right">:</span></h4>
                </div>
                <div class="col-md-6">
                    <h4 class="text-right">-- ABSENSI TERISI KARENA DINAS LUAR --</h4>
                </div>
            </div>
        </li>
        @else
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <h4>Lokasi Absen <span class="float-right">:</span></h4>
                </div>
                <div class="col-md-6">
                    <h4 class="text-right">{{ $current_absensi->lokasi_keluar }}</h4>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <h4>Jarak ke Kantor <span class="float-right">:</span></h4>
                </div>
                <div class="col-md-6">
                    <h4 class="text-right">{{ $current_absensi->jarak_keluar }} Meter</h4>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <h4>Dokumentasi <span class="float-right">:</span></h4>
                </div>
                <div class="col-md-6">
                    <img src="{{ Storage::url('public/uploads/'.$current_absensi->dok_keluar) }}"
                        alt="Dokumentasi keluar" class="img-thumbnail"
                        style="aspect-ratio: 1; object-fit: contain; cursor: pointer;"
                        onclick="window.open(event.target.src)">
                    <p class="text-sm text-info text-center">klik untuk memperbesar</p>
                </div>
            </div>
        </li>
        @endif
    </ul>
</div>