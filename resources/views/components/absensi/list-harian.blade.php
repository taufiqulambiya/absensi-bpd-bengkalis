{{-- container for js access --}}
{{-- <div class="data-container" data-data="{{ json_encode($absensi_by_user) }}"></div> --}}
{{-- end container for js access --}}

@php
     $filter_status = [
        ['all', 'Semua'],
        ['hadir', 'Hadir'],
        ['izin', 'Izin'],
        ['cuti', 'Cuti'],
        ['dinas_luar', 'Dinas Luar']
    ];
@endphp

<div class="card-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group col-3 p-0">
                <label for="filter">Filter Status</label>
                <select name="filter-status" id="filter-status" class="form-control"
                    onchange="window.location.href = `?view=harian&status=${event.target.options[event.target.options.selectedIndex].value}&tgl={{ request()->tgl }}`">
                    @foreach ($filter_status as $item)
                    <option value="{{ $item[0] }}" @if (request()->status === $item[0]) selected @endif>
                        {{ $item[1] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group col-3 p-0">
                <label for="filter">Filter Tanggal</label>
                <input type="date" class="form-control" id="filter-tgl"
                    onchange="window.location.href = `?view=harian&status={{ request()->status }}&tgl=${event.target.value}`"
                    value="{{ request()->tgl ?? date('Y-m-d') }}">
            </div>

            @if (request()->status OR request()->tgl)
            <h4 class="my-3">Difilter berdasarkan status: <script>
                    document.write($('#filter-status option:selected').text())
                </script>, tanggal: <script>
                    document.write(moment(new Date($('#filter-tgl').val())).format('DD/MM/YYYY'))
                </script>
            </h4>
            <a href="?" class="btn btn-primary">Clear filter</a>
            @else
            <h4 class="my-3">Tanggal Hari Ini : {{ date('d - F - Y') }}</h4>
            @endif
        </div>
        <div class="col-12">
            <hr />
            <button class="btn btn-success mb-3" id="print-all-harian">
                <i class="fa fa-print mr-2" aria-hidden="true"></i>Cetak
            </button>
        </div>
        <div class="col-12">
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
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->formatted_tanggal ?? '-' }}</td>
                            <td>{{ $item->absensi['formatted_waktu_masuk'] ?? '-' }}</td>
                            <td>{{ $item->absensi['formatted_waktu_keluar'] ?? '-' }}</td>
                            <td>{{ $item->absensi['formatted_shift'] ?? '-' }}</td>
                            <td>{{ $item->absensi['total_jam'] ?? '-' }}</td>
                            <td>
                                @if ($item->absensi['dok_masuk'] ?? false)
                                <a href="{{ Storage::url('public/uploads/'. $item->absensi['dok_masuk']) }}"
                                    target="_blank">
                                    Dok. Masuk
                                </a>
                                @else
                                -
                                @endif
                                <br />
                                @if ($item->absensi['dok_keluar'] ?? false)
                                <a href="{{ Storage::url('public/uploads/'. $item->absensi['dok_keluar']) }}"
                                    target="_blank">
                                    Dok. Keluar
                                </a>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if ($item->absensi['status'] ?? false)
                                <span class="badge badge-{{ $item->absensi['status_color']}}">{{ ucwords($item->absensi['status']) }}</span>
                                @else
                                <span class="badge badge-secondary">
                                    Belum Absen
                                </span>
                                @endif
                            </td>
                            <td>
                                @if ($item->absensi['status'] ?? false)
                                <a href="{{ route('absensi.show', $item->absensi['id']) }}" class="btn btn-info">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <button class="btn btn-success btn-print-item" data-id="{{ $item->id }}"><i
                                        class="fa fa-print" aria-hidden="true"></i></button>
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
