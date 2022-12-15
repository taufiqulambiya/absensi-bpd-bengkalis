@if ($user_role === 'pegawai')
<div>
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
            @foreach ($data_cuti as $item)
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
                    <span class="badge badge-{{ $item->status_class }}">{{
                        ucfirst($item->status_text) }}</span>
                </td>
                <td>
                    <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                        data-toggle="modal" data-target="#tracking">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                    @if ($item->status == 'pending')
                    @if ($item->terlewat)
                    <button class="btn btn-secondary" data-toggle="modal"
                        onclick="showErrorAlert('Pengajuan sudah terlewat, silahkan batalkan dan ajukan ulang.')"
                        title="Ubah pengajuan">
                        <span class="fas fa-pencil"></span>
                    </button>
                    <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" data-toggle="modal"
                        data-target="#modal-delete" title="Batalkan pengajuan">
                        <span class="fas fa-times"></span>
                    </button>
                    @else
                    <button class="btn btn-info btn-edit" data-toggle="modal" data-target="#modal-form"
                        data-item='{{ json_encode($item) }}' title="Ubah pengajuan">
                        <span class="fas fa-pencil"></span>
                    </button>
                    <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" data-toggle="modal"
                        data-target="#modal-delete" title="Batalkan pengajuan">
                        <span class="fas fa-times"></span>
                    </button>
                    @endif
                    @elseif($item->status == 'accepted_admin' OR
                    $item->status ==
                    'rejected')
                    <button class="btn btn-success btn-print-detail" data-item="{{ $item }}">
                        <i class="fas fa-print"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif


@if ($user_role === 'kabid')
<table class="datatable table table-striped table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>NIP</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Keterangan</th>
            <th>Bukti</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_cuti as $item)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{$item->user->nama}}</td>
            <td>{{$item->user->nip}}</td>
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
                <span class="badge badge-{{ $item->status_class }}">{{
                    ucfirst($item->status_text) }}</span>
            </td>
            <td>
                @if ($item->status == 'pending')
                @if ($item->terlewat)
                <button class="btn btn-secondary" data-toggle="modal"
                    onclick="showErrorAlert('Pengajuan sudah terlewat, silahkan batalkan dan ajukan ulang.')"
                    title="Ubah pengajuan">
                    <span class="fas fa-pencil"></span>
                </button>
                <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" data-toggle="modal"
                    data-target="#modal-delete" title="Batalkan pengajuan">
                    <span class="fas fa-times"></span>
                </button>
                @else
                <form action="#" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-success btn-acc" data-id='{{ $item->id }}' type="button" title="Setujui">
                        <span class="fas fa-check"></span>
                    </button>
                </form>
                <button class="btn btn-danger btn-reject" data-id='{{ $item->id }}' title="Tolak">
                    <span class="fas fa-ban"></span>
                </button>
                @endif
                @elseif($item->status == 'accepted_admin' OR
                $item->status ==
                'rejected')
                <button class="btn btn-success btn-print-detail" data-item="{{ $item }}">
                    <i class="fas fa-print"></i>
                </button>
                @else
                <span class="text-info">Pengajuan sedang
                    diproses.</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif