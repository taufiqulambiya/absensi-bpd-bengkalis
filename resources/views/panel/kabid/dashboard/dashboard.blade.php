@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="container body">
    <div class="main_container">
        <!-- sidebar -->
        @include('layouts.sidebar')
        @include('layouts.topbar')

        <!-- page content -->
        <div class="right_col" role="main" style="min-height: calc(100vh - 55px);">

            <div class="row">
                <div class="col-md-4">
                    {{-- <div class="card bg-info text-white">
                        <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Pegawai</h4>
                                <i class="fas fa-users float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <span class="display-4 font-weight-bold">{{$pegawai_count}}</span>
                            <hr />
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div>
                    </div> --}}
                    {{-- <x-dashboard.overview
                        :bg-class="'bg-yellow'"
                        :text-class="'text-dark'"
                        :icon-class="'fas fa-right-from-bracket'"
                        :title="'Pengajuan Cuti'"
                        :count="$cuti_count"
                        :link="route('cuti.index')"
                    /> --}}
                    <x-dashboard.overview
                        :bg-class="'bg-info'"
                        :text-class="'text-white'"
                        :icon-class="'fas fa-users'"
                        :title="'Pegawai'"
                        :count="$pegawai_count"
                        :link="route('users.index')"
                    />
                </div>
                <div class="col-md-4">
                    {{-- <div class="card bg-warning text-dark"> --}}
                        {{-- <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Pengajuan Izin</h4>
                                <i class="fas fa-right-from-bracket float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <div class="p-3">
                                <div class="display-4 font-weight-bold">{{$izin_count}}</div>
                                @if ($izin_pending > 0)
                                <div class="text-primary">{{$izin_pending}} Pengajuan
                                    Pending</div>
                                @endif
                            </div>
                            <hr />
                            <a href="{{ route('izin.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-database fa-fw"></i> Detail</a>
                        </div> --}}
                    {{-- </div> --}}
                    {{-- <x-dashboard.overview :bg_class="'bg-yellow'" :text_class="'text-dark'"
                        :icon_class="'fas fa-right-from-bracket'" :title="'Pengajuan Cuti'" :count="$cuti_count" :pending_count="$cuti_pending"
                        :link="route('cuti.index')" /> --}}
                    <x-dashboard.overview
                        :bg-class="'bg-warning'"
                        :text-class="'text-dark'"
                        :icon-class="'fas fa-right-from-bracket'"
                        :title="'Pengajuan Izin'"
                        :count="$izin_count"
                        :pending-count="$izin_pending"
                        :link="route('izin.index')"
                    />
                </div>
                <div class="col-md-4">
                    {{-- <div class="card bg-yellow text-dark">
                        <div class="card-body">
                            <div>
                                <h4 class="card-title d-inline-block">Pengajuan Cuti</h4>
                                <i class="fas fa-right-from-bracket float-right" style="font-size: 32px"></i>
                            </div>
                            <hr />
                            <div class="p-3">
                                <div class="display-4 font-weight-bold">{{$cuti_count}}</div>
                                @if ($cuti_pending > 0)
                                <div class="text-primary">{{$cuti_pending}} Pengajuan
                                    Pending</div>
                                @endif
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
                        :pending-count="$cuti_pending"
                        :link="route('cuti.index')"
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