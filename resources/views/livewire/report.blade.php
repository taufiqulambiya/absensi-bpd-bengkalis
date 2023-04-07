<div>
    <div class="form-group mb-3">
        <label for="jenis-data">Pilih Jenis Data</label>
        <select class="form-control">
            <option value="pegawai">Pegawai</option>
            <option value="absensi">Absensi</option>
            <option value="izin">Izin</option>
            <option value="cuti">Cuti</option>
            <option value="dinas-luar">Dinas Luar</option>
        </select>
    </div>

    <div class="form-group mb-3" id="input-pegawai">
        <label for="pegawai">Pilih Pegawai</label>
        <select name="pegawai_ids" id="pegawai-select" data-label="Pegawai" multiple>
            @foreach ($pegawai as $item)
            <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group mb-3" id="input-cuti">
        <label for="jenis-cuti">Jenis Cuti</label>
        <select name="jenis_cutis" id="jenis-cuti" data-label="Jenis Cuti" multiple>
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
                <input type="date" class="form-control" id="tanggal_awal">
                @error('tanggal_awal') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group w-100">
                <label for="tanggal_akhir">Tanggal Akhir</label>
                <input type="date" class="form-control" id="tanggal_akhir">
                @error('tanggal_akhir') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <button class="btn btn-success btn-sm">
        <i class="fa fa-print" aria-hidden="true"></i> Cetak
    </button>
</div>
