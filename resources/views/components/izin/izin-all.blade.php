@php
$role = session('user')->level;
$showExtraColsFor = ['kabid', 'admin'];
@endphp

<table class="datatable table table-striped table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            @if(in_array($role, $showExtraColsFor))
            <th>Nama</th>
            <th>NIP</th>
            @endif
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
        @if (count($data) == 0)
            <tr>
                <td colspan="11" class="text-center">Tidak ada data</td>
            </tr>
        @endif
        @foreach ($data as $item)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            @if(in_array($role, $showExtraColsFor))
            <td>{{ $item->user->nama }}</td>
            <td>{{ $item->user->nip }}</td>
            @endif
            <td>{{ $item->jenis }}</td>
            <td>{{ $item->formatted_tgl_mulai }}</td>
            <td>{{ $item->formatted_tgl_selesai }}</td>
            <td>{{ $item->formatted_durasi }}</td>
            <td>{{ $item->keterangan }}</td>
            <td><a href="{{ Storage::url('public/uploads/'.$item->bukti) }}" target="_blank"
                    class="text-primary text-decoration-none">Download</a></td>
            <td><span class="badge badge-{{ $item->status['color'] }}">{{
                    ucfirst($item->status['text']) }}</span></td>
            <td class="action">
                @if (in_array('track', $item->actions))
                <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                    data-toggle="modal" data-target="#tracking">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
                @endif
                @if (in_array('edit', $item->actions))
                <button class="btn btn-info btn-edit" data-toggle="modal" data-target="#modal-form"
                    data-item='{{ json_encode($item) }}' title="Ubah pengajuan">
                    <span class="fas fa-pencil" style="pointer-events: none"></span>
                </button>
                @endif
                @if (in_array('delete', $item->actions))
                <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" title="Batalkan pengajuan">
                    <span class="fas fa-times"></span>
                </button>
                @endif
                @if (in_array('accept', $item->actions))
                <button class="btn btn-success acc-izin" data-id="{{ $item->id }}" data-status="accepted_kabid"
                    title="Setujui">
                    <span class="fas fa-check"></span>
                </button>
                @endif
                @if (in_array('reject', $item->actions))
                <button class="btn btn-danger reject-izin" data-id="{{ $item->id }}" title="Tolak">
                    <span class="fas fa-ban"></span>
                </button>
                @endif
                @if (in_array('print', $item->actions))
                <button class="btn btn-success print-izin" data-id="{{ $item->id }}" title="Cetak">
                    <span class="fas fa-print"></span>
                </button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>