@extends('layouts.app')
@section('title', 'Izin Pegawai')

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
                <div id="canvas-container"></div>

                <div class="row">
                    <div class="col-md-12 col-sm-12  ">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Konten @yield('title')</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab"
                                            aria-controls="all" aria-selected="true">Pengajuan Aktif</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="terlewat-tab" data-toggle="tab" href="#terlewat"
                                            role="tab" aria-controls="terlewat" aria-selected="false"><i
                                                class="fa fa-info-circle" aria-hidden="true"></i> Pengajuan Terlewat
                                            @if ($missed_count>0) <span class="badge badge-danger">{{ $missed_count }}</span> @endif </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="selesai-tab" data-toggle="tab" href="#selesai"
                                            role="tab" aria-controls="selesai" aria-selected="false"><i
                                                class="fa fa-check" aria-hidden="true"></i> Pengajuan Selesai</a>
                                    </li>
                                </ul>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="tab-content py-3" id="myTabContent">
                                            @csrf
                                            <div class="tab-pane fade show active" id="all" role="tabpanel"
                                                aria-labelledby="all-tab">
                                                <x-izin.list-pending />
                                            </div>
                                            <div class="tab-pane fade" id="terlewat" role="tabpanel"
                                                aria-labelledby="terlewat-tab">
                                                <x-izin.list-missed />
                                            </div>
                                            <div class="tab-pane fade" id="selesai" role="tabpanel"
                                                aria-labelledby="selesai-tab">
                                                <x-izin.list-done />
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
        <x-modal.tracking />

        <x-modal.delete id="modal-delete" title="Hapus data ini?" desc="Tindakan ini tidak bisa dibatalkan. Lanjutkan menghapus?" />

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

<script src="{{ asset('js/izin-page.js') }}"></script>
<script>
    $('.btn-delete').each(function() {
        const id = $(this).data('id');
        $(this).click(function() {
            const formURL = baseURL+'/panel/izin/'+id;
            $('#form-delete').attr('action', formURL);
        })
    })
</script>
@endsection