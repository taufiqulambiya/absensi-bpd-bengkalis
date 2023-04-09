@if ($isShow)
<div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="alert alert-info">
                <h4 class="alert-heading">Informasi</h4>
                <p class="mb-0">Anda memiliki cuti yang sedang berlangsung.</p>
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">Tanggal Mulai</div>
                        <div class="col-8">{{ $cuti->mulai_formatted }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">Tanggal Selesai</div>
                        <div class="col-8">{{ $cuti->selesai_formatted }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">Total</div>
                        <div class="col-8">{{ $cuti->total_formatted }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">Jenis</div>
                        <div class="col-8">{{ strtoupper($cuti->jenis) }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">Keterangan</div>
                        <div class="col-8">{{ $cuti->keterangan }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-4">Bukti</div>
                        <div class="col-8">
                            @if ($cuti->bukti)
                            <a href="{{ Storage::url('public/cuti/'.$cuti->bukti) }}" target="_blank"
                                class="text-primary">Download</a>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endif