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
                                        <button class="btn btn-primary btn-add" data-toggle="modal" data-target="#modal-form"><i
                                                class="fas fa-plus"></i> Tambah Pengguna</button>
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        <table id="datatable" class="table table-striped table-bordered"
                                            style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>NIP</th>
                                                    <th>Golongan</th>
                                                    <th>Jabatan</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>Alamat</th>
                                                    <th>Level</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->nama }}</td>
                                                    <td>{{ $item->nip }}</td>
                                                    <td>{{ $item->golongan }}</td>
                                                    <td>{{ $item->bidang->nama ?? '-' }}</td>
                                                    <td>{{ date_format(date_create($item->tgl_lahir), 'd/m/Y') }}</td>
                                                    <td>{{ $item->alamat }}</td>
                                                    <td>{{ strtoupper($item->level) }}</td>
                                                    <td>
                                                        <button class="btn btn-info btn-edit" data-toggle="modal" data-target="#modal-form" data-item="{{ $item }}" title="Ubah pengajuan">
                                                            <span class="fas fa-pencil"></span>
                                                        </button>
                                                        <button class="btn btn-danger" title="Batalkan pengajuan">
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
                        <h5 class="modal-title">Tambah Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" aria-describedby="nama"
                                    placeholder="Nama..." required>
                            </div>
                            <div class="form-group">
                                <label for="jk">Jenis Kelamin</label>
                                <select name="jk" id="jk" class="form-control">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tgl_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir"
                                    aria-describedby="tgl_lahir" placeholder="Tanggal lahir..." required max="{{ date('Y-m-d', strtotime('-20 year')) }}">
                            </div>
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" class="form-control" onkeyup="event.target.value = event.target.value.replace(/[^0-9.]/g, '')" name="nip" id="nip" aria-describedby="nip"
                                    placeholder="NIP..." required>
                            </div>
                            <div class="form-group">
                                <label for="golongan">Golongan</label>
                                <input type="text" class="form-control" name="golongan" id="golongan"
                                    aria-describedby="golongan" placeholder="Golongan...">
                            </div>
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <select class="form-control" name="jabatan" id="jabatan" required>
                                    @foreach (DB::table('tb_bidang')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" name="alamat" id="alamat" aria-describedby="alamat"
                                    placeholder="Alamat..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="no_telp">No Telepon</label>
                                <input type="text" class="form-control" onkeyup="event.target.value = event.target.value.replace(/[^0-9.]/g, '')" name="no_telp" id="no_telp" aria-describedby="no_telp"
                                    placeholder="No telepon..." required>
                            </div>
                            <div class="form-group">
                                <label for="level">Level</label>
                                <select class="form-control" name="level" id="level" required>
                                    <option value="pegawai">Pegawai</option>
                                    <option value="kabid">Kabid</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <div id="level-message"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
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

<script>
    $('.btn-edit').click(function() {
        const item = $(this).data('item');
        item.bidang = item.bidang?.id;
        $('input:not([name=_token])').each(function() {
            const name = $(this).attr('name');
            $(this).val(item[name]);
        })
        $('textarea').each(function() {
            const name = $(this).attr('name');
            $(this).val(item[name]);
        })
        $('select').each(function() {
            const name = $(this).attr('name');
            $(this).val(item[name]);
        })
    })
    $('.btn-add').click(function() {
        $('input:not([name=_token])').each(function() {
            $(this).val('');
        })
        $('textarea').each(function() {
            $(this).val('');
        })
        $('select').each(function() {
            $(this).val('');
        })
    })
    $('#level').on('change', function(){
        if ($(this).val() === 'kabid') {
            const jabatanValue = $('#jabatan option:selected').text();
            $('#level-message').html(`<b class='text-info'>Sebagai kepala bidang untuk <label for='jabatan'>${jabatanValue}</label></b>`);
        } else {
            $('#level-message').html();
        }
    })
</script>
@endsection