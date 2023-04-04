@extends('layouts.app')
@section('title', 'Izin Pegawai')

@section('content')

{{-- data container between php and js --}}
@csrf
{{-- end data container between php and js --}}

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
                                    <x-izin.last-pengajuan :data="$activeIzin" />

                                    <div class="col-12">
                                        @if ($allow_ajukan)
                                        <button class="btn btn-primary mb-3" id="btn-add" data-toggle="modal"
                                            data-target="#modal-form"><i class="fas fa-plus"></i>
                                            <?= $level == 'admin' ? 'Tambahkan' : 'Ajukan' ?> Izin
                                        </button>
                                        @else
                                        <button class="btn btn-secondary mb-3 disabled" style="cursor: not-allowed"
                                            onclick="showErrorAlert('Masih ada pengajuan belum diproses / sedang berlangsung.')"><i
                                                class="fas fa-plus"></i> Ajukan Izin</button>
                                        @endif
                                    </div>

                                    <div class="col-12">
                                        <livewire:izin.tabs />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <livewire:izin.modal />
        {{-- <x-modal.tracking /> --}}

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

{{--
<script src="{{ asset('js/izin-page.js') }}"></script>
<script>
    $('#jenis').on('change', function() {
        if($(this).val() === 'Lainnya') {
            $('#jenis-lainnya').html(`<input name='jenis' class='form-control' placeholder='Pilih alasan' required />`);
        } else {
            $('#jenis-lainnya').html('');
        }
    })
</script> --}}
@endsection