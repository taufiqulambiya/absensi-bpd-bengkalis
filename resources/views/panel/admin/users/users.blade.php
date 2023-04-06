@extends('layouts.app')
@section('title', 'Daftar Pegawai')

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
                @csrf

                <div class="row">
                    <div class="col-md-12 col-sm-12  ">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Konten @yield('title')</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-add" data-toggle="modal"
                                            data-target="#modal-form"><i class="fas fa-plus"></i> Tambah
                                            Pengguna</button>
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <livewire:users.table />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- modals --}}
        <livewire:users.modal />
        {{-- modals --}}

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
        const lw = window.livewire;
        lw.on('success', (message) => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: message,
            });
        });
        lw.on('successHtml', htmlMessage => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                html: htmlMessage,
            });
        });
        lw.on('error', (message) => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: message,
            });
        });
        lw.on('toggleModal', () => {
            $('#modal-form').modal('toggle');
        });
        lw.on('confirmResetPassword', id => {
            Swal.fire({
                title: 'Lanjutkan?',
                text: "Anda akan mereset password pengguna ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, reset!'
            }).then((result) => {
                if (result.isConfirmed) {
                    lw.emit('resetPassword', id);
                }
            })
        })
        lw.on('confirmDelete', id => {
            Swal.fire({
                title: 'Lanjutkan?',
                text: "Anda akan menghapus pengguna ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    lw.emit('delete', id);
                }
            })
        });

        $('#modal-form').on('hide.bs.modal', function() {
            lw.emit('resetForm');
        });

        $('table').DataTable();
    })
</script>

@endsection