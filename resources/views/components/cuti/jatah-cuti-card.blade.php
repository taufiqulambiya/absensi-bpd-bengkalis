<div class="card mb-3 jctc">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <div class="form-group">
                        <label for="jenis-jatah">Pilih Jenis Jatah</label>
                        <select name="" id="jenis-jatah" class="form-control">
                            <option value="jatah_cuti_tahunan" data-label="TAHUNAN"
                                data-value="{{ $data->jatah_cuti_tahunan }}">Cuti Tahunan</option>
                            <option value="jatah_cuti_besar" data-label="BESAR"
                                data-value="{{ $data->jatah_cuti_besar }}">Cuti
                                Besar</option>
                            <option value="jatah_cuti_melahirkan" data-label="MELAHIRKAN"
                                data-value="{{ $data->jatah_cuti_melahirkan }}">Cuti Melahirkan</option>
                            <option value="jatah_cuti_penting" data-label="ALASAN PENTING"
                                data-value="{{ $data->jatah_cuti_penting }}">Cuti Alasan Penting</option>
                            <option value="jatah_cuti_ctln" data-label="DILUAR TANGGUNGAN NEGARA"
                                data-value="{{ $data->jatah_cuti_ctln }}">Cuti Diluar Tanggungan Negara</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="card-title">Jatah Cuti Tersisa <span id="jtct-label">TAHUNAN</span> - {{date('Y')}}</h4>
            </div>
        </div>
        <hr>
        <span class="display-4" id="jtct-value">{{$data->jatah_cuti_tahunan}}</span>
        @if ($level == 'pegawai')
        <hr>
        @if ($data->is_waiting OR $data->has_cuti)
        <button class="btn btn-secondary mb-3 disabled" style="cursor: not-allowed" @if ($data->has_cuti)
            onclick="showErrorAlert('Masih ada pengajuan yang aktif.')" @else
            onclick="showErrorAlert('Masih ada pengajuan belum/sedang diproses.')" @endif><i class="fas fa-plus"></i>
            Ajukan Cuti</button>
        @else
        <button class="btn btn-primary mb-3" id="btn-add" data-toggle="modal" data-target="#modal-form"><i
                class="fas fa-plus"></i>
            <?= $level == 'admin' ? 'Tambahkan' : 'Ajukan' ?> Cuti
        </button>
        @endif
        @endif
    </div>
</div>

<script>
    class JatahCuti {
        constructor() {
            $('#jenis-jatah').change(this.handleChangeJenis);
        }

        handleChangeJenis(e) {
            const name = $(this).val();
            const value = $(this).find('option:selected').data('value');
            const label = $(this).find('option:selected').data('label');
            $('#jtct-label').text(label);
            $('#jtct-value').text(value);
        }
    }

    new JatahCuti();
</script>