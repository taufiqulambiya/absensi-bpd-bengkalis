<table class="datatable table table-striped table-bordered" id="list-done" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Jenis Izin</th>
            <th>Mulai</th>
            <th>Hingga</th>
            <th>Durasi</th>
            <th>Keterangan</th>
            <th>Bukti</th>
            <th>Status</th>
            @if ($is_with_rejected)
                <th>Alasan Penolakan</th>
            @endif
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $item->jenis }}</td>
            <td>{{ $item->tgl_mulai }}</td>
            <td>{{ $item->tgl_selesai }}</td>
            <td>{{ $item->durasi }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>
                <a href="{{ Storage::url('public/uploads/'.$item->bukti) }}" target="_blank"
                    class="text-primary text-decoration-none">Download</a>
            </td>
            <td>
                <span class="badge badge-{{ $item->status_class }}">{{
                    $item->status_text }}</span>
            </td>
            @if ($is_with_rejected)
            <td>{{ $item->reason ?? '-' }}</td>
            @endif
            <td>
                <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                    data-toggle="modal" data-target="#tracking">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
                <button class="btn btn-success btn-print" data-item="{{ $item }}">
                    <i class="fas fa-print"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>