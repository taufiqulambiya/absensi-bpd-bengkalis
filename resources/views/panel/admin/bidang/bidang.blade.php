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
                                        <table class="datatable table table-striped table-bordered" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    {{-- <th>Kepala Bidang</th> --}}
                                                    <th>Jumlah Staff</th>
                                                    <th>Tanggal Dibuat</th>
                                                    <th>Terakhir Diperbarui</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $item)
                                                <tr>
                                                    <th scope="row">{{$loop->iteration}}</th>
                                                    <td>{{ $item->nama }}</td>
                                                    {{-- <td>{{ $item->kabids->nama ?? '-' }}</td> --}}
                                                    <td>{{ $item->users->count() }}</td>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ $item->updated_at }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-edit"
                                                            data-toggle="modal" data-target="#modal-form"
                                                            data-item="{{ $item }}" title="Perbarui">
                                                            <i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                        <button type="button" class="btn btn-danger btn-delete" data-id="{{ $item->id }}" title="Hapus"><i
                                                                class="fa fa-times" aria-hidden="true"></i></button>
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

        <!-- Modal -->
        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Bidang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="form" method="POST" autocomplete="off">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" aria-describedby="namaId"
                                    placeholder="Nama..." required>
                                <small id="namaId" class="form-text text-muted">Masukkan nama bidang</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" form="form">Simpan</button>
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
    const bidangs = JSON.parse(`<?= json_encode($data) ?>`);
    const notAllowed = bidangs.map(v => v.nama.toLowerCase());
    const all_users = JSON.parse(`<?= json_encode($all_users) ?>`);


    $(document).ready(function () {
        $('#nama').keyup(function () {
            const val = $(this).val();
            if (notAllowed.includes(val.toLowerCase())) {
                $(this).val('');
                $(this).addClass('is-invalid');
                $(this).after('<small class="invalid-feedback">Nama sudah ada di sistem.</small>');
            } else {
                $(this).removeClass('is-invalid');
                $('.invalid-feedback').remove();
            }
        });

        $('.btn-edit').each(function () {
            $(this).click(function () { 
                const item = $(this).data('item');
                
                const inputNames = $('form').serializeArray().map(v => v.name).filter(v => v !== '_token');
                inputNames.forEach(v => {
                    if (v === 'kabid') {
                        const selectedKabid = all_users.find(k => k.id === item[v]);
                        const kabidId = item[v] || null;
                        if (kabidId) {
                            const kabidName = selectedKabid ? selectedKabid.nama : 'Pilih';
                            $(`#${v}`).prepend(`<option value="${kabidId}" id="kabid-prepend">${kabidName}</option>`);    
                        }
                    }
                    $(`#${v}`).val(item[v]);
                });
                $('form').attr('action', `${baseURL}/panel/master/bidang/${item.id}`);
                $('form').prepend(`@method('PUT')`);
                $('.modal-title').text('Edit Bidang');
            });
        });

        $('.btn-delete').each(function (_, element) {
            // element == this
            $(element).click(function () { 
                const id = $(this).data('id');
                dangerConfirmator({}, () => {
                    const URI = `${baseURL}/panel/master/bidang/${id}`;
                    const payload = {
                        _token: $('input[name=_token]').val(),
                        _method: 'DELETE',
                    }
                    $.post(URI, payload)
                        .then(res => {
                            if (res?.success) {
                                showSuccessAlert(res?.success, () => {
                                    window.location.reload();
                                });
                            }
                            if (res?.error) {
                                showErrorAlert(res?.error);
                            }
                        })
                })
            });
        });

        $('#modal-form').on('hide.bs.modal', function () {
            $('form').attr('action', `${baseURL}/panel/master/bidang`);
            $('#kabid-prepend').remove();
            $('input[name=_method]').remove();
            $('.modal-title').text('Tambah Bidang');
            $('form').trigger('reset');
        });
    }); 
</script>
@endsection