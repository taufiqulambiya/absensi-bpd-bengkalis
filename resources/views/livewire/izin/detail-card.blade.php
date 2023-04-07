@if ($isShow)
<div class="card mb-3">
    <div class="card-body">
        <div class="alert alert-info">
            <h4 class="alert-heading">Informasi</h4>
            <p class="mb-0">Anda memiliki izin yang sedang berlangsung.</p>
        </div>
        <ul class="list-group">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">Tanggal Mulai</div>
                    <div class="col-8">
                        {{$data->tgl_mulai}}
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">Tanggal Selesai</div>
                    <div class="col-8">
                        {{$data->tgl_selesai}}
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">Total</div>
                    <div class="col-8">
                        {{$data->total}}
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">Jenis</div>
                    <div class="col-8">{{ strtoupper($data->jenis) }}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">Keterangan</div>
                    <div class="col-8">{{ $data->keterangan }}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">Bukti</div>
                    <div class="col-8">
                        @if ($data->bukti)
                        <a href="{{ Storage::url('public/izin/'.$data->bukti) }}" target="_blank"
                            class="text-primary">Download</a>
                        @endif
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
@endif