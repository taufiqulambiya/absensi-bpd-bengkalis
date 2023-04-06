<table class="datatable table table-striped table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            {{-- <th>Kepala Bidang</th> --}}
            <th>Jumlah Staff</th>
            <th>Tanggal Dibuat</th>
            <th>Terakhir Diperbarui</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>
            <th scope="row">{{$loop->iteration}}</th>
            <td>{{ $item->nama }}</td>
            {{-- <td>{{ $item->kabids->nama ?? '-' }}</td> --}}
            <td>{{ $item->users->count() }}</td>
            <td>{{ $item->created_at }}</td>
            <td>{{ $item->updated_at }}</td>
            <td>
                <button type="button" class="btn btn-info btn-edit" data-toggle="modal" data-target="#modal-form" title="Perbarui" wire:click="edit({{ $item->id }})"><i
                        class="fa fa-pencil" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-danger btn-delete" title="Hapus" wire:click="$emit('confimDelete', {{ $item->id }})"><i
                        class="fa fa-times" aria-hidden="true"></i></button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>