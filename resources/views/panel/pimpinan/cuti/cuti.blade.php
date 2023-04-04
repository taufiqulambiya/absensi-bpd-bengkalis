@extends('layouts.app')
@section('title', 'Cuti Pegawai')

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
                                        <livewire:cuti.tabs />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- modals --}}
        <x-modal.tracking />

        <x-modal.delete id="modal-delete" title="Hapus data ini?" desc="Tindakan ini tidak bisa dibatalkan. Lanjutkan menghapus?" />
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
    let jatahCuti = `{{ $jatah_cuti_tahunan }}`;
</script>
<script src="{{ asset('js/cuti.js') }}"></script>
<script>
    $('.btn-acc').each(function() {
        $(this).click(function() {
            const id = $(this).data('id');
            cuti.acc(id, 'accepted_pimpinan');
        })
    })

    $('.btn-reject').each(function() {
        $(this).click(function() {
            const id = $(this).data('id');
            cuti.reject(id);
        })
    });
</script>
@endsection