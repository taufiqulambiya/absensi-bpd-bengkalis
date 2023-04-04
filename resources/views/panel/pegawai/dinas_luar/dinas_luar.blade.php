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
                                <livewire:dinas-luar.tabs />
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
    document.addEventListener('livewire:load', function () {
        const LW = window.Livewire;
        LW.on('print', url => {
            window.open(url, '_blank');
        })
    });
</script>
@endsection