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
                                                    <label for="filter">Filter Status</label>
                                                    <select name="filter-status" id="filter-status" class="form-control"
                                                        onchange="window.location.href = '?view=harian&status='+event.target.options[event.target.options.selectedIndex].value">
                                                        <option value="hadir" @if (!empty($_GET['status']) AND
                                                            $_GET['status']=='hadir' ) selected @endif>Hadir</option>
                                                        <option value="izin" @if (!empty($_GET['status']) AND
                                                            $_GET['status']=='izin' ) selected @endif>Izin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group col-3 p-0">
                                                    <label for="filter">Filter Tanggal</label>
                                                    <select name="filter-tgl" id="filter-tgl" class="form-control"
                                                        onchange="window.location.href = '?view=harian&tgl='+event.target.options[event.target.options.selectedIndex].value">
                                                        @for ($i = 1; $i <= $days_in_current_month; $i++) <option
                                                            value={{ $i }} @if (!empty($_GET['tgl']) AND
                                                            $_GET['tgl']==$i) selected @elseif(empty($_GET['tgl']) AND
                                                            intval(date('d'))==$i) selected @endif>{{$i}} {{ date('F')
                                                            }}</option>
                                                            @endfor
                                                    </select>
                                                </div>
                                                <h4 class="my-3">Tanggal Hari Ini : {{ date('d - F - Y') }}</h4>
                                            </div>
                                            <div class="col-12">
                                                <hr />
                                                <button class="btn btn-success mb-3" id="print-all">
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
                                                                <th>Total Jam</th>
                                                                <th>Dokumentasi</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($absensi as $item)
                                                            <tr
                                                                class="@if($item->status == 'izin') text-primary @endif">
                                                                <th scope="row">{{ $loop->iteration }}</th>
                                                                <th>{{ $item->nip }}</th>
                                                                <th>{{ $item->nama }}</th>
                                                                <th>{{ $item->tanggal_absensi }}</th>
                                                                <th>{{ $item->absensi->first()['waktu_masuk'] ?? '-' }}
                                                                </th>
                                                                <th>{{ $item->absensi->first()['waktu_keluar'] ?? '-' }}
                                                                </th>
                                                                <th>{{ $item->absensi->first()['total_jam'] ?? '-' }}
                                                                </th>
                                                                <th>
                                                                    @if ($item->absensi->first()['dok_masuk'] ?? null)
                                                                    @if(Storage::disk('public')->has('uploads/'.$item->absensi->first()['dok_masuk']))
                                                                    <a href="{{ Storage::url('public/uploads/'.$item->absensi->first()['dok_masuk']) }}"
                                                                        target="_blank">
                                                                        <p>Dok. Masuk</p>
                                                                    </a>
                                                                    @endif
                                                                    @endif
                                                                    @if ($item->absensi->first()['dok_keluar'] ?? null)
                                                                    @if(Storage::disk('public')->has('uploads/'.$item->absensi->first()['dok_keluar']))
                                                                    <a href="{{ Storage::url('public/uploads/'.$item->absensi->first()['dok_keluar']) }}"
                                                                        target="_blank">
                                                                        <p>Dok. Keluar</p>
                                                                    </a>
                                                                    @endif
                                                                    @endif
                                                                </th>
                                                                <th>
                                                                    <button class="btn btn-success btn-print-detail"
                                                                        data-item="{{ $item }}"><i class="fa fa-print"
                                                                            aria-hidden="true"></i></button>
                                                                </th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

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


<script src="{{ asset('js/printer.js') }}"></script>
<script>
    $('#print-all').click(function(){
        const data = JSON.parse(`<?= $absensi ?>`);
        printAllAbsensi(data, null, 'admin');
    });
    $('.btn-print-detail').each(function(){
        const item = $(this).data('item');
        $(this).click(function(){
            console.log(item);
            // printPerItem(item);
        })
    });
</script>
@endsection