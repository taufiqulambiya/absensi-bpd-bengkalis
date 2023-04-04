@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<style>
    .prfltbl input {
        font-size: 14px;
    }

    .image-container {
        position: relative;
    }

    .upload-image {
        display: inline-block;
        position: absolute;
        left: 50%;
        bottom: 4px;
        transform: translateX(-50%);
    }

    #user-image {
        aspect-ratio: 1/1;
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
                        <h3>@yield('title')</h3>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-6">
                        <livewire:profile />
                    </div>
                    <div class="col-md-6">
                        <livewire:update-password />
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
    document.addEventListener('livewire:load', function () {
        window.livewire.on('success', (message) => {
            Swal.fire({
                title: 'Berhasil!',
                text: message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.reload();
            });
        });
    });
</script>

@endsection