@extends('layouts.app')
@section('title', 'Daftar Absensi')

@section('content')

<div class="container body">
    <div class="main_container">
        <!-- sidebar -->
        @include('layouts.sidebar')
        @include('layouts.topbar')


        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Halaman @yield('title')</h3>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12  ">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Konten @yield('title')</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="card">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs nav-stacked mb-3">
                                            <li class="nav-item">
                                                <a href="?view=harian" class="nav-link @if (empty($_GET['view']) || $_GET['view'] == 'harian')
                                                active
                                                @endif">Harian</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="?view=bulanan" class="nav-link @if (!empty($_GET['view']) AND $_GET['view'] == 'bulanan')
                                                    active
                                                @endif">Bulanan</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group col-3 p-0">
                                                    <label for="filter">Filter Bulan</label>
                                                    <select name="filter-bulan" id="filter-bulan" class="form-control"
                                                        {{--
                                                        onchange="window.location.href = '?view=bulanan&bulan='+event.target.options[event.target.options.selectedIndex].value"
                                                        --}}>
                                                        {{-- @foreach ($bulan_indo as $key => $item)
                                                        @if (!empty($_GET['bulan']))
                                                        <option value="{{ $key + 1 }}"
                                                            @if ($key + 1 == $_GET['bulan'] OR $key + 1 == date('m'))
                                                            selected
                                                            @endif
                                                        >{{ $item }}</option>
                                                        @else
                                                        <option value="{{ $key + 1 }}"
                                                            @if ($key + 1 == date('m'))
                                                            selected
                                                            @endif
                                                        >{{ $item }}</option>
                                                        @endif
                                                        @endforeach --}}

                                                        @foreach ($bulan_indo as $key => $item)
                                                        <option value="{{ $key + 1 }}"
                                                            @if ($key + 1 == date('m'))
                                                            selected
                                                            @endif
                                                        >{{ $item }}</option>
                                                    </select>
                                                </div>
                                                <h4 class="my-3">Tanggal Hari Ini : {{ date('d - F - Y') }}</h4>
                                            </div>
                                            <div class="col-12">
                                                <div class="border d-inline-block p-1">
                                                    <div class="bg-secondary d-inline-block"
                                                        style="width: 24px; height: 24px; vertical-align: middle"></div>
                                                    <p class="d-inline">Belum Absen</p>
                                                </div>
                                                <div class="border d-inline-block p-1">
                                                    <div class="bg-primary d-inline-block"
                                                        style="width: 24px; height: 24px; vertical-align: middle"></div>
                                                    <p class="d-inline">Sudah Absen Masuk</p>
                                                </div>
                                                <div class="border d-inline-block p-1">
                                                    <div class="bg-success d-inline-block"
                                                        style="width: 24px; height: 24px; vertical-align: middle"></div>
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
                                                                @for ($i = 1; $i <= $days_in_current_month; $i++) <th>{{
                                                                    $i }}</th>
                                                                    @endfor
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($map_table as $item)
                                                            <tr>
                                                                <td>{{ $item['nama'] }}</td>
                                                                @foreach ($item['absensi'] as $abs)
                                                                @if ($abs['uncolorize'])
                                                                <td></td>
                                                                @elseif (count($abs['record']) > 0)
                                                                <td class="
                                                                    @foreach ($abs['record'] as $item_abs)
                                                                    @if ($item_abs['status'] == 'masuk')
                                                                        bg-primary
                                                                    @elseif($item_abs['status'] == 'keluar')
                                                                        bg-success
                                                                    @else
                                                                        bg-secondary
                                                                    @endif
                                                                    @endforeach" style="cursor: pointer"
                                                                    onclick="window.location.href = 'absensi/' + `{{ $item_abs['id'] }}`">
                                                                    @if ($item_abs['status'] == 'masuk')
                                                                    <span class="text-white">{{
                                                                        date_format(date_create($item_abs['waktu_masuk']),
                                                                        'H:i') }}</span>
                                                                    @endif
                                                                    @if ($item_abs['status'] == 'keluar')
                                                                    <span class="text-white">{{
                                                                        date_format(date_create($item_abs['waktu_keluar']),
                                                                        'H:i') }}</span>
                                                                    @endif
                                                                </td>
                                                                @else
                                                                <td class="bg-secondary"></td>
                                                                @endif
                                                                @endforeach
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    {{-- <table class="datatable table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>NIP</th>
                                                                <th>Nama</th>
                                                                <th>Tanggal</th>
                                                                <th>Waktu Masuk</th>
                                                                <th>Waktu Keluar</th>
                                                                <th>Total Jam</th>
                                                                <th>Dokumentasi</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($absensi as $item)
                                                            <tr>
                                                                <th scope="row">{{ $loop->iteration }}</th>
                                                                <td>{{ $item->user['nip'] }}</td>
                                                                <td>{{ $item->user['nama'] }}</td>
                                                                <td>{{ $item->tanggal }}</td>
                                                                <td>{{ $item->waktu_masuk }} WIB</td>
                                                                <td>{{ $item->waktu_keluar }} WIB</td>
                                                                <td>{{ $item->total_jam }} Jam</td>
                                                                <td>
                                                                    <a href="{{ Storage::url('public/uploads/'.$item->dok_masuk) }}"
                                                                        target="_blank">
                                                                        <p>Dok. Masuk</p>
                                                                    </a>
                                                                    @if(Storage::disk('public')->has('uploads/'.$item->dok_keluar))
                                                                    <a href="{{ Storage::url('public/uploads/'.$item->dok_keluar) }}"
                                                                        target="_blank">
                                                                        <p>Dok. Keluar</p>
                                                                    </a>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-info" title="Lihat detail">
                                                                        <span class="fas fa-info-circle"></span>
                                                                    </button>
                                                                    <button class="btn btn-success"
                                                                        title="Cetak detail">
                                                                        <span class="fas fa-print"></span>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- modals --}}
        <div class="modal" id="modal-form" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah pengajuan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="jenis">Jenis izin</label>
                                <select name="jenis" id="jenis" class="form-control">
                                    <option value="">-- PILIH JENIS --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="durasi">Durasi</label>
                                <fieldset>
                                    <div class="control-group">
                                        <div class="controls">
                                            <div class="input-prepend input-group">
                                                <span class="add-on input-group-addon"><i class="fas fa-calendar"
                                                        style="vertical-align: middle"></i></span>
                                                <input type="text" style="width: 200px" name="durasi" id="durasi"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <input type="text" class="form-control" readonly="readonly" id="total-durasi">
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <input type="text" class="form-control" id="keterangan" required>
                            </div>
                            <div class="form-group">
                                <label for="bukti">Bukti</label>
                                <input type="file" class="form-control-file" id="bukti" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary">Ajukan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- modals --}}

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Sistem Informasi Absensi Kab. Bengkalis &copy 2022
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>


<script>
    const parsedAbsensi = JSON.parse(`<?= json_encode($absensi_bulanan) ?>`);
    const tableBody = [
        [
            { text: 'NIP', style: 'tableHeader' },
            { text: 'Nama', style: 'tableHeader' },
            { text: 'Tanggal', style: 'tableHeader' },
            { text: 'Waktu Masuk', style: 'tableHeader' },
            { text: 'Waktu Keluar', style: 'tableHeader' },
            { text: 'Total Jam', style: 'tableHeader' },
        ]
    ];
    parsedAbsensi.forEach(absensi => {
        const toPush = [absensi.nip, absensi.nama, absensi.tanggal, absensi.waktu_masuk || '-', absensi.waktu_keluar || '-', absensi.total_jam];
        tableBody.push(toPush.map(item => ({
            text: item,
            style: 'tableBody',
        })));
    })
    const docDefAllItems = {
        content: [
            {
                text: 'Laporan Absensi Bulanan',
                alignment: 'center',
                margin: [0,32,0,24],
                fontSize: 12,
            },
            {
                style: 'tableStyle',
                headerRows: 1,
                table: {
                    widths: ['*', '*', '*', '*', '*', '*'],
                    body: tableBody,
                },
                layout: 'lightHorizontalLines'
            }
        ],
        styles: {
            tableHeader: {
                fontSize: 10,
                bold: true,
                margin: [4,4,4,4]
            },
            tableBody: {
                fontSize: 10,
                margin: [4,4,4,4]
            }
        }
    }

    $('#print-all-bulanan').click(function(){
        pdfMake.createPdf(docDefAllItems).open();
    })
</script>
@endsection