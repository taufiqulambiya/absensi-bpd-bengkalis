<div class="col-12">
    <style>
        .nav-link {
            cursor: pointer;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs nav-stacked mb-3">
                {{-- <li class="nav-item">
                    <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all"
                        aria-selected="true">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="terlewat-tab" data-toggle="tab" href="#terlewat" role="tab"
                        aria-controls="terlewat" aria-selected="true">Terlewat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="selesai-tab" data-toggle="tab" href="#selesai" role="tab"
                        aria-controls="selesai" aria-selected="true">Selesai</a>
                </li> --}}
                {{-- update: use link with search named = view --}}
                <li class="nav-item">
                    <a class="nav-link {{ $active_tab == 'pending' ? 'active' : '' }}" wire:click="changeTab('pending')"
                        role="tab" aria-controls="all" aria-selected="true">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active_tab == 'missed' ? 'active' : '' }}" wire:click="changeTab('missed')"
                        role="tab" aria-controls="terlewat" aria-selected="true">Terlewat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active_tab == 'done' ? 'active' : '' }}" wire:click="changeTab('done')"
                        role="tab" aria-controls="selesai" aria-selected="true">Selesai</a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content py-3">
                <div class="tab-pane fade show active table-responsive" id="all">
                    {{--
                    <x-cuti.cuti-all :data="$dataCuti" /> --}}
                    {{-- @livewire('cuti.table', ['data' => $dataCuti]) --}}
                    {{--
                    <livewire:cuti.table :data="$dataCuti" /> --}}
                    <div class="table-responsive">
                        {{-- <div class="row mb-3">
                            <div class="col-md-6">
                                <div>Filter Tanggal</div>
                                <div class="d-flex">
                                    <input type="date" class="form-control mr-3"
                                        wire:model.debounce.500ms="filter.date_start">
                                    <input type="date" class="form-control" wire:model.debounce.500ms="filter.date_end">

                                </div>
                            </div>
                        </div> --}}
                        <table class="datatable table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if ($level != 'pegawai')
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    @endif
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
                                @if ($dataCuti->count() == 0)
                                <tr>
                                    <td colspan="{{ $level == 'pegawai' ? 8 : 11 }}"
                                     class=" text-center">
                                        <div wire:loading>
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <div wire:loading.remove>
                                            Tidak ada data.
                                        </div>
                                    </td>
                                </tr>
                                @endif

                                @foreach ($dataCuti as $item)
                                <tr wire:key="cuti-{{ $item->id }}">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    @if ($level != 'pegawai')
                                    <td>{{ $item->user->nama }}</td>
                                    <td>{{ $item->user->nip }}</td>
                                    @endif
                                    <td>
                                        <div class="d-flex flex-wrap flex-column" style="gap: 4px">
                                            @foreach ($item->tanggal_arr as $tgl)
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
                                        @if (in_array('track', $item->actions))
                                        <button class="btn btn-warning btn-track" title="Lacak" data-toggle="modal"
                                            data-target="#tracking" wire:click="track({{ $item->id }})">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                        @endif
                                        @if (in_array('edit', $item->actions))
                                        <button class="btn btn-info btn-edit" data-toggle="modal"
                                            data-target="#modal-form" title="Ubah pengajuan"
                                            wire:click="edit({{ $item->id }})">
                                            <span class="fas fa-pencil"></span>
                                        </button>
                                        @endif
                                        @if (in_array('delete', $item->actions))
                                        <button class="btn btn-danger" title="Batalkan pengajuan"
                                            wire:click="$emit('delete', {{ $item->id }})">
                                            <span class="fas fa-times"></span>
                                        </button>
                                        @endif
                                        @if (in_array('print', $item->actions))
                                        <a href="?print=id&&id={{ $item->id }}" target="_blank"
                                            class="btn btn-success btn-print" title="Cetak pengajuan">
                                            <span class="fas fa-print"></span>
                                        </a>
                                        @endif
                                        @if (in_array('accept', $item->actions))
                                        <button class="btn btn-success btn-acc" title="Setujui"
                                            wire:click="$emit('acc', {{ $item->id }})">
                                            <span class="fas fa-check"></span>
                                        </button>
                                        @endif
                                        @if (in_array('reject', $item->actions))
                                        <button class="btn btn-danger btn-rej" title="Tolak"
                                            wire:click="$emit('rej', {{ $item->id }})">
                                            <span class="fas fa-ban"></span>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                {{-- <div class="tab-pane fade show active table-responsive" id="all">
                    <x-cuti.list-pending />
                </div>
                <div class="tab-pane fade table-responsive" id="terlewat">
                    <x-cuti.list-missed />
                </div>
                <div class="tab-pane fade table-responsive" id="selesai">
                    <x-cuti.list-done />
                </div> --}}
            </div>
        </div>
    </div>

    <livewire:cuti.modal-tracking :data="$trackData" />
</div>