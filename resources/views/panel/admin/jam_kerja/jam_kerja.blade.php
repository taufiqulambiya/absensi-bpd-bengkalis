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
                                        <table class="datatable table table-striped table-bordered" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Hari</th>
                                                    <th>Jam Mulai</th>
                                                    <th>Jam Akhir</th>
                                                    <th>Keterangan</th>
                                                    <th>Status</th>
                                                    <th>Tanggal Dibuat</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $item)
                                                <tr>
                                                    <th scope="row">{{$loop->iteration}}</th>
                                                    <td>{{ strtoupper($item->days) }}</td>
                                                    <td>{{ explode(':', $item->mulai)[0].':'.explode(':',
                                                        $item->mulai)[1] }}</td>
                                                    <td>{{ explode(':', $item->selesai)[0].':'.explode(':',
                                                        $item->selesai)[1] }}</td>
                                                    <td>{{$item->keterangan}}</td>
                                                    <td>
                                                        {{-- <form action="{{ route('jam_kerja.update', $item->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="update_status" value="1">
                                                            <input type="hidden" name="status"
                                                                value="{{ $item->status == 'aktif' ? 'off' : 'on' }}">
                                                            <label for="edit-submit{{ $item->id }}" class="text-primary"
                                                                style="cursor: pointer">{{ $item->status == 'aktif' ?
                                                                'nonaktifkan' : 'aktifkan' }}</label>
                                                            <button type="submit" id="edit-submit{{ $item->id }}"
                                                                class="d-none" title="Aktifkan atau Nonaktifkan">
                                                            </button>
                                                        </form> --}}

                                                        {{-- update: using switch component --}}
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input switch-status"
                                                                id="customSwitch{{ $item->id }}"
                                                                data-id="{{ $item->id }}"
                                                                {{ $item->status == 'aktif' ? 'checked' : '' }}>
                                                            <label class="custom-control-label"
                                                                for="customSwitch{{ $item->id }}"></label>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        {{$item->created_at}}
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-info btn-edit" data-toggle="modal"
                                                            data-target="#modal-form" title="Ubah data"
                                                            data-item='{{ $item }}'>
                                                            <span class="fas fa-pencil"></span>
                                                        </button>

                                                        <button type="submit" class="btn btn-danger btn-delete" data-id="{{$item->id}}" title="Hapus data">
                                                            <span class="fas fa-times"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- modals --}}
        <div class="modal" id="modal-form" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Jam Kerja</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('jam_kerja.store') }}" method="POST" autocomplete="off" id="form-add">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group d-flex flex-column">
                                <label for="days">Hari</label>
                                <select name="days[]" style="width: 100%" class="form-control" multiple>
                                    @foreach ($allowed as $item)
                                    <option value="{{ $item }}">{{ strtoupper($item) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex">
                                <div class="form-group" style="flex-grow: 1">
                                    <label for="mulai">Jam Mulai</label>
                                    <input type="time" class="form-control" name="mulai">
                                </div>
                                <div class="form-group" style="flex-grow: 1">
                                    <label for="selesai">Jam Berakhir</label>
                                    <input type="time" class="form-control" name="selesai">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" cols="10" rows="5" class="form-control"
                                    placeholder="Keterangan"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="status" class="form-control-checkbox">
                                <label for="status">Aktif</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="btn-submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

@if(false)
<script>
    const initAllowedDays = JSON.parse(`<?= json_encode($allowed) ?>`);
    const days = JSON.parse(`<?= json_encode($days) ?>`);
    const data = JSON.parse(`<?= json_encode($data) ?>`);
    console.log(data);

    // $(document).ready(function(){
    //     function validateTime() {
    //         const arrayTime = [];
    //         $("input.timepicker").each(function() {
    //             arrayTime.push(parseInt($(this).val().replace(":", ""), 10));
    //         })
    //         const isValid = arrayTime[0] < arrayTime[1];
    //     }

    //     $("#btn-submit").on("click", validateTime);
    // });

    $(function () {
        function removeMs(time) {
            return `${time.toString().split(':')[0]}:${time.toString().split(':')[1]}`;
        }
        function resetForm() {
            $('input[name=mulai]').val('');
            $('input[name=selesai]').val('');
            $('textarea[name=keterangan]').val('');
            $('input[name=status]').attr('checked', false);
            $('#days').html(
                initAllowedDays.map(v => `<option value="${v}">${v.toUpperCase()}</option>`).join('')
            );
        }
        function fillEdit() {
            const item = $(this).data('item');
            const itemsExceptCurrent = data.filter(v => v.id !== item.id);
            const itemsExceptDays = itemsExceptCurrent.map(v => v.days.split(', ')).flat();
            const allowedDays = days.filter(v => !itemsExceptDays.some(x => x === v));
            console.log(allowedDays);

            $('input[name=mulai]').val(removeMs(item.mulai));
            $('input[name=selesai]').val(removeMs(item.selesai));
            $('textarea[name=keterangan]').val(item.keterangan);
            $('input[name=status]').attr('checked', item.status === 'aktif' ? true : false);
            $('#days').html(
                allowedDays.map(v => `<option value="${v}">${v.toUpperCase()}</option>`).join('')
            );
            $('#days').val(item.days.split(', '));

            $('#form-add').append(`@method('PUT')`);
            $('#form-add').attr('action', `jam_kerja/${item.id}`);
        }
        $('.btn-edit').each(function () {
            $(this).click(fillEdit);
        })
        $('#btn-add').click(resetForm);

        $('#days').select2();
    })
</script>
@endif
@endsection