@if ($has_cuti->count() > 0)
<style>
    .dtcc  {
        border: 1px solid #e6e6e6;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }
    .dtcc h4 {
        font-size: 1rem;
    }
    .dtcc span {
        font-size: 12px;
    }
</style>
<div class="card dtcc">
    <div class="card-header">
        <h4 class="card-title">Anda memiliki cuti yang disetujui.</h4>
    </div>
    <div class="card-body">
        <table class="table table-sm">
            <tbody>
                <tr>
                    <td>Tanggal</td>
                    <td class="d-flex" style="column-gap: 4px">
                        <span>:</span>
                        <div class="d-flex flex-wrap" style="gap: 4px">
                            @foreach ($has_cuti->last()->tanggal as $tgl)
                            <span class="chip in-table bg-secondary px-3 text-white">{{
                                $tgl }}</span>
                            @endforeach
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>: {{$has_cuti->last()->total}} Hari</td>
                </tr>
                <tr>
                    <td>Bukti</td>
                    <td>:
                        @if ($has_cuti->last()->bukti)
                        <a href="{{ Storage::url('public/uploads/'.$has_cuti->last()->bukti) }}" target="_blank"
                            class="text-primary">Download</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>: {{$has_cuti->last()->keterangan}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif