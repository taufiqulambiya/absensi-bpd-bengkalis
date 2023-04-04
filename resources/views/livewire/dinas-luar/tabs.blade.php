<div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link @if ($tab == 'active') active @endif" href="#" role="tab" aria-controls="all"
                aria-selected="true" wire:click="changeTab('active')">Aktif</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if ($tab == 'done') active @endif" href="#" role="tab" aria-controls="selesai"
                aria-selected="false" wire:click="changeTab('done')"><i class="fa fa-check" aria-hidden="true"></i>
                Selesai</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-12">
            <div class="tab-content py-3" id="myTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="table-responsive">
                        <table class="datatable table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if ($level != 'pegawai')
                                    <th>Pegawai</th>
                                    <th>NIP</th>
                                    @endif
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Durasi</th>
                                    <th>Maksud Perjalanan</th>
                                    <th>Lokasi</th>
                                    <th>Keterangan</th>
                                    <th>Surat Jalan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    @if ($level != 'pegawai')
                                    <td>{{ $item->user->nama }}</td>
                                    <td>{{ $item->user->nip }}</td>
                                    @endif
                                    <td>{{ $item->mulai }}</td>
                                    <td>{{ $item->selesai }}</td>
                                    <td>{{ $item->durasi }}</td>
                                    <td>{{ $item->maksud }}</td>
                                    <td>{{ $item->lokasi }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>
                                        @if (Storage::disk('public')->has('dinas-luar/'.$item->file))
                                        <a href="{{ Storage::url('public/dinas-luar/'.$item->file) }}"
                                            target="_blank">Download</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (in_array('edit', $item->action))
                                        <button class="btn btn-info btn-edit" title="Perbarui" data-toggle="modal"
                                            data-target="#add" wire:click="edit({{ $item->id }})">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                        @if (in_array('delete', $item->action))
                                        <button class="btn btn-danger btn-cancel" title="Batalkan"
                                            data-id="{{ $item->id }}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                        @if (in_array('print', $item->action))
                                        <button class="btn btn-success" title="Cetak" wire:click="print({{ $item->id }})">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>