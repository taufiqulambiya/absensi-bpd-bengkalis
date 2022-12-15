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
                                    @if (request('view'))
                                        @if (request('view') == 'harian')
                                        <x-absensi.list-harian />
                                        @else
                                        <x-absensi.list-bulanan />
                                        @endif
                                    @else
                                    <x-absensi.list-harian />
                                    @endif
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
        // const data = JSON.parse(`<?= $absensi ?>`);
        // printAllAbsensi(data, null, 'admin');
    });
    $('.btn-print-detail').each(function(){
        const item = $(this).data('item');
        $(this).click(function(){
            console.log(item);
            // printPerItem(item);
        })
    });
</script> --}}
@endsection