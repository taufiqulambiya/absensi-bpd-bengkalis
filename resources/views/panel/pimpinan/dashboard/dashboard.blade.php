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
                <div class="col-md-3">
                    <div class="card mb-3 bg-warning text-dark">
                        <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Pengajuan Izin</h4>
                                <i class="fas fa-right-from-bracket float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <span class="display-4 font-weight-bold">{{$izin_count}}</span>
                                </div>
                                <div class="col-6">
                                    @if ($izin_pending > 0)
                                    <span class="text-info float-right" style="font-size: 14px">{{$izin_pending}} Pengajuan
                                        Pending</span>
                                    @endif
                                </div>
                            </div>
                            <hr />
                            <a href="{{ route('izin.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-3 bg-yellow text-dark">
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
                                <div class="col-6">
                                    @if ($cuti_pending > 0)
                                    <span class="text-info float-right" style="font-size: 14px">{{$cuti_pending}} Pengajuan
                                        Pending</span>
                                    @endif
                                </div>
                            </div>
                            <hr />
                            <a href="{{ route('cuti.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card mb-3 bg-danger text-white">
                        <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Dinas Luar</h4>
                                <i class="fas fa-envelope float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <span class="display-4 font-weight-bold">{{$dinas_luar_count}}</span>
                                </div>
                            </div>
                            <hr />
                            <a href="{{ route('dinas_luar.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div>
                </div>
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