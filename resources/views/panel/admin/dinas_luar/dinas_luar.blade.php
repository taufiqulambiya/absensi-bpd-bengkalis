@extends('layouts.app')
@section('title', 'Dinas Luar')

@section('content')
<style>
    .capture-btn-container {
        border-radius: 44px;
        display: flex;
        justify-content: center;
        bottom: 20px;
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
                                <button data-toggle="modal" id="btn-add" data-target="#add"
                                    class="btn btn-primary mb-3">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Tambah
                                </button>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab"
                                            aria-controls="all" aria-selected="true">Mendatang</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="selesai-tab" data-toggle="tab" href="#selesai"
                                            role="tab" aria-controls="selesai" aria-selected="false"><i
                                                class="fa fa-check" aria-hidden="true"></i> Selesai</a>
                                    </li>
                                </ul>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="tab-content py-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="all" role="tabpanel"
                                                aria-labelledby="all-tab">
                                                <x-dinas-luar.list-coming />
                                            </div>
                                            <div class="tab-pane fade" id="selesai" role="tabpanel"
                                                aria-labelledby="terlewat-tab">
                                                <x-dinas-luar.list-done />
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
        <x-modal.add-dinas-luar />
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

@csrf
<script>
    const users = JSON.parse(`<?= json_encode($users) ?>`);
    loadjs([`{{ asset('js/add-dinas-luar.js') }}`], 'loaded');
    loadjs.ready('loaded', () => {
        console.log('loaded');
    })
</script>
@endsection