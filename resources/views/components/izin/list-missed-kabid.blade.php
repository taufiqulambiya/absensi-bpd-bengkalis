<div class="table-responsive">
    <table class="datatable table table-striped table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>NIP</th>
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
                <td>{{ $item->user->nama }}</td>
                <td>{{ $item->user->nip }}</td>
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
                    @if ($item->status === 'rejected')
                    <div class="text-danger mt-3">
                        {{ $item->reason }}
                    </div>
                    @endif
                </td>
                <td>
                    <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                        data-toggle="modal" data-target="#tracking">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-secondary" style="cursor: not-allowed"
                        onclick="showErrorAlert('Pengajuan sudah terlewat dari tanggal saat ini.')" title="Setujui">
                        <span class="fas fa-check"></span>
                    </button>
                    <button class="btn btn-secondary" style="cursor: not-allowed"
                        onclick="showErrorAlert('Pengajuan sudah terlewat dari tanggal saat ini.')" title="Tolak">
                        <span class="fas fa-ban"></span>
                    </button>
                    <button class="btn btn-warning btn-delete" data-toggle="modal" data-target="#modal-delete"
                        data-id="{{ $item->id }}" title="Hapus">
                        <span class="fas fa-trash"></span>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>