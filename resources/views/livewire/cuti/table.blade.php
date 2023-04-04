<div class="table-responsive">
    {{-- <div class="row mb-3">
        <div class="col-md-6">
            <div>Filter Tanggal</div>
            <div class="d-flex">
                <input type="date" class="form-control mr-3">
                >
                <input type="date" class="form-control">

            </div>
        </div>
    </div> --}}
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
            <tr wire:key="cuti-{{ $item->id }}">
                <th scope="row">{{ $loop->iteration }}</th>
                <td>
                    <div class="d-flex flex-wrap flex-column" style="gap: 4px">
                        @foreach (($item->tanggal_arr ?? []) as $tgl)
                        <span class="chip in-table bg-secondary text-white">{{
                            $tgl }}</span>
                        @endforeach
                    </div>
                </td>
                <td>{{ $item->total }} Hari</td>
                <td>{{ $item->jenis }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>
                    @if(Storage::disk('public')->has('cuti/' .
                    $item->bukti))
                    <a href="{{ Storage::url('public/cuti/'.$item->bukti) }}" target="_blank"
                        class="text-primary text-decoration-none">Download</a>
                    @else
                    <span class="text-danger">File tidak ada.</span>
                    @endif
                </td>
                <td>
                    <span class="badge badge-{{ $item->status_color }}">{{
                        ucfirst($item->status_text) }}</span>
                </td>
                <td>
                    @if (in_array('track', $item->actions ?? []))
                    <button class="btn btn-warning btn-track" title="Lacak" data-tracking="{{ $item->tracking }}"
                        data-toggle="modal" data-target="#tracking">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                    @endif
                    @if (in_array('edit', $item->actions ?? []))
                    <button class="btn btn-info btn-edit" data-toggle="modal" data-target="#modal-edit"
                        data-id='{{ $item->id }}' title="Ubah pengajuan" wire:click.defer="edit({{ $item->id }})">
                        <span class="fas fa-pencil"></span>
                    </button>
                    @endif
                    @if (in_array('delete', $item->actions ?? []))
                    <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}" data-toggle="modal"
                        data-target="#modal-delete" title="Batalkan pengajuan">
                        <span class="fas fa-times"></span>
                    </button>
                    @endif
                    @if (in_array('print', $item->actions ?? []))
                    <button class="btn btn-success btn-print" data-id="{{ $item->id }}" title="Cetak pengajuan">
                        <span class="fas fa-print"></span>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>