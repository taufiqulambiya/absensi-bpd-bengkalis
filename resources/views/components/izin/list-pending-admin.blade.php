<table class="datatable table table-striped table-bordered" style="width: 100%">
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
            <td>
                <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                    data-toggle="modal" data-target="#tracking">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
                @if($item->status == 'pending')
                <span class="text-info">Belum disetujui Kabid.</span>
                @elseif ($item->status == 'accepted_admin')
                <button class="btn btn-success" data-id="{{ $item->id }}" title="Cetak">
                    <span class="fas fa-print"></span>
                </button>
                @else
                @csrf
                <button class="btn btn-success acc-izin" data-id="{{ $item->id }}" data-status="accepted_admin"
                    title="Setujui">
                    <span class="fas fa-check"></span>
                </button>
                <button class="btn btn-danger reject-izin" data-id="{{ $item->id }}" title="Tolak">
                    <span class="fas fa-ban"></span>
                </button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>