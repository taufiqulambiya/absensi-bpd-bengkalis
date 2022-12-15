<div>
    <div class="card mx-1 my-3">
        <div class="card-header">
            <h4 class="card-title">Cuti Aktif</h4>
        </div>
        <div class="card-body">
            <div class="row" style="font-size: 1rem; row-gap: 14px;">
                <div class="col-md-4"><span>Tanggal</span></div>
                <div class="col-md-8 d-flex">
                    <span>:</span>
                    <div class="d-flex flex-wrap" style="gap: 4px">
                        @foreach ($has_cuti->tanggal as $tgl)
                        <span class="chip bg-secondary text-white">{{ $tgl }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-4"><span>Total</span></div>
                <div class="col-md-8"><span>: {{$has_cuti->total}}</span>
                </div>
                <div class="col-md-4"><span>Bukti</span></div>
                <div class="col-md-8"><span>:
                        @if ($has_cuti->bukti)
                        <a href="{{ Storage::url('public/uploads/'.$has_cuti->bukti) }}" target="_blank"
                            class="text-primary">Download</a>
                        @endif
                    </span></div>
                <div class="col-md-4"><span>Keterangan</span></div>
                <div class="col-md-8"><span>: {{$has_cuti->keterangan}}</span></div>
            </div>
        </div>
    </div>
</div>