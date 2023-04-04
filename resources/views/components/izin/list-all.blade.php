{{-- data container --}}
<div class="data-container" data-data="{{ json_encode($data) }}"></div>
{{-- end data container --}}

{{-- style --}}
<style>
    .action {
        width: 100px;
    }

    .action button {
        margin-right: 5px;
    }

    .action button:last-child {
        margin-right: 0;
    }
</style>

@if ($role == 'pegawai')
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
            <td><a href="{{ Storage::url('public/uploads/'.$item->bukti) }}" target="_blank"
                    class="text-primary text-decoration-none">Download</a></td>
            <td><span class="badge badge-{{ $item->status['color'] }}">{{
                    ucfirst($item->status['text']) }}</span></td>
            <td class="action">
                @if (in_array('show-track', $action_shows))
                <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                    data-toggle="modal" data-target="#tracking">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
                @endif
                @if (in_array('edit', $action_shows))
                <button class="btn btn-info btn-edit" data-toggle="modal" data-target="#modal-form"
                    data-item='{{ json_encode($item) }}' title="Ubah pengajuan">
                    <span class="fas fa-pencil" style="pointer-events: none"></span>
                </button>
                @endif
                @if (in_array('delete', $action_shows))
                <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" title="Batalkan pengajuan">
                    <span class="fas fa-times"></span>
                </button>
                @endif
                @if (in_array('print', $action_shows))
                <button class="btn btn-success print-izin" data-id="{{ $item->id }}" title="Cetak">
                    <span class="fas fa-print"></span>
                </button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if ($role == 'kabid')
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
                    <span class="badge badge-{{ $item->status['color'] }}">{{
                        $item->status['text'] }}</span>
                    @if ($item->status['ori'] === 'rejected')
                    <div class="text-danger mt-3">
                        {{ $item->reason }}
                    </div>
                    @endif
                </td>
                <td class="action">
                    @if (in_array('show-track', $action_shows))
                    <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                        data-toggle="modal" data-target="#tracking">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                    @endif
                    @if (in_array('delete', $action_shows))
                    <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" title="Hapus pengajuan">
                        <span class="fas fa-times"></span>
                    </button>
                    @endif
                    @if (in_array('accept', $action_shows))
                    <button class="btn btn-success acc-izin" data-id="{{ $item->id }}" data-status="accepted_kabid"
                        title="Setujui">
                        <span class="fas fa-check"></span>
                    </button>
                    @endif
                    @if (in_array('reject', $action_shows))
                    <button class="btn btn-danger reject-izin" data-id="{{ $item->id }}" title="Tolak">
                        <span class="fas fa-ban"></span>
                    </button>
                    @endif
                    @if (in_array('print', $action_shows))
                    <button class="btn btn-success print-izin" data-id="{{ $item->id }}" title="Cetak">
                        <span class="fas fa-print"></span>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if ($role == 'admin')
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
                <span class="badge badge-{{ $item->status['color'] }}">{{
                    $item->status['text'] }}</span>
            </td>
            <td class="action">
                @if (in_array('show-track', $action_shows))
                <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                    data-toggle="modal" data-target="#tracking">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
                @endif
                @if($item->status['ori'] == 'pending')
                <span class="text-info">Belum disetujui Kabid.</span>
                @endif

                @if (in_array('print', $action_shows))
                <button class="btn btn-success print-izin" data-id="{{ $item->id }}" title="Cetak">
                    <span class="fas fa-print"></span>
                </button>
                @endif
                @if (in_array('delete', $action_shows))
                <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" title="Hapus pengajuan">
                    <span class="fas fa-times"></span>
                </button>
                @endif
                @if (in_array('accept', $action_shows))
                <button class="btn btn-success acc-izin" data-id="{{ $item->id }}" data-status="accepted_admin"
                    title="Setujui">
                    <span class="fas fa-check"></span>
                </button>
                @endif
                @if (in_array('reject', $action_shows))
                <button class="btn btn-danger reject-izin" data-id="{{ $item->id }}" title="Tolak">
                    <span class="fas fa-ban"></span>
                </button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if ($role == 'atasan')
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
                <span class="badge badge-{{ $item->status['color'] }}">{{
                    $item->status['text'] }}</span>
            </td>
            <td class="action">
                @if (in_array('show-track', $action_shows))
                <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                    data-toggle="modal" data-target="#tracking">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
                @endif
                @if (in_array('print', $action_shows))
                <button class="btn btn-success print-izin" data-id="{{ $item->id }}" title="Cetak">
                    <span class="fas fa-print"></span>
                </button>
                @endif
                @if (in_array('accept', $action_shows))
                <button class="btn btn-success acc-izin" data-id="{{ $item->id }}" data-status="accepted_pimpinan"
                    title="Setujui">
                    <span class="fas fa-check"></span>
                </button>
                @endif
                @if (in_array('reject', $action_shows))
                <button class="btn btn-danger reject-izin" data-id="{{ $item->id }}" title="Tolak">
                    <span class="fas fa-ban"></span>
                </button>
                @endif
                @if (in_array('delete', $action_shows))
                <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" title="Hapus pengajuan">
                    <span class="fas fa-times"></span>
                </button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif