@extends('layouts.app')
@section('title', 'Cuti Pegawai')

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
                @csrf

                <div class="row">
                    <div class="col-md-12 col-sm-12  ">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Konten @yield('title')</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-6">
                                        <x-cuti.jatah-cuti-card />
                                    </div>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <ul class="nav nav-tabs nav-stacked mb-3">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="all-tab" data-toggle="tab"
                                                            href="#all" role="tab" aria-controls="all"
                                                            aria-selected="true">Pending</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="terlewat-tab" data-toggle="tab"
                                                            href="#terlewat" role="tab" aria-controls="terlewat"
                                                            aria-selected="true">Terlewat</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="selesai-tab" data-toggle="tab"
                                                            href="#selesai" role="tab" aria-controls="selesai"
                                                            aria-selected="true">Selesai</a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="card-body">
                                                <div class="tab-content py-3">
                                                    <div class="tab-pane fade show active table-responsive" id="all">
                                                        {{-- <x-cuti.table role="kabid" :data="$cuti_aktif" /> --}}
                                                        <x-cuti.list-pending />
                                                    </div>
                                                    <div class="tab-pane fade table-responsive" id="terlewat">
                                                        <x-cuti.list-missed />
                                                    </div>
                                                    <div class="tab-pane fade table-responsive" id="selesai">
                                                        <x-cuti.list-done />
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
        </div>

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

<script>
    let jatahCuti = `{{ $jatah_cuti_tahunan }}`;
</script>
<script src="{{ asset('js/cuti.js') }}"></script>
<script>
    $('.btn-acc').each(function() {
        $(this).click(function() {
            const id = $(this).data('id');
            cuti.acc(id, 'accepted_kabid');
        })
    });

    $('.btn-reject').each(function() {
        $(this).click(function() {
            const id = $(this).data('id');
            cuti.reject(id);
        })
    });
</script>
@endsection