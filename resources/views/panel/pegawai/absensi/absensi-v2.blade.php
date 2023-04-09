@extends('layouts.app')
@section('title', 'Absensi')

@section('content')

{{-- <div class="data-container" data-jam-kerja="{{ $jam_kerja }}"></div>
<div class="data-container" data-absensi-id="{{ $missed_out->id ?? $current_absensi->id ?? '' }}"></div> --}}

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
                            <div class="x_content">
                                @if ($disableRecord)
                                @foreach ($disableRecordReason as $item)
                                {{-- @dump($item) --}}
                                <div class="col-md-6">
                                    @if (!empty($item['livewire']))
                                    @livewire($item['livewire'])
                                    @else
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Pesan</h4>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="text-center">Absensi belum dibuka.</h4>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                                @else
                                @if ($hasMissedOut)
                                <div class="col-md-12">
                                    <div class="alert alert-secondary" role="alert">
                                        <h4 class="alert-heading">Perhatian</h4>
                                        <p style="font-size: 1rem">Anda sepertinya lupa melakukan log keluar sejak {{
                                            date_format(date_create($missedOut->tanggal), 'd/m/Y') }}, silahkan log
                                            keluar
                                            terlebih dahulu.</p>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <h4 class="card-title text-center">Absensi Masuk</h4>
                                        </div>
                                        @if ($currentAbsensi)
                                        <x-absensi.detail :data="$currentAbsensi" :type="'in'" />
                                        @else
                                        <livewire:absensi.record :mode="'in'" />
                                        @endif
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-success text-white">
                                            <h4 class="card-title text-center">Absensi Keluar</h4>
                                        </div>
                                        @if ($currentAbsensi && $currentAbsensi->has_out)
                                        <x-absensi.detail :data="$currentAbsensi" :type="'out'" />
                                        @else
                                        <livewire:absensi.record :mode="'out'" />
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
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