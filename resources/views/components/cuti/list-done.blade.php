@php
$role = session('user')->level;
@endphp

@if ($role == 'pegawai')
<table class="datatable table table-striped table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Jenis Cuti</th>
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
                <div class="d-flex flex-wrap flex-column" style="gap: 4px">
                    @foreach ($item->tanggal as $tgl)
                    <span class="chip in-table bg-secondary text-white">{{
                        $tgl }}</span>
                    @endforeach
                </div>
            </td>
            <td>{{ $item->total }} Hari</td>
            <td>{{ $item->jenis }}</td>
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
                <button class="btn btn-success btn-print-detail" data-item="{{ $item }}">
                    <i class="fas fa-print"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if ($role == 'kabid')
<table class="datatable table table-striped table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>NIP</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Jenis Cuti</th>
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
            <td>
                <div class="d-flex flex-wrap flex-column" style="gap: 4px">
                    @foreach ($item->tanggal as $tgl)
                    <span class="chip in-table bg-secondary text-white">{{
                        $tgl }}</span>
                    @endforeach
                </div>
            </td>
            <td>{{ $item->total }} Hari</td>
            <td>{{ $item->jenis }}</td>
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
                <button class="btn btn-success btn-print-detail" data-item="{{ $item }}">
                    <i class="fas fa-print"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if ($role == 'admin')
<table class="datatable table table-striped table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>NIP</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Jenis Cuti</th>
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
            <td>
                <div class="d-flex flex-wrap flex-column" style="gap: 4px">
                    @foreach ($item->tanggal as $tgl)
                    <span class="chip in-table bg-secondary text-white">{{
                        $tgl }}</span>
                    @endforeach
                </div>
            </td>
            <td>{{ $item->total }} Hari</td>
            <td>{{ $item->jenis }}</td>
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
                <button class="btn btn-success btn-print-detail" data-item="{{ $item }}">
                    <i class="fas fa-print"></i>
                </button>
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
            <th>Tanggal</th>
            <th>Total</th>
            <th>Jenis Cuti</th>
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
            <td>
                <div class="d-flex flex-wrap flex-column" style="gap: 4px">
                    @foreach ($item->tanggal as $tgl)
                    <span class="chip in-table bg-secondary text-white">{{
                        $tgl }}</span>
                    @endforeach
                </div>
            </td>
            <td>{{ $item->total }} Hari</td>
            <td>{{ $item->jenis }}</td>
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
                <button class="btn btn-success btn-print-detail" data-item="{{ $item }}">
                    <i class="fas fa-print"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif