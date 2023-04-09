@extends('layouts.app')
@section('title', 'Riwayat Absensi')

@section('content')
<style>
    .page-item:not(.active) .page-link {
        color: #2A3F54 !important;
    }

    .page-item.active .page-link {
        background-color: #2A3F54 !important;
        border-color: #2A3F54 !important;
    }
</style>

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
                                                <a href="?print=all" target="_blank" class="btn btn-success mb-3">
                                                    <i class="fa fa-print mr-2" aria-hidden="true"></i>Cetak
                                                </a>
                                            </div>
                                            <div class="col-12">
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
                                                        @php
                                                        $user = $absensi;
                                                        @endphp
                                                        @foreach ($user->absensi as $a)
                                                        <tr>
                                                            <td scope="row">{{ $loop->iteration }}</td>
                                                            <td>{{ $user->nip }}</td>
                                                            <td>{{ $user->nama }}</td>
                                                            <td>{{ $a->formatted_tanggal }}</td>
                                                            <td>{{ $a->formatted_waktu_masuk }}</td>
                                                            <td>{{ $a->formatted_waktu_keluar }}</td>
                                                            <td>{{ $a->formatted_shift }}</td>
                                                            <td>{{ $a->total_jam }}</td>
                                                            <td>
                                                                {{-- check if file is exist in storage --}}
                                                                @if ($a->dok_masuk &&
                                                                Storage::exists('public/uploads/'.$a->dok_masuk))
                                                                <a href="{{ Storage::url('public/uploads/'.$a->dok_masuk) }}"
                                                                    target="_blank" class="d-block">Dok. Masuk</a>
                                                                @endif
                                                                @if ($a->dok_keluar
                                                                &&Storage::exists('public/uploads/'.$a->dok_keluar))
                                                                <a href="{{ Storage::url('public/uploads/'.$a->dok_keluar) }}"
                                                                    target="_blank" class="d-block">Dok. Keluar</a>
                                                                @endif

                                                                {{-- @if ($a->dok_masuk)
                                                                <a href="{{ Storage::url('public/uploads/'.$a->dok_masuk) }}"
                                                                    target="_blank" class="d-block">Dok. Masuk</a>
                                                                @endif
                                                                @if ($a->dok_keluar)
                                                                <a href="{{ Storage::url('public/uploads/'.$a->dok_keluar) }}"
                                                                    target="_blank" class="d-block">Dok. Keluar</a>
                                                                @endif --}}
                                                            </td>
                                                            <td>{{ strtoupper($a->status) }}</td>
                                                            <td>
                                                                {{-- <button class="btn btn-success btn-print-detail"
                                                                    data-id="{{ $a->id }}"><i class="fa fa-print"
                                                                        aria-hidden="true"></i></button> --}}
                                                                <a href="?print=id&&id={{$a->id}}" target="_blank"
                                                                    class="btn btn-success"><i class="fa fa-print"
                                                                        aria-hidden="true"></i></a>
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

{{-- <script src="{{ asset('js/printer.js') }}"></script>
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
</script> --}}
@endsection