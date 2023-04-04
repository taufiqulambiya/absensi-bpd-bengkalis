@if (!empty($data))
<style>
    .dtis {
        border: 1px solid #e6e6e6;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }
    .dtis h4 {
        font-size: 1rem;
    }
</style>
<div class="card dtis">
    <div class="card-header">
        <h4 class="card-title">Anda memiliki izin yang disetujui.</h4>
    </div>
    <div class="card-body">
        <table class="table table-sm">
            <tbody>
                <tr>
                    <td>Tanggal Mulai</td>
                    <td>: {{$data->tgl_mulai}}</td>
                </tr>
                <tr>
                    <td>Tanggal Selesai</td>
                    <td>: {{$data->tgl_selesai}}</td>
                </tr>
                <tr>
                    <td>Jenis</td>
                    <td>: {{$data->jenis}}</td>
                </tr>
                <tr>
                    <td>Bukti</td>
                    <td>:
                        @if ($data->bukti)
                        <a href="{{ Storage::url('public/uploads/'.$data->bukti) }}" target="_blank"
                            class="text-primary">Download</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>: {{$data->keterangan}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif