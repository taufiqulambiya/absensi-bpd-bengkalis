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

<div class="data-container" data-pegawai="{{ DB::table('users')->where('level', 'pegawai')->get() }}"></div>

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
                      <label for="jenis-data">Pilih Jenis Data</label>
                      <select class="form-control" id="jenis-data" data-label="Jenis Data" name="jenis">
                        <option value="pegawai">Pegawai</option>
                        <option value="absensi">Absensi</option>
                        <option value="izin">Izin</option>
                        <option value="cuti">Cuti</option>
                        <option value="dinas-luar">Dinas Luar</option>
                      </select>
                    </div>

                    <div class="form-group mb-3" id="input-pegawai">
                      <label for="pegawai">Pilih Pegawai</label>
                      <select name="pegawai" id="pegawai-select" data-label="Pegawai" multiple data-name="pegawai">
                        @foreach ($pegawai as $item)
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group mb-3" id="input-cuti">
                      <label for="jenis-cuti">Jenis Cuti</label>
                      <select name="jenis_cuti" id="jenis-cuti" data-label="Jenis Cuti" multiple data-name="jenis_cuti">
                        <option value="tahunan">Cuti Tahunan</option>
                        <option value="besar">Cuti Besar</option>
                        <option value="melahirkan">Cuti Melahirkan</option>
                        <option value="penting">Cuti Alasan Penting</option>
                        <option value="ctln">Cuti Diluar Tanggungan Negara</option>
                      </select>
                    </div>

                    <div class="section mb-3" id="input-rentang">
                      <h4 class="text-muted">Pilih Rentang</h4>
                      <div class="d-flex gap-3 w-100">
                        <div class="form-group w-100">
                          <label for="tanggal_awal">Tanggal Awal</label>
                          <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal">
                        </div>
                        <div class="form-group w-100">
                          <label for="tanggal_akhir">Tanggal Akhir</label>
                          <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir">
                        </div>
                      </div>
                    </div>

                    <button class="btn btn-success btn-sm" id="btn-cetak">
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
<script>
  // function renderSelectize() {
  //   $('select[multiple]').selectize({
  //       plugins: ['remove_button'],
  //       delimiter: ',',
  //       persist: false,
  //       create: function(input) {
  //           return {
  //               value: input,
  //               text: input
  //           }
  //       },
  //       onChange: function(value) {
  //         const label = $(this.$input).data('label');
  //         const text = value ? value.split(',').join(', ') : 'Semua ' + label;
  //         $(this.$input).parent().find('.item').text(text);
  //       }
  //   });
  // }

  // function changeType(type = 'pegawai') {
  //   const all = ['input-pegawai', 'input-rentang', 'input-cuti'];
  //   const showInputs = {
  //     pegawai: ['input-pegawai'],
  //     absensi: ['input-pegawai', 'input-rentang'],
  //     izin: ['input-pegawai', 'input-rentang'],
  //     cuti: ['input-pegawai', 'input-rentang','input-cuti'],
  //     'dinas-luar' : ['input-pegawai', 'input-rentang'],
  //   }

  //   const show = showInputs[type];
  //   const hide = all.filter(item => !show.includes(item));

  //   show.forEach(item => {
  //     $(`#${item}`).show();
  //   });

  //   hide.forEach(item => {
  //     $(`#${item}`).hide();
  //   });
  // }

  // window.onload = function() {
  //   renderSelectize();
  //   changeType();

  //   $('#jenis-data').on('change', function() {
  //     changeType($(this).val());
  //   });

  //   $('#btn-cetak').on('click', function() {
  //     const jenisData = $('#jenis-data').val();
  //     const pegawai = $('#pegawai-select').val();
  //     const tanggalAwal = $('#tanggal_awal').val();
  //     const tanggalAkhir = $('#tanggal_akhir').val();
  //     const jenisCuti = $('#jenis-cuti').val();

  //     const url = `{{ route('report.index') }}?jenis=${jenisData}`;
  //     const params = {
  //       pegawai_ids: pegawai,
  //       tanggal_awal: tanggalAwal,
  //       tanggal_akhir: tanggalAkhir,
  //       jenis_cutis: jenisCuti,
  //     };

  //     const queryString = Object.keys(params)
  //       .map(key => key + '=' + params[key])
  //       .join('&');
      
  //     window.open(`${url}&${queryString}`, '_blank');
  //   });
  // }

  class Report {
    constructor() {
      this.data = {}
      this.renderSelectize();
      this.changeJenisData();
      this.jenis = 'pegawai';
    }

    renderSelectize = () => {
      $('select[multiple]').each((index, item) => {
        const $item = $(item);
        const name = $item.data('name');
        const selectize = $item.selectize({
          plugins: ['remove_button'],
          delimiter: ',',
          persist: false,
          create: function(input) {
              return {
                  value: input,
                  text: input
              }
          },
          onChange: (value) => {
            this.data[name] = value;
          }
        });
      });
    }

    changeJenisData(type = 'pegawai') {
      const all = ['input-pegawai', 'input-rentang', 'input-cuti'];
      const showInputs = {
        pegawai: ['input-pegawai'],
        absensi: ['input-pegawai', 'input-rentang'],
        izin: ['input-pegawai', 'input-rentang'],
        cuti: ['input-pegawai', 'input-rentang','input-cuti'],
        'dinas-luar' : ['input-pegawai', 'input-rentang'],
      }

      const show = showInputs[type];
      const hide = all.filter(item => !show.includes(item));

      show.forEach(item => {
        $(`#${item}`).show();
      });

      hide.forEach(item => {
        $(`#${item}`).hide();
      });

      this.jenis = type;
    }

    print() {
      const tglAwal = $('#tanggal_awal').val();
      const tglAkhir = $('#tanggal_akhir').val();

      if (tglAwal) {
        this.data.tanggal_awal = tglAwal;
      }
      if (tglAkhir) {
        this.data.tanggal_akhir = tglAkhir;
      }

      const params = this.data;
      params.jenis = this.jenis;
      
      const queryString = window.Qs.stringify(params);

      window.open(`{{ route('report.index') }}?${queryString}`, '_blank');
    }
  }

  window.onload = function() {
    const report = new Report();

    $('#jenis-data').on('change', function() {
      report.changeJenisData($(this).val());
    });
   
    $('#btn-cetak').on('click', function() {
      report.print();
    });
  }
</script>
@endsection