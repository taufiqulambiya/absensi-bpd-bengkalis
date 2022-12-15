@if ($has_cuti->count() > 0)
<style>
    .dtcc h4 {
        font-size: 1rem;
    }
    .dtcc span {
        font-size: 12px;
    }
</style>
<div class="card dtcc">
    <div class="card-body">
        <h4 class="mb-3">Anda memiliki cuti yang disetujui.</h4>
        <hr>
        <div class="row" style="font-size: 1rem; row-gap: 14px;">
            <div class="col-md-4"><span>Tanggal</span></div>
            <div class="col-md-8 d-flex">
                <div class="d-flex flex-wrap" style="gap: 4px">
                    @foreach ($has_cuti->last()->tanggal as $tgl)
                    <span class="chip in-table bg-secondary px-3 text-white">{{
                        $tgl }}</span>
                    @endforeach
                </div>
                {{-- <ul>
                    @foreach (explode(',', $has_cuti->last()->tanggal) as $item)
                    <li>{{$item}}</li>
                    @endforeach
                </ul> --}}
            </div>
            <div class="col-md-4"><span>Total</span></div>
            <div class="col-md-8"><span>: {{$has_cuti->last()->total}} Hari</span></div>
            <div class="col-md-4"><span>Bukti</span></div>
            <div class="col-md-8"><span>:
                    @if ($has_cuti->last()->bukti)
                    <a href="{{ Storage::url('public/uploads/'.$has_cuti->last()->bukti) }}" target="_blank"
                        class="text-primary">Download</a>
                    @endif
                </span></div>
            <div class="col-md-4"><span>Keterangan</span></div>
            <div class="col-md-8"><span>: {{$has_cuti->last()->keterangan}}</span></div>
        </div>
    </div>
</div>
@endif