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
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <div class="border d-inline-block p-1">
                                                        <div class="bg-warning d-inline-block"
                                                            style="width: 24px; height: 24px; vertical-align: middle">
                                                        </div>
                                                        <p class="d-inline">Log Keluar Terlewat/Terlupa</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
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
                                                                <th>Status</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($absensi as $item)
                                                            <tr @if ($item->forgotten)
                                                                class="text-warning"
                                                                @endif>
                                                                <th scope="row">{{ $loop->iteration }}</th>
                                                                <th>{{ $item->user->nip }}</th>
                                                                <th>{{ $item->user->nama }}</th>
                                                                <th>{{ $item->tanggal }}</th>
                                                                <th>{{ $item->waktu_masuk }}</th>
                                                                <th>{{ $item->waktu_keluar }}</th>
                                                                <th>{{ $item->total_jam }}</th>
                                                                <th>
                                                                    @if ($item->dok_masuk)
                                                                    <a href="{{ Storage::url('public/uploads/'.$item->dok_masuk) }}"
                                                                        target="_blank" class="d-block">Dok. Masuk</a>
                                                                    @endif
                                                                    @if ($item->dok_keluar)
                                                                    <a href="{{ Storage::url('public/uploads/'.$item->dok_keluar) }}"
                                                                        target="_blank" class="d-block">Dok. Keluar</a>
                                                                    @endif
                                                                </th>
                                                                <th>{{ strtoupper($item->status) }}</th>
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
        const date = `{{ date('d/m/Y') }}`;
        printAllAbsensi(data, date);
    })

    $('.btn-print-detail').each(function() {
        const item = $(this).data('item');
        $(this).click(function(){
            console.log(item);
            printPerItem(item);
        });
    })
</script>
@endsection