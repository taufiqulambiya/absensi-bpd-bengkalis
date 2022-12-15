@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<style>
    /* #report-content .form-control.multiselect {
        height: 100% !important;
        border-radius: 4px;
        border: 1px solid rgba(0,0,0,0.3);
    } */
</style>
<div class="container body">
    <div class="main_container">
        @include('layouts.sidebar')
        @include('layouts.topbar')

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
                    <div class="col-md-12 col-sm-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Export @yield('title')</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content" id="report-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                          <label for="jenis-data">Pilih Jenis-data Data</label>
                                          <select class="form-control" name="jenis-data" id="jenis-data">
                                            <option value="pegawai">Pegawai</option>
                                            <option value="absensi">Absensi</option>
                                            <option value="izin">Izin</option>
                                            <option value="cuti">Cuti</option>
                                            <option value="dinas-luar">Dinas Luar</option>
                                          </select>
                                        </div>

                                        <div id="options">
                                            <div id="pegawai" class="mb-3">
                                                <div class="form-group">
                                                  <label for="pegawai">Pilih Pegawai</label>
                                                  <select class="form-control multiselect" name="pegawai[]" id="pegawai-select" multiple data-label="Pegawai" data-item="pegawai">
                                                    @foreach (DB::table('users')->where('level', 'pegawai')->get() as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                            </div>

                                            <div id="absensi" class="mb-3">
                                                <div class="form-group">
                                                  <label for="absensi-pegawai">Pilih Pegawai</label>
                                                  <select class="form-control multiselect" name="absensi-pegawai[]" id="absensi-pegawai" multiple data-label="Pegawai" data-item="pegawai">
                                                    @foreach (DB::table('users')->where('level', 'pegawai')->get() as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                  </select>
                                                </div>

                                                <div class="form-group">
                                                  <label for="range-absensi">Range Tanggal</label>
                                                  <input type="text"
                                                    class="form-control drPicker" name="range-absensi" id="range-absensi" aria-describedby="helpId" placeholder="Range Tanggal...">
                                                </div>
                                            </div>
                                            
                                            <div id="izin" class="mb-3">
                                                <div class="form-group">
                                                  <label for="izin-pegawai">Pilih Pegawai</label>
                                                  <select class="form-control multiselect" name="izin-pegawai[]" id="izin-pegawai" multiple data-label="Pegawai" data-item="pegawai">
                                                    @foreach (DB::table('users')->where('level', 'pegawai')->get() as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                  </select>
                                                </div>

                                                <div class="form-group">
                                                  <label for="range-izin">Range Tanggal</label>
                                                  <input type="text"
                                                    class="form-control drPicker" name="range-izin" id="range-izin" placeholder="Range Tanggal...">
                                                </div>

                                                <div class="form-group">
                                                  <label for="jenis-izin">Jenis</label>
                                                  <select name="jenis-izin[]" id="jenis-izin" class="form-control multiselect" multiple data-label="Jenis Izin">
                                                    <option value="Sakit">Sakit</option>
                                                    <option value="Urusan Keluarga">Urusan Keluarga</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                  </select>
                                                </div>
                                            </div>

                                            <div id="cuti" class="mb-3">
                                                <div class="form-group">
                                                  <label for="cuti-pegawai">Pilih Pegawai</label>
                                                  <select class="form-control multiselect" name="cuti-pegawai[]" id="cuti-pegawai" multiple data-label="Pegawai" data-item="pegawai">
                                                    @foreach (DB::table('users')->where('level', 'pegawai')->get() as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                  </select>
                                                </div>

                                                <div class="form-group">
                                                  <label for="range-cuti">Range Tanggal</label>
                                                  <input type="text"
                                                    class="form-control drPicker" name="range-cuti" id="range-cuti" placeholder="Range Tanggal...">
                                                </div>

                                                <div class="form-group">
                                                  <label for="jenis-cuti">Jenis</label>
                                                  <select name="jenis-cuti" id="jenis-cuti" class="form-control multiselect" data-label="Jenis Cuti" multiple>
                                                    <option value="tahunan">Cuti Tahunan</option>
                                                    <option value="besar">Cuti Besar</option>
                                                    <option value="melahirkan">Cuti Melahirkan</option>
                                                    <option value="penting">Cuti Alasan Penting</option>
                                                    <option value="ctln">Cuti Diluar Tanggungan Negara</option>
                                                  </select>
                                                </div>
                                            </div>

                                            <div id="dinas-luar" class="mb-3">
                                                <div class="form-group">
                                                    <label for="dinas-luar-pegawai">Pilih Pegawai</label>
                                                    <select class="form-control multiselect" name="dinas-luar-pegawai[]" id="dinas-luar-pegawai" multiple data-label="Pegawai" data-item="pegawai">
                                                      @foreach (DB::table('users')->where('level', 'pegawai')->get() as $item)
                                                          <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                      @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="range-dinas-luar">Range Tanggal</label>
                                                    <input type="text"
                                                      class="form-control drPicker" name="range-dinas-luar" id="range-dinas-luar" placeholder="Range Tanggal...">
                                                </div>
                                            </div>

                                            <button class="btn btn-success btn-sm" id="btn-print">
                                                <i class="fa fa-print" aria-hidden="true"></i> Cetak
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/report.js') }}"></script>
<script>
    $(document).ready(function() {
        const report = new Report();

        $('#jenis-data').change(function() {
            const selectedId = $(this).val();
            report.showId(selectedId);
        });

        $('#btn-print').click(function() {
            report.print();
        });
    });
</script>
@endsection