<table id="datatable" class="table table-striped table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>NIP</th>
            <th>Golongan</th>
            <th>Jabatan</th>
            <th>Bidang</th>
            <th>Tanggal Lahir</th>
            <th>No. HP</th>
            <th>Alamat</th>
            <th>Level</th>
            @if ($level == 'admin')
            <th>Aksi</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->nip }}</td>
            <td>{{ $item->golongan }}</td>
            <td>{{ $item->jabatan ?? '-' }}</td>
            <td>{{ $item->bidangs->nama ?? '-' }}</td>
            <td>{{ date_format(date_create($item->tgl_lahir), 'd/m/Y') }}</td>
            <td>{{ $item->no_telp }}</td>
            <td>{{ $item->alamat }}</td>
            <td>{{ strtoupper($item->level == 'atasan' ? 'pimpinan' : $item->level) }}</td>
            @if ($level == 'admin')
            <td>
                <button class="btn btn-info btn-edit" data-toggle="modal" data-target="#modal-form"
                    wire:click="$emit('fillEdit', {{ $item }})" title="Edit Pengguna">
                    <span class="fas fa-pencil"></span>
                </button>
                <button class="btn btn-warning" title="Reset Password" wire:click="$emit('confirmResetPassword', {{ $item->id }})"><i
                        class="fas fa-key"></i></button>
                <button class="btn btn-danger btn-delete" title="Hapus Pengguna" wire:click="$emit('confirmDelete', {{$item->id}})">
                    <span class="fas fa-times"></span>
                </button>
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>