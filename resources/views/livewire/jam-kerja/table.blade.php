<table class="datatable table table-striped table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Hari</th>
            <th>Jam Mulai</th>
            <th>Jam Akhir</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Tanggal Dibuat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>
            <th scope="row">{{$loop->iteration}}</th>
            <td>{{ strtoupper($item->days) }}</td>
            <td>{{ explode(':', $item->mulai)[0].':'.explode(':',
                $item->mulai)[1] }}</td>
            <td>{{ explode(':', $item->selesai)[0].':'.explode(':',
                $item->selesai)[1] }}</td>
            <td>{{$item->keterangan}}</td>
            <td>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input switch-status" id="customSwitch{{ $item->id }}"
                        {{ $item->status == 'aktif' ? 'checked' : '' }} wire:click="toggleStatus({{ $item->id }})">
                    <label class="custom-control-label" for="customSwitch{{ $item->id }}"></label>
                </div>

            </td>
            <td>
                {{$item->created_at}}
            </td>
            <td>
                <button class="btn btn-info" data-toggle="modal" data-target="#modal-form" title="Ubah data" wire:click="edit({{$item->id}})">
                    <span class="fas fa-pencil"></span>
                </button>

                <button type="submit" class="btn btn-danger btn-delete" title="Hapus data" wire:click="$emit('confirmDelete', {{$item->id}})">
                    <span class="fas fa-times"></span>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>