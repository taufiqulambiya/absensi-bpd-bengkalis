@php
$filter_status = [
['all', 'Semua'],
['hadir', 'Hadir'],
['izin', 'Izin'],
['cuti', 'Cuti'],
// ['dinas_luar', 'Dinas Luar']
];
@endphp

<div>
    <div class="form-group mb-3 p-0" style="width: 200px">
        <label for="filter">Filter Status</label>
        <select name="filter-status" id="filter-status" class="form-control" wire:model="status"
            wire:change="setIsFiltered(true)">
            @foreach ($filter_status as $item)
            <option value="{{ $item[0] }}" @if ($status===$item[0]) selected @endif>
                {{ $item[1] }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3 p-0" style="width: 200px">
        <label for="filter">Filter Tanggal</label>
        <input type="date" class="form-control" id="filter-tgl" wire:model="tgl" wire:change="setIsFiltered(true)">
    </div>

    @if ($isFiltered)
    <div class="alert alert-info" role="alert" style="width: 400px">
        <p class="mb-0">Difilter berdasarkan status: <strong>{{ strtoupper($status) }}</strong>, tanggal: <strong>{{
                date('d/m/Y', strtotime($tgl))}}</strong>
            <a href="javascript:void(0)" wire:click="clearFilter" class="float-right text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x"
                    viewBox="0 0 16 16">
                    <path
                        d="M11.854 4.646a.5.5 0 0 1 0 .708L9.207 8l2.647 2.646a.5.5 0 0 1-.708.708L8.5 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.793 8 5.146 5.354a.5.5 0 0 1 .708-.708L8.5 7.293l2.646-2.647z" />
                </svg>
            </a>
        </p>
    </div>
    @endif

    <div class="mb-3">
        <hr>
        <a href="?print=all&mode=harian&tgl={{$tgl}}&status={{$status}}" target="_blank" class="btn btn-success mb-3">
            <i class="fa fa-print mr-2" aria-hidden="true"></i>Cetak
        </a>
    </div>

    <div class="table-responsive">
        <table class="datatable table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                    <th>Jam Absen</th>
                    <th>Total Jam</th>
                    <th>Dokumentasi</th>
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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nip }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->formatted_tanggal ?? '-' }}</td>
                    <td>{{ $item->absensiRaw->waktu_masuk ?? '-' }}</td>
                    <td>{{ ($item->absensiRaw->show_waktu_keluar ?? false) ? ($item->absensiRaw->waktu_keluar ?? '-') :
                        '-' }}</td>
                    <td>{{ $item->absensiRaw->shift ?? '-' }}</td>
                    <td>{{ ($item->absensiRaw->show_waktu_keluar ?? false) ? ($item->absensiRaw->total_jam ?? '-') : '-'
                        }}</td>
                    <td>
                        @if ($item->absensiRaw->dok_masuk ?? false)
                        <a href="{{ Storage::url('public/uploads/'. $item->absensiRaw->dok_masuk) }}" target="_blank">
                            Dok. Masuk
                        </a>
                        @else
                        -
                        @endif
                        <br />
                        @if ($item->absensiRaw->dok_keluar ?? false)
                        <a href="{{ Storage::url('public/uploads/'. $item->absensiRaw->dok_keluar) }}" target="_blank">
                            Dok. Keluar
                        </a>
                        @else
                        -
                        @endif
                    </td>
                    <td>
                        {!! $item->statusHtml !!}
                    </td>
                    <td>
                        @if (in_array('info', $item->actions))
                        <button class="btn btn-info btn-info-item" wire:click="detail({{$item->absensiRaw->id}})">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        @endif
                        @if (in_array('print', $item->actions))
                        <button class="btn btn-success" wire:click="print({{$item->absensiRaw->id}})">
                            <i class="fa fa-print" aria-hidden="true"></i>
                        </button>
                        @endif
                        {{-- @if ($item->absensi['status'] ?? false)
                        <a href="{{ route('absensi.show', $item->absensi['id']) }}" class="btn btn-info">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        <button class="btn btn-success btn-print-item" data-id="{{ $item->id }}"><i class="fa fa-print"
                                aria-hidden="true"></i></button>
                        @endif --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>