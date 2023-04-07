@extends('layouts.app')
@section('title', 'Cuti Pegawai')

@section('content')
<div class="data-container" data-id-user="{{ session('user')->id }}"></div>

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
                            <div class="row">
                                @if ($has_cuti)
                                <div class="col-md-6">
                                    {{-- <x-cuti.detail-card /> --}}
                                    <livewire:cuti.detail-card />
                                </div>
                                @endif
                                <div class="col-md-6">
                                    {{-- <x-cuti.jatah-cuti-card :data="$jatah_cuti" :enable-add="$allowed_ajukan" /> --}}
                                    <livewire:cuti.jatah-cuti-card :data="$jatah_cuti" :enable-add="$allowed_ajukan" />
                                </div>


                                <livewire:cuti.tabs />
                                <livewire:cuti.modal
                                    :disable-dates="$disable_dates"
                                    :jatah-cuti="$jatah_cuti"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- modals --}}
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
@endsection