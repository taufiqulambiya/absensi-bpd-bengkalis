@extends('layouts.app')
@section('title', 'Absensi')

@section('content')

<div class="data-container" data-jam-kerja="{{ $jam_kerja }}"></div>
<div class="data-container" data-absensi-id="{{ $missed_out->id ?? $current_absensi->id ?? '' }}"></div>

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
                    <div class="col-md-12 col-sm-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Konten @yield('title')</h2>
                                <div class="clearfix"></div>
                            </div>
                            @if ($has_dinas)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-secondary" role="alert">
                                        <h4 class="alert-heading"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                        </h4>
                                        <p>Absen terisi otomatis jika memiliki dinas luar.</p>
                                    </div>
                                </div>
                                <div class="col-md-6"></div>
                                <x-dinas-luar.aktif />
                            </div>
                            @elseif ($has_izin)
                            <div class="col-md-6">
                                <x-izin.detail-card :data="$izin" />
                            </div>
                            @elseif ($has_cuti)
                            <div class="col-md-6">
                                {{-- <x-cuti.detail-card :data="$cuti" /> --}}
                                <livewire:cuti.detail-card />
                            </div>
                            @endif
                            @if ($jam_kerja AND $jam_kerja['is_absen_time'])
                            {{-- JIKA ADA JAM KERJA --}}
                            <div class="row">
                                @if ($has_missed_out)
                                <div class="col-md-12">
                                    <div class="alert alert-secondary" role="alert">
                                        <h4 class="alert-heading">Perhatian</h4>
                                        <p style="font-size: 1rem">Anda sepertinya lupa melakukan log keluar sejak {{
                                            date_format(date_create($missed_out->tanggal), 'd/m/Y') }}, silahkan log
                                            keluar
                                            terlebih dahulu.</p>
                                    </div>
                                </div>
                                @elseif($current_absensi)
                                {{-- DATA ABSENSI --}}
                                <div class="col-md-6">
                                    <x-absensi.detail :data="$current_absensi" :type="'in'" />
                                </div>
                                {{-- DATA ABSENSI --}}
                                @else
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <h4 class="card-title text-center">Absensi Masuk</h4>
                                        </div>
                                        <livewire:absensi.record :disable_log="false" :id-jam="$jam_kerja->id"
                                            :jam-kerja="$jam_kerja->formatted ?? '-'" :mode="'in'" />
                                        {{--
                                        <x-absensi.log-masuk :disable-log="false" /> --}}
                                    </div>
                                </div>
                                @endif


                                @if ($current_absensi AND $current_absensi->has_out)
                                <div class="col-md-6">
                                    <x-absensi.detail :data="$current_absensi" :type="'out'" />
                                </div>
                                @else
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-success text-white">
                                            <h4 class="card-title text-center">Absensi Keluar</h4>
                                        </div>

                                        @if ($has_missed_out)
                                        {{--
                                        <x-absensi.log-keluar :disable-log="false" :missed-out="$missed_out"
                                            :jam-kerja="$jam_kerja" /> --}}
                                        <livewire:absensi.record :disable_log="false" :id-jam="$jam_kerja->id"
                                            :jam-kerja="$jam_kerja->formatted ?? '-'" :mode="'out'"
                                            :missed-out="$missed_out" />
                                        @else
                                        {{--
                                        <x-absensi.log-keluar :disable-log="empty($current_absensi)"
                                            :jam-kerja="$jam_kerja" :missed-out="$missed_out" /> --}}
                                        <livewire:absensi.record :disable_log="empty($current_absensi)"
                                            :id-jam="$jam_kerja->id" :jam-kerja="$jam_kerja->formatted ?? '-'"
                                            :mode="'out'" />
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            {{-- JIKA ADA JAM KERJA --}}
                            @else
                            <div class="x_content col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Pesan</h4>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="text-center">Absensi belum dibuka.</h4>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <x-layouts.copyright />
        <!-- /footer content -->
    </div>
</div>

@endsection