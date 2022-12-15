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
                    <a href="#" onclick="window.history.back()" role="button" class="btn btn-secondary">Kembali</a>
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
                                                <img src="{{ Storage::url('public/uploads/'.$absensi->user->gambar) }}"
                                                    alt="{{ $absensi->user->nama }}"
                                                    class="img-thumbnail w-50 d-block mx-auto mb-3">
                                            </div>
                                            <div class="col-6">Nama
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">{{ $absensi->user->nama }}</div>
                                            <div class="col-6">NIP
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">{{ $absensi->user->nip }}</div>
                                            <div class="col-6">Golongan
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">{{ $absensi->user->golongan }}</div>
                                            <div class="col-6">Jabatan
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">{{ $absensi->user->jabatan }}</div>
                                            <div class="col-6">Tanggal Lahir
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">{{ $absensi->user->tgl_lahir }}</div>
                                            <div class="col-6">Jenis Kelamin
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">{{ $absensi->user->jk }}</div>
                                            <div class="col-6">Nomor Telepon
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">{{ $absensi->user->no_telp }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Absensi Tanggal - {{ date_format(date_create($absensi->tanggal), 'd/m/Y') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-block mx-auto mb-3">
                                            <h4 class="text-primary text-center">Absensi Masuk</h4>
                                        </div>
                                        @if ($absensi)
                                        <div class="row" style="row-gap: 14px;">
                                            <div class="col-6">
                                                Waktu
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                {{ $absensi->waktu_masuk }}
                                            </div>
                                            <div class="col-6">
                                                Lokasi
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                {{ $absensi->lokasi_masuk }}
                                            </div>
                                            <div class="col-6">
                                                Jarak dari kantor
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                {{ $absensi->jarak_masuk }} Meter
                                            </div>
                                            <div class="col-6">
                                                Gambar
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                <img src="{{ Storage::url('public/uploads/'.$absensi->dok_masuk) }}"
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
                                        <h4 class="card-title">Absensi Tanggal - {{ date_format(date_create($absensi->tanggal), 'd/m/Y') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-block mx-auto mb-3">
                                            <h4 class="text-success text-center">Absensi Keluar</h4>
                                        </div>
                                        @if ($absensi AND $absensi->has_out)
                                        <div class="row" style="row-gap: 14px;">
                                            <div class="col-6">
                                                Waktu
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                {{ $absensi->waktu_keluar }}
                                            </div>
                                            <div class="col-6">
                                                Lokasi
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                {{ $absensi->lokasi_keluar }}
                                            </div>
                                            <div class="col-6">
                                                Jarak dari kantor
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                {{ $absensi->jarak_keluar }} Meter
                                            </div>
                                            <div class="col-6">
                                                Gambar
                                                <span class="float-right">:</span>
                                            </div>
                                            <div class="col-6">
                                                <img src="{{ Storage::url('public/uploads/'.$absensi->dok_keluar) }}"
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