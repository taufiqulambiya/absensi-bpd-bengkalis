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
                                    <div class="col-6">
                                        {{-- <x-cuti.jatah-cuti-card /> --}}
                                        {{-- <x-cuti.jatah-cuti-card :data="$jatah_cuti" :enable-add="false" /> --}}
                                        <livewire:cuti.jatah-cuti-card :data="$jatah_cuti" :enable-add="false" />
                                    </div>
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

        @if (session('user')->level == 'pegawai')
        <div class="modal" id="modal-form" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah pengajuan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @csrf
                        @if (count($not_allowed) > 0)
                        <div class="not-allowed mb-3">
                            <span class="d-block text-danger">Harap pilih selain dari tanggal berikut:</span>
                            <div class="d-flex flex-wrap chips" style="gap: 4px">
                                @foreach ($not_allowed as $item)
                                <span class="chip bg-secondary text-white">{{$item}}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="jenis">Jenis Cuti</label>
                            <select class="form-control" name="jenis" id="jcf-selector">
                                <option value="1" data-value="{{ $jatah_cuti_tahunan }}">Cuti Tahunan</option>
                                <option value="2" data-value="{{ $jatah_cuti_besar }}">Cuti Besar</option>
                                <option value="3" data-value="{{ $jatah_cuti_melahirkan }}">Cuti Melahirkan
                                </option>
                                <option value="4" data-value="{{ $jatah_cuti_penting }}">Cuti Karena Alasan
                                    Penting</option>
                                <option value="5" data-value="{{ $jatah_cuti_ctln }}">Cuti Diluar
                                    Tanggungan Negara</option>
                            </select>
                            <span class="d-block font-weight-bold">Jatah tersisa : <span id="jcf-value">{{
                                    $jatah_cuti_tahunan }}</span></span>
                        </div>
                        <div class="p-2 form-group">
                            <label for="ctmdp">Pilih Tanggal</label>
                            <div class="row justify-content-center">
                                <div class="col-md-6 col-sm-12">
                                    <div id="ctmdp"></div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal-selector" aria-describedby="tanggal"
                                min="{{ date('Y-m-d', strtotime('+1day')) }}"
                                max="{{ date('Y-m-d', strtotime('12/31')) }}" placeholder="">
                            <div class="d-flex flex-wrap chips mt-3" id="tanggal-list" style="gap: 4px">
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" id="keterangan" required>
                        </div>
                        <div class="form-group">
                            <label for="bukti">Bukti</label>
                            <input type="file" class="form-control-file" id="bukti" name="bukti" required>
                            <span class="text-info" id="bukti-helper"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="submit-cuti">Ajukan</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <x-modal.delete id="modal-delete" title="Hapus data ini?"
            desc="Tindakan ini tidak bisa dibatalkan. Lanjutkan menghapus?" />
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
@endsection