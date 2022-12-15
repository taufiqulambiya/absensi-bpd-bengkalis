@extends('layouts.app')
@section('title', 'Izin Pegawai')

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
                                <div class="row">
                                    <x-izin.last-pengajuan />

                                    <div class="col-12">
                                        @if ($is_waiting || $has_izin || $izin_mendatang)
                                        <button class="btn btn-secondary mb-3 disabled" style="cursor: not-allowed"
                                            @if ($has_izin || $izin_mendatang)
                                            onclick="showErrorAlert('Masih ada pengajuan yang aktif.')"
                                            @else
                                            onclick="showErrorAlert('Masih ada pengajuan belum/sedang diproses.')"
                                            @endif
                                        ><i
                                                class="fas fa-plus"></i> Ajukan Izin</button>
                                        @else
                                        <button class="btn btn-primary mb-3" id="btn-add" data-toggle="modal"
                                            data-target="#modal-form"><i class="fas fa-plus"></i>
                                            <?= $level == 'admin' ? 'Tambahkan' : 'Ajukan' ?> Izin
                                        </button>
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab"
                                                            aria-controls="all" aria-selected="true">Pengajuan Pending</a>
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
                                            </div>

                                            <div class="card-body">
                                                <div class="tab-content py-3">
                                                    <div class="tab-pane fade show active table-responsive" id="all">
                                                        <x-izin.list-pending />
                                                    </div>
                                                    <div class="tab-pane fade table-responsive" id="terlewat">
                                                        <x-izin.list-missed />
                                                    </div>
                                                    <div class="tab-pane fade table-responsive" id="selesai">
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

                    <form action="{{ route('izin.store') }}" enctype="multipart/form-data" method="POST" id="form-add">
                        @csrf
                        <div id="method-inner"></div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="jenis">Jenis izin</label>
                                <select name="jenis" id="jenis" class="form-control" required>
                                    <option value="">-- PILIH JENIS --</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Urusan Keluarga">Urusan Keluarga</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div id="jenis-lainnya"></div>
                            </div>
                            <div class="row">
                                <div class="col-6 form-group">
                                    <label for="tgl_mulai">Tanggal Mulai</label>
                                    <input type="date" name="tgl_mulai" id="tgl_mulai" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="form-control" required>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="tgl_selesai">Tanggal Selesai</label>
                                    <input type="date" name="tgl_selesai" id="tgl_selesai"
                                        value="{{ date('Y-m-d', strtotime('+4 days')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control" id="keterangan" required>
                            </div>
                            <div class="form-group">
                                <label for="bukti">Bukti</label>
                                <input type="file" class="form-control-file" id="bukti" name="bukti" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Ajukan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <x-modal.tracking />

        <x-modal.delete id="modal-delete" title="Batalkan pengajuan?" desc="Tindakan ini akan membatalkan pengajuan. Lanjutkan
        membatalkan?" />

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
    $('#jenis').on('change', function() {
        if($(this).val() === 'Lainnya') {
            $('#jenis-lainnya').html(`<input name='jenis' class='form-control' placeholder='Pilih alasan' required />`);
        } else {
            $('#jenis-lainnya').html('');
        }
    })
</script>
@endsection