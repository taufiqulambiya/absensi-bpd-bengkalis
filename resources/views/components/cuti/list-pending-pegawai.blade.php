<table class="datatable table table-striped table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Total</th>
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
            <td>
                <div class="d-flex flex-wrap" style="gap: 4px">
                    @foreach ($item->tanggal as $tgl)
                    <span class="chip bg-secondary text-white">{{
                        $tgl }}</span>
                    @endforeach
                </div>
            </td>
            <td>{{ $item->total }} Hari</td>
            <td>{{ $item->keterangan }}</td>
            <td>
                @if(Storage::disk('public')->has('uploads/' .
                $item->bukti))
                <a href="{{ Storage::url('public/uploads/'.$item->bukti) }}" target="_blank"
                    class="text-primary text-decoration-none">Download</a>
                @else
                <span class="text-danger">File tidak ada.</span>
                @endif
            </td>
            <td>
                <span class="badge badge-{{ $item->status['color'] }}">{{
                    ucfirst($item->status['text']) }}</span>
            </td>
            <td>
                <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                    data-toggle="modal" data-target="#tracking">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
                <button class="btn btn-info btn-edit" data-toggle="modal" data-target="#modal-form"
                    data-item='{{ json_encode($item) }}' title="Ubah pengajuan">
                    <span class="fas fa-pencil"></span>
                </button>
                <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" data-toggle="modal"
                    data-target="#modal-delete" title="Batalkan pengajuan">
                    <span class="fas fa-times"></span>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>