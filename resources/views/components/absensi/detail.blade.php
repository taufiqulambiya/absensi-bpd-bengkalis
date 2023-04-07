
<div class="card mb-3" id="detail-absensi">
    <div class="card-header bg-{{ $type == 'in' ? 'primary' : 'success' }} text-white">
        <h6 class="card-title text-center">{{$switch_data[$type]['title']}}</h6>
    </div>
    <div class="card-body">
        @if ($data->status == 'dinas')
        <div class="alert alert-secondary" role="alert">
            <strong>ABSENSI TERISI OTOMATIS KARENA DINAS LUAR</strong>
        </div>
        @endif
        <ul class="list-group">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h6>{{$switch_data[$type]['title']}} <span class="float-right">:</span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-right">{{ $switch_data[$type]['waktu'] }}</h6>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Jam Absen <span class="float-right">:</span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-right">{{ $data->formatted_shift }}</h6>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Tanggal <span class="float-right">:</span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-right">{{ $data->formatted_tanggal }}
                        </h6>
                    </div>
                </div>
            </li>
            @if ($data->status == 'dinas')
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Lokasi Absen <span class="float-right">:</span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-right">-- ABSENSI TERISI KARENA DINAS LUAR --</h6>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Jarak ke Kantor <span class="float-right">:</span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-right">-- ABSENSI TERISI KARENA DINAS LUAR --</h6>
                    </div>
                </div>
            </li>
            @else
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Lokasi Absen <span class="float-right">:</span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-right">{{ $switch_data[$type]['lokasi'] }}</h6>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Jarak ke Kantor <span class="float-right">:</span></h6>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-right">{{ $switch_data[$type]['jarak'] }}</h6>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Dokumentasi <span class="float-right">:</span></h6>
                    </div>
                    <div class="col-md-6">
                        @php
                        $dok = $switch_data[$type]['dok']
                        @endphp
                        <img src="{{ Storage::url('public/uploads/'.$dok) }}" alt="Dokumentasi Masuk"
                            class="img-thumbnail" style="aspect-ratio: 1; object-fit: contain; cursor: pointer;"
                            onclick="window.open(event.target.src)">
                        <p class="text-sm text-info text-center">klik untuk memperbesar</p>
                    </div>
                </div>
            </li>
            @endif
        </ul>
    </div>
</div>