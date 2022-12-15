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
                @csrf
                <button class="btn btn-sm btn-warning btn-delete" data-id="{{$item->id}}" data-toggle="modal"
                    data-target="#modal-delete" title="Buang">
                    <span class="fas fa-trash"></span>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>