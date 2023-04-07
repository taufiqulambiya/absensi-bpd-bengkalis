@extends('layouts.app')
@section('title', 'Settings')
@section('content')

<style>
    #map {
        width: auto;
        height: 300px;
        margin-bottom: 32px;
    }
</style>

<div class="container body">
    <div class="main_container">
        <!-- sidebar -->
        @include('layouts.sidebar')
        @include('layouts.topbar')

        <!-- /page content -->
        <div class="right_col" role="main">
            <div class="x_content">
                <livewire:admin.settings />
            </div>
        </div>

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