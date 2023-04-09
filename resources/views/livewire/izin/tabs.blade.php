<div class="card">
    <div class="card-header">
        @if ($level == 'kabid' AND $showInfo)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Info!</strong> Data yang tampil sesuai pegawai dengan bidang yang sama dengan bidang Kabid.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" wire:click="closeInfo">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link @if ($activeTab == 'pending' OR !$activeTab) active @endif" id="all-tab"
                    href="javascript:;" role="tab" aria-controls="all" wire:click="changeTab('pending')">
                    Pengajuan Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if ($activeTab == 'missed') active @endif" id="terlewat-tab" href="javascript:;"
                    role="tab" aria-controls="terlewat" wire:click="changeTab('missed')">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    Pengajuan Terlewat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if ($activeTab == 'done') active @endif" id="selesai-tab" href="javascript:;"
                    role="tab" aria-controls="selesai" wire:click="changeTab('done')"><i class="fa fa-check"
                        aria-hidden="true"></i> Pengajuan
                    Selesai</a>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content py-3">
            <div class="tab-pane fade show active table-responsive" id="all">
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
                            <th>Durasi (Hari Kerja)</th>
                            <th>Keterangan</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @if (count($data) == 0)
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data</td>
                        </tr>
                        @endif --}}
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
                            <td><a href="{{ Storage::url('public/izin/'.$item->bukti) }}" target="_blank"
                                    class="text-primary text-decoration-none">Download</a></td>
                            <td><span class="badge badge-{{ $item->status_color }}">{{
                                    ucfirst($item->status_text) }}</span></td>
                            <td class="action">
                                @if (in_array('track', $item->actions))
                                <button class="btn btn-warning btn-track" title="Lacak"
                                    wire:click="showTracking({{$item->id}})" data-toggle="modal"
                                    data-target="#tracking">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                                @endif
                                @if (in_array('edit', $item->actions))
                                <button class="btn btn-info btn-edit" data-toggle="modal" data-target="#modal-form"
                                    wire:click="updateModalEdit({{$item->id}})" title="Ubah pengajuan">
                                    <span class="fas fa-pencil" style="pointer-events: none"></span>
                                </button>
                                @endif
                                @if (in_array('delete', $item->actions))
                                <button class="btn btn-danger" wire:click="$emit('izin:delete', {{ $item->id }})"
                                    title="Batalkan pengajuan">
                                    <span class="fas fa-times"></span>
                                </button>
                                @endif
                                @if (in_array('accept', $item->actions))
                                <button class="btn btn-success acc-izin" wire:click="$emit('izin:acc', {{$item->id}})" title="Setujui">
                                    <span class="fas fa-check"></span>
                                </button>
                                @endif
                                @if (in_array('reject', $item->actions))
                                <button class="btn btn-danger reject-izin" wire:click="$emit('izin:reject', {{$item->id}})" title="Tolak">
                                    <span class="fas fa-ban"></span>
                                </button>
                                @endif
                                @if (in_array('print', $item->actions))
                                <a href="?print=id&id={{$item->id}}" target="_blank" class="btn btn-success"
                                    title="Cetak">
                                    <span class="fas fa-print"></span>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <livewire:cuti.modal-tracking :data="$trackData" />
</div>