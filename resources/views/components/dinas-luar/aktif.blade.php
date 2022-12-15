@if (!empty($data))
<style>
    .dtdn h4 {
        font-size: 1rem;
    }
</style>
<div class="col-md-6">
    <div class="card mb-3 dtdn">
        <div class="card-body">
            <h4 class="mb-3">Anda memiliki JADWAL DINAS LUAR.</h4>
            <hr>
            <div class="row" style="font-size: 12px; row-gap: 14px;">
                <div class="col-md-4"><span>Tanggal Mulai</span></div>
                <div class="col-md-8"><span>: {{$data->mulai}}</span></div>
                <div class="col-md-4"><span>Tanggal Selesai</span></div>
                <div class="col-md-8"><span>: {{$data->selesai}}</span></div>
                <div class="col-md-4"><span>Tujuan</span></div>
                <div class="col-md-8"><span>: {{$data->tujuan}}</span></div>
                <div class="col-md-4"><span>Surat Jalan</span></div>
                <div class="col-md-8"><span>:
                        @if ($data->file)
                        <a href="{{ Storage::url('public/uploads/'.$data->file) }}" target="_blank"
                            class="text-primary">Download</a>
                        @endif
                    </span></div>
                <div class="col-md-4"><span>Keterangan</span></div>
                <div class="col-md-8"><span>: {{$data->keterangan}}</span></div>
            </div>
        </div>
    </div>
</div>
@endif