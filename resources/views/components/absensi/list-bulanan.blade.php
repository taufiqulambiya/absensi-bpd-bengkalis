@php
$months = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
];
@endphp

<style>
    .clickable {
        cursor: pointer;
    }
</style>

{{-- data container --}}
<div class="data-container" data-data='<?= $data ?? [] ?>'></div>
<div class="data-container" data-days='<?= json_encode($days) ?? [] ?>'></div>
{{-- end data container --}}

<div class="card-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group col-6 p-0">
                <label for="filter">Filter Bulan</label>
                <div class="d-flex">
                    <select id="filter-bulan" name="filter-bulan" class="form-control">
                        @foreach ($months as $key => $month)
                        <option value="{{ $key+1 }}" {{ $key+1==($_GET['bulan'] ?? date('m') ) ? 'selected' : '' }}>
                            {{ $month }}
                        </option>
                        @endforeach
                    </select>
                    <select name="year" id="year" class="form-control" onchange="
                        window.location.href = '?view=bulanan&bulan='+document.querySelector('select[name=filter-bulan]').options[document.querySelector('select[name=filter-bulan]').options.selectedIndex].value+'&year='+event.target.options[event.target.options.selectedIndex].value
                    ">
                        @for ($i = date('Y', strtotime('-3year')); $i <= date('Y'); $i++) <option value="{{ $i }}" {{
                            $i==($_GET['year'] ?? date('Y') ) ? 'selected' : '' }}>
                            {{ $i }}
                            </option>
                            @endfor
                    </select>
                    @if (isset($_GET['bulan']) || isset($_GET['year']))
                    <a href="#" onclick="window.location.href = window.location.href.split('?')[0] + '?view=bulanan'"
                        class="btn btn-secondary">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                    @endif
                </div>
            </div>
            <h4 class="my-3">Tanggal Hari Ini : {{
                \Carbon\Carbon::now()->format('d/m/Y') }}
            </h4>
        </div>
        <div class="col-12">
            <div class="border d-inline-block p-1">
                <div class="bg-secondary d-inline-block" style="width: 24px; height: 24px; vertical-align: middle">
                </div>
                <p class="d-inline">Belum Absen</p>
            </div>
            <div class="border d-inline-block p-1">
                <div class="bg-primary d-inline-block" style="width: 24px; height: 24px; vertical-align: middle"></div>
                <p class="d-inline">Sudah Absen Masuk</p>
            </div>
            <div class="border d-inline-block p-1">
                <div class="bg-success d-inline-block" style="width: 24px; height: 24px; vertical-align: middle"></div>
                <p class="d-inline">Sudah Absen Keluar</p>
            </div>
        </div>
        <div class="col-12 p-3">
            <button id="print-all-bulanan" class="btn btn-success">
                <i class="fa fa-print" aria-hidden="true"></i> Cetak
            </button>
            <hr />
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Pegawai / Tanggal</th>
                            @for ($i = 1; $i <= count($days); $i++) <th>{{
                                $i }}</th>
                                @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            @foreach ($days as $day)
                            @if ($item->absensi->where('tanggal', $day->format('Y-m-d'))->count() > 0)
                            @php
                            $absensi = $item->absensi->where('tanggal', $day->format('Y-m-d'))->first();
                            @endphp
                            @if ($absensi->waktu_keluar != null &&
                            !\Carbon\Carbon::parse($absensi->waktu_keluar)->isMidnight())
                            <td class="bg-success text-white clickable" data-id="{{ $absensi->id }}">
                                {{ \Carbon\Carbon::parse($absensi->waktu_keluar)->format('H:i \W\I\B') }}
                            </td>
                            @else
                            <td class="bg-primary text-white clickable" data-id="{{ $absensi->id }}">
                                {{ \Carbon\Carbon::parse($absensi->waktu_masuk)->format('H:i \W\I\B') }}
                            </td>
                            @endif
                            @elseif (!$day->isAfter(date('Y-m-d')))
                            <td class="bg-secondary"></td>
                            @else
                            <td></td>
                            @endif
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>