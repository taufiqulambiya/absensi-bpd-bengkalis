@extends('layouts.app')
@section('title', 'Dinas Luar')

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
                    <div class="col-md-12 col-sm-12  ">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Konten @yield('title')</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <button data-toggle="modal" id="btn-add" data-target="#add"
                                    class="btn btn-primary mb-3">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Tambah
                                </button>

                                <livewire:dinas-luar.tabs />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- modals --}}
        {{--
        <x-modal.add-dinas-luar /> --}}
        <livewire:dinas-luar.modal />
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
    document.addEventListener("livewire:load", function (event) {
        const LW = window.livewire;
        LW.on("print", url => {
            window.open(url, '_blank');
        });

        LW.on("success", message => {
            Swal.fire({
                title: 'Berhasil',
                text: message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.reload();
            });
        })
    });
</script>

{{-- @csrf --}}
{{-- <script>
    const users = JSON.parse(`<?= json_encode($users) ?>`);
    loadjs([`{{ asset('js/add-dinas-luar.js') }}`], 'loaded');
    loadjs.ready('loaded', () => {
        console.log('loaded');
    })
</script> --}}
@endsection