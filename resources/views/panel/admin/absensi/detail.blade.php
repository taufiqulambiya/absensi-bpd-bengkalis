@extends('layouts.app')
@section('title', 'Detail Absensi')

@section('content')
<style>
    .capture-btn-container {
        border-radius: 44px;
        display: flex;
        justify-content: center;
        bottom: 20px;
    }
</style>

@php
function renderList($label, $value) {
    return '<div class="col-6">'.$label.'<span class="float-right">:</span></div>
    <div class="col-6">'.$value.'</div>';
}
function isUrl($url) {
    return preg_match('/^http(s)?:\/\//', $url);
}
@endphp
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

                <div class="x_panel">
                    <a href="{{route('absensi.index')}}" role="button" class="btn btn-secondary">Kembali</a>
                    <div class="x_title">
                        <h2>Konten @yield('title')</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Detail Pegawai</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" style="row-gap: 14px;">
                                            <div class="col-12">
                                                <img src="{{ Storage::url('public/user_images/'.$absensi->user->gambar) }}"
                                                    alt="{{ $absensi->user->nama }}"
                                                    class="img-thumbnail d-block mx-auto mb-3"
                                                    style="height: 120px; width: 120px; object-fit: cover;">
                                            </div>
                                            {!! renderList('Nama', $absensi->user->nama) !!}
                                            {!! renderList('NIP', $absensi->user->nip) !!}
                                            {!! renderList('Golongan', $absensi->user->golongan) !!}
                                            {!! renderList('Jabatan', $absensi->user->jabatan) !!}
                                            {!! renderList('Tanggal Lahir', $absensi->user->tgl_lahir) !!}
                                            {!! renderList('Jenis Kelamin', $absensi->user->jk) !!}
                                            {!! renderList('Nomor Telepon', $absensi->user->no_telp) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Absensi Tanggal - {{
                                            date_format(date_create($absensi->tanggal), 'd/m/Y') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-block mx-auto mb-3">
                                            <h4 class="text-primary text-center">Absensi Masuk</h4>
                                        </div>
                                        @if ($absensi)
                                        <div class="row" style="row-gap: 14px;">
                                            {!! renderList('Tanggal', date_format(date_create($absensi->tanggal), 'd/m/Y')) !!}
                                            {!! renderList('Waktu', $absensi->waktu_masuk) !!}
                                            {!! renderList('Lokasi', $absensi->lokasi_masuk) !!}
                                            {!! renderList('Jam Absensi', $absensi->jam_absen) !!}
                                            {!! renderList('Jarak dari kantor', $absensi->jarak_masuk.' Meter') !!}
                                            <div class="col-6">
                                                Gambar
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                @php
                                                    $src = isUrl($absensi->dok_masuk) ? $absensi->dok_masuk : Storage::url('public/uploads/'.$absensi->dok_masuk);
                                                @endphp
                                                <img src="{{ $src }}"
                                                    alt="{{ $absensi->dok_masuk }}" class="img-thumbnail"
                                                    style="width: 100%; aspect-ratio: 1; object-fit: cover; cursor: pointer;"
                                                    onclick="window.open(event.target.src)">
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-center p-3">
                                            <img src="/img/not_found.png" alt="Not Found" class="img-fluid">
                                            <h4>Belum ada data absensi</h4>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Absensi Tanggal - {{
                                            date_format(date_create($absensi->tanggal), 'd/m/Y') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-block mx-auto mb-3">
                                            <h4 class="text-success text-center">Absensi Keluar</h4>
                                        </div>
                                        @if ($absensi AND $absensi->has_out)
                                        <div class="row" style="row-gap: 14px;">
                                            {!! renderList('Tanggal', date_format(date_create($absensi->tanggal), 'd/m/Y')) !!}
                                            {!! renderList('Waktu', $absensi->waktu_keluar) !!}
                                            {!! renderList('Lokasi', $absensi->lokasi_keluar) !!}
                                            {!! renderList('Jam Absen', $absensi->jam_absen) !!}
                                            {!! renderList('Jarak dari kantor', $absensi->jarak_keluar.' Meter') !!}
                                            <div class="col-6">
                                                Gambar
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                @php
                                                    $src = isUrl($absensi->dok_keluar) ? $absensi->dok_keluar : Storage::url('public/uploads/'.$absensi->dok_keluar);
                                                @endphp
                                                <img src="{{ $src }}"
                                                    alt="{{ $absensi->dok_keluar }}" class="img-thumbnail"
                                                    style="width: 100%; aspect-ratio: 1; object-fit: cover; cursor: pointer;"
                                                    onclick="window.open(event.target.src)">
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-center p-3">
                                            <img src="/img/not_found.png" alt="Not Found" class="img-fluid">
                                            <h4>Belum ada data absensi</h4>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

<script>
    $(document).ready(function(){
        const toggleFormProfile = () => {
            $(".form-profile").each(function(){
                $(this).toggleClass("d-none");
            })
            $(".form-group.file").each(function(){
                $(this).toggleClass("d-none");
            })
            $(".form-text-profile").each(function(){
                $(this).toggleClass("d-none");
            })
        }
        const toggleFormPassword = () => {
            $("#form-password").toggleClass("d-none");
        }

        $("#btn-toggle-form").click(toggleFormProfile);
        $("#btn-toggle-password-form").click(toggleFormPassword);
    });
</script>
@endsection