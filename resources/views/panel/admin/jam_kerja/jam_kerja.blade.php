@extends('layouts.app')
@section('title', 'Jam Kerja')

@section('content')

<div class="data-container" data-allowed="{{ json_encode($allowed) }}"></div>

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
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary" id="btn-add" data-toggle="modal"
                                            data-target="#modal-form"><i class="fas fa-plus"></i> Tambah Jam</button>
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <livewire:jam-kerja.table />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- modals --}}
        <livewire:jam-kerja.modal />
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
    // on livewire load
    document.addEventListener('livewire:load', () => {
        window.livewire.on('success', (message) => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: message,
            }).then(() => {
                $('#modal-form').modal('hide');
            });
        });

        window.livewire.on('error', (message) => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: message,
            });
        });

        window.livewire.on('confirmDelete', id => {
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
                    window.livewire.emit('delete', id);
                }
            });
        })

        window.livewire.on('cancelSwitch', (elId) => {
            $(`#${elId}`).prop('checked', false);
        });

        window.livewire.on('edit', item => {
            console.log(item);
            const allDays = item.allDays.split(',').map(item => item.trim());
            const days = item.days.split(',').map(item => item.trim());
            const selectDays = $('#modal-form').find('select[name="days[]"]');
            // unitiaze selectize
            if(selectDays.hasClass('selectized')) {
                selectDays[0].selectize.destroy();
            }
            selectDays.selectize({
                plugins: ['remove_button'],
                options: allDays.map(day => ({value: day, text: day.toUpperCase()})),
                items: days,
                onChange: function(value) {
                    window.livewire.emit('daysChanged', value);
                },
            });

            $('#modal-form').find('input[name="mulai"]').val(item.mulai);
            $('#modal-form').find('input[name="selesai"]').val(item.selesai);
            $('#modal-form').find('textarea[name="keterangan"]').val(item.keterangan);
            $('#modal-form').find('input[name="status"]').val(item.status);
        });

        $('#btn-add').on('click', function () {
            const selectDays = $('#modal-form').find('select[name="days[]"]');
            if(selectDays.hasClass('selectized')) {
                selectDays[0].selectize.destroy();
            }

            let days = $('.data-container').data('allowed');
            days = Object.values(days);
            selectDays.selectize({
                plugins: ['remove_button'],
                options: days.map(day => ({value: day, text: day.toUpperCase()})),
                items: [],
                onChange: function(value) {
                    window.livewire.emit('daysChanged', value);
                },
            });
        });

        $('#modal-form').on('hidden.bs.modal', function () {
            $(this).find('input,textarea').val('');
            // destroy selectize
            const selectDays = $('#modal-form').find('select[name="days[]"]');
            if(selectDays.hasClass('selectized')) {
                selectDays[0].selectize.destroy();
            }
        });
    })
</script>
@endsection