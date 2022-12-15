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
                                                class="fa fa-info-circle" aria-hidden="true"></i> Pengajuan Terlewat</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="selesai-tab" data-toggle="tab" href="#selesai"
                                            role="tab" aria-controls="selesai" aria-selected="false"><i
                                                class="fa fa-check" aria-hidden="true"></i> Pengajuan Selesai</a>
                                    </li>
                                </ul>
                                <div class="row">
                                    <div class="col-12">
                                        @if ($level == 'pegawai')
                                        @if ($is_waiting)
                                        <button class="btn btn-secondary disabled" style="cursor: not-allowed"
                                            onclick="showErrorAlert('Masih ada pengajuan yang belum diproses.')"><i
                                                class="fas fa-plus"></i> Ajukan Izin</button>
                                        @else
                                        <button class="btn btn-primary" id="btn-add" data-toggle="modal"
                                            data-target="#modal-form"><i class="fas fa-plus"></i>
                                            <?= $level == 'admin' ? 'Tambahkan' : 'Ajukan' ?> Izin
                                        </button>
                                        @endif
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <div class="tab-content py-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="all" role="tabpanel"
                                                aria-labelledby="all-tab">
                                                <x-izin.list-pending />
                                            </div>
                                            <div class="tab-pane fade" id="terlewat" role="tabpanel"
                                                aria-labelledby="terlewat-tab">
                                                <x-izin.list-missed />
                                            </div>
                                            <div class="tab-pane fade" id="selesai" role="tabpanel"
                                                aria-labelledby="terlewat-tab">
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
        <div class="modal fade" id="tracking" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lacak Pengajuan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                               <div id="tracking-pre"></div>
                               <div id="tracking">
                                  <div class="tracking-list" id="tracking-list">
                                    <span class="d-block m-3 text-center" style="font-size: 16px;">Belum ada data.</span>
                                  </div>
                               </div>
                            </div>
                         </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="modal-delete" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus data ini?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="POST" id="form-delete">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <span class="text-danger">Tindakan ini tidak bisa dibatalkan. Lanjutkan menghapus?</span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger">Lanjut</button>
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

<script src="{{ asset('js/izin-page.js') }}"></script>
<script>
    $('.btn-delete').each(function() {
        const id = $(this).data('id');
        $(this).click(function() {
            const formURL = baseURL+'/panel/izin/'+id;
            $('#form-delete').attr('action', formURL);
        })
    })

    $('.btn-print').each(function(){
        const item = $(this).data('item');
        $(this).click(function(){
            izin.printPerItem(item);
        })
    });
</script>
@endsection