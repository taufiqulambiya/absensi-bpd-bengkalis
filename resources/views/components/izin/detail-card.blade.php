@if (!empty($has_izin))

<style>
    .dtis h4 {
        font-size: 1rem;
    }
</style>
<div class="card dtis">
    <div class="card-body">
        <h4 class="mb-3">Anda memiliki izin yang disetujui.</h4>
        <hr>
        <div class="row" style="font-size: 12px; row-gap: 14px;">
            <div class="col-md-4"><span>Tanggal Mulai</span></div>
            <div class="col-md-8"><span>: {{$has_izin->tgl_mulai}}</span></div>
            <div class="col-md-4"><span>Tanggal Selesai</span></div>
            <div class="col-md-8"><span>: {{$has_izin->tgl_selesai}}</span></div>
            <div class="col-md-4"><span>Jenis</span></div>
            <div class="col-md-8"><span>: {{$has_izin->jenis}}</span></div>
            <div class="col-md-4"><span>Bukti</span></div>
            <div class="col-md-8"><span>:
                    @if ($has_izin->bukti)
                    <a href="{{ Storage::url('public/uploads/'.$has_izin->bukti) }}" target="_blank"
                        class="text-primary">Download</a>
                    @endif
                </span></div>
            <div class="col-md-4"><span>Keterangan</span></div>
            <div class="col-md-8"><span>: {{$has_izin->keterangan}}</span></div>
        </div>
    </div>
</div>
@endif