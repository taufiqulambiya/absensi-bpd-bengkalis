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
                    <div class="col-md-12 col-sm-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>@yield('title') sesuai Bidang Anda</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="row">
                                    <div class="col-12">
                                        <table id="datatable" class="table datatable table-striped table-bordered"
                                            style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>NIP</th>
                                                    <th>Golongan</th>
                                                    <th>Jabatan</th>
                                                    <th>Bidang</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>Alamat</th>
                                                    <th>Level</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $item)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $item->nama }}</td>
                                                    <td>{{ $item->nip }}</td>
                                                    <td>{{ $item->golongan }}</td>
                                                    <td>{{ $item->jabatan ?? '-' }}</td>
                                                    <td>{{ $item->bidangs->nama ?? '-' }}</td>
                                                    <td>{{ date_format(date_create($item->tgl_lahir), 'd/m/Y') }}</td>
                                                    <td>{{ $item->alamat }}</td>
                                                    <td>{{ strtoupper($item->level) }}</td>
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