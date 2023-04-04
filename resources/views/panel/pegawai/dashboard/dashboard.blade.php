@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<style>
    .blinking {
        animation: blinker 1s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
</style>
<div class="container body">
    <div class="main_container">
        <!-- sidebar -->
        @include('layouts.sidebar')
        @include('layouts.topbar')

        <!-- page content -->
        <div class="right_col" role="main" style="min-height: calc(100vh - 55px);">
            <div class="row">
                <div class="col-md-4">
                    {{-- <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Pengajuan Izin</h4>
                                <i class="fas fa-right-from-bracket float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <div class="row align-items-center">
                                <div class="col-4">
                                    <span class="display-4 font-weight-bold">{{$izin_count}}</span>
                                </div>
                            </div>
                            <hr />
                            <a href="{{ route('izin.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div> --}}
                    <x-dashboard.overview
                        :bg-class="'bg-warning'"
                        :text-class="'text-dark'"
                        :icon-class="'fas fa-right-from-bracket'"
                        :title="'Pengajuan Izin'"
                        :count="$izin_count"
                        :link="route('izin.index')"
                    />
                </div>
                <div class="col-md-4">
                    {{-- <div class="card mb-3 bg-yellow text-dark">
                        <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Pengajuan Cuti</h4>
                                <i class="fas fa-right-from-bracket float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <span class="display-4 font-weight-bold">{{$cuti_count}}</span>
                                </div>
                            </div>
                            <hr />
                            <a href="{{ route('cuti.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div> --}}
                    
                    <x-dashboard.overview
                        :bg-class="'bg-yellow'"
                        :text-class="'text-dark'"
                        :icon-class="'fas fa-right-from-bracket'"
                        :title="'Pengajuan Cuti'"
                        :count="$cuti_count"
                        :link="route('cuti.index')"
                    />
                </div>

                {{-- <x-dinas-luar.aktif /> --}}
                <x-izin.last-pengajuan :data="$last_izin" />
                <x-cuti.last-pengajuan :data="$last_cuti" />
            </div>
        </div>
        <!-- /page content -->

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