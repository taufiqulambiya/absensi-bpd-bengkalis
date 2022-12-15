@extends('layouts.app')
@section('title', 'Profil Saya')

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

                <div class="row">
                    <div class="col-md-6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Konten @yield('title')</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="card">
                                    <div class="card-body">
                                        <button class="btn btn-info" id="btn-toggle-form">Perbarui <i
                                                class="fas fa-pencil"></i></button>

                                        <button class="btn btn-warning" data-toggle="modal"
                                            data-target="#modal-password"><i class="fas fa-key"></i> Ubah
                                            Password</button>
                                        <img src="{{ Storage::url('public/uploads/'.$data->gambar) }}" alt="Gambar"
                                            class="img-fluid img-thumbnail d-block mx-auto my-3 w-50">
                                        <form action="{{ route('users.update', $data->id) }}"
                                            enctype="multipart/form-data" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="nama">Nama</label>
                                                <h4 class="font-weight-bold form-text-profile float-right">{{
                                                    $data->nama }}
                                                </h4>
                                                <input type="text" id="nama" name="nama" placeholder="Nama..."
                                                    class="form-control form-profile d-none" value="{{ $data->nama }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="nip">NIP</label>
                                                <h4 class="font-weight-bold form-text-profile float-right">{{ $data->nip
                                                    }}
                                                </h4>
                                                <input type="text" id="nip" name="nip" placeholder="NIP..."
                                                    class="form-control form-profile d-none" value="{{ $data->nip }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="golongan">Golongan</label>
                                                <h4 class="font-weight-bold float-right">{{ $data->golongan }}</h4>
                                            </div>
                                            <div class="form-group">
                                                <label for="jabatan">Jabatan</label>
                                                <h4 class="font-weight-bold float-right">{{ $data->jabatan }}</h4>
                                            </div>
                                            <div class="form-group">
                                                <label for="tgl_lahir">Tanggal Lahir</label>
                                                <h4 class="font-weight-bold form-text-profile float-right">{{
                                                    date_format(date_create($data->tgl_lahir), 'd/m/Y') }}
                                                </h4>
                                                <input type="date" id="tgl_lahir" name="tgl_lahir"
                                                    class="form-control form-profile d-none"
                                                    value="{{ $data->tgl_lahir }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="no_telp">Nomor HP</label>
                                                <h4 class="font-weight-bold form-text-profile float-right">{{
                                                    $data->no_telp
                                                    }}</h4>
                                                <input type="text" id="no_telp" name="no_telp" placeholder="No HP..."
                                                    class="form-control form-profile d-none"
                                                    value="{{ $data->no_telp }}">
                                            </div>
                                            <div class="form-group file d-none">
                                                <label for="gambar">Gambar</label>
                                                <input type="file" name="gambar" id="gambar" class="form-control-file">
                                            </div>
                                            <button class="btn btn-success form-profile d-none">Simpan
                                                profil</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-password" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('users.update', $data->id) }}" method="POST" id="form-password">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="update_password" value="1">
                            <div class="form-group">
                                <label for="old-password">Password lama</label>
                                <input type="password" name="old-password" placeholder="Password lama..."
                                    id="old-password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new-password">Password baru</label>
                                <input type="password" name="new-password" placeholder="Password baru..."
                                    id="new-password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new-password2">Konfirmasi password baru</label>
                                <input type="password" name="new-password2" placeholder="Konfirmasi password baru..."
                                    id="new-password2" class="form-control">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" form="form-password">Simpan</button>
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