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
                <div class="col-md-4 col-lg-3">
                    <x-dashboard.overview :bg_class="'bg-info'" :text_class="'text-white'" :icon_class="'fas fa-list'"
                        :title="'Absensi'" :count="$absensi_count" :link="route('absensi.index')" />
                    {{-- <div class="card mb-3 bg-info text-white">
                        <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Absensi</h4>
                                <i class="fas fa-list float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <span class="display-4 font-weight-bold">{{$absensi_count}}</span>
                            <hr />
                            <a href="{{ route('absensi.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div> --}}
                </div>
                <div class="col-md-4 col-lg-3">
                    {{-- <div class="card mb-3 bg-warning text-dark">
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
                                    <span class="text-info float-right" style="font-size: 14px">{{$izin_pending}}
                                        Pengajuan
                                        Pending</span>
                                    @endif
                                </div>
                            </div>
                            <hr />
                            <a href="{{ route('izin.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div> --}}
                    <x-dashboard.overview :bg_class="'bg-warning'" :text_class="'text-dark'"
                        :icon_class="'fas fa-right-from-bracket'" :title="'Pengajuan Izin'" :count="$izin_count" :pending_count="$izin_pending"
                        :link="route('izin.index')" />
                </div>
                <div class="col-md-4 col-lg-3">
                    {{-- <div class="card mb-3 bg-yellow text-dark"> --}}
                        {{-- <div class="card-body">
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
                                    <span class="text-info float-right" style="font-size: 14px">{{$cuti_pending}}
                                        Pengajuan
                                        Pending</span>
                                    @endif
                                </div>
                            </div>
                            <hr />
                            <a href="{{ route('cuti.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div> --}}
                        <x-dashboard.overview :bg_class="'bg-yellow'" :text_class="'text-dark'"
                            :icon_class="'fas fa-right-from-bracket'" :title="'Pengajuan Cuti'" :count="$cuti_count" :pending_count="$cuti_pending"
                            :link="route('cuti.index')" />
                    {{-- </div> --}}
                </div>
                <div class="col-md-4 col-lg-3">
                    {{-- <div class="card mb-3 bg-danger text-white">
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
                    </div> --}}
                    <x-dashboard.overview :bg_class="'bg-danger'" :text_class="'text-white'"
                        :icon_class="'fas fa-envelope'" :title="'Dinas Luar'" :count="$dinas_luar_count" :link="route('dinas_luar.index')" />
                </div>
                <div class="col-md-4 col-lg-3">
                    {{-- <div class="card mb-3 bg-dongker text-white">
                        <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Jam Kerja</h4>
                                <i class="fas fa-clock float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <span class="display-4 font-weight-bold">{{$jam_kerja_count}}</span>
                            <hr />
                            <a href="{{ route('jam_kerja.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div> --}}
                    <x-dashboard.overview
                        :bg-class="'bg-blue'"
                        :text-class="'text-white'"
                        :icon-class="'fas fa-clock'"
                        :title="'Jam Kerja'"
                        :count="$jam_kerja_count"
                        :link="route('jam_kerja.index')"
                    />
                </div>
                <div class="col-md-4 col-lg-3">
                    {{-- <div class="card mb-3 bg-success text-white">
                        <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Data Pegawai</h4>
                                <i class="fas fa-users float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <span class="display-4 font-weight-bold">{{$pegawai_count}}</span>
                            <hr />
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div> --}}
                    <x-dashboard.overview
                        :bg-class="'bg-success'"
                        :text-class="'text-white'"
                        :icon-class="'fas fa-users'"
                        :title="'Data Pegawai'"
                        :count="$pegawai_count"
                        :link="route('users.index')"
                    />
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