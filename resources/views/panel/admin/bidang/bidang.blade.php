@extends('layouts.app')
@section('title', 'Bidang')

@section('content')
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
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary" id="btn-add" data-toggle="modal"
                                            data-target="#modal-form"><i class="fas fa-plus"></i> Tambah Data</button>
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <livewire:bidang.table />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <livewire:bidang.modal />

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
    document.addEventListener('livewire:load', () => {
        const LW = window.livewire;
        LW.on('success', (message) => {
            $('#modal-form').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: message,
            });
        });
        LW.on('error', (message) => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: message,
            });
        });
        // confimDelete
        LW.on('confimDelete', id => {
            Swal.fire({
                title: 'Lanjutkan?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
            }).then((result) => {
                if (result.isConfirmed) {
                    LW.emit('delete', id);
                }
            });
        });
    });
</script>
@endsection