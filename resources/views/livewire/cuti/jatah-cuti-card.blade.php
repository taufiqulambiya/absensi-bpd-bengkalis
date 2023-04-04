<div class="card mb-3 jctc">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <div class="form-group">
                        <label for="jenis-jatah">Pilih Jenis Jatah</label>
                        <select name="" id="jenis-jatah" class="form-control" wire:model="show">
                            <option value="tahunan" data-value="{{ $data['tahunan'] }}">
                                Cuti Tahunan</option>
                            <option value="besar" data-value="{{ $data['besar'] }}">Cuti
                                Besar</option>
                            <option value="melahirkan" data-value="{{ $data['melahirkan'] }}">Cuti Melahirkan</option>
                            <option value="penting" data-value="{{ $data['penting'] }}">Cuti Alasan Penting</option>
                            <option value="ctln" data-value="{{ $data['ctln'] }}">Cuti Diluar Tanggungan Negara</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="card-title">Jatah Cuti Tersisa <span id="jtct-label">TAHUNAN</span> - {{date('Y')}}</h4>
            </div>
        </div>
        <hr>
        <span class="display-4" id="jtct-value">{{$data[$show]}}</span>
        @if ($level == 'pegawai')
        <hr>
        @if ($enableAdd)
        <button class="btn btn-secondary mb-3 disabled" style="cursor: not-allowed"
            onclick="showErrorAlert('Masih ada pengajuan belum/sedang diproses.')"><i class="fas fa-plus"></i>
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