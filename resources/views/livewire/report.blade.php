<div>
    <div class="form-group mb-3">
        <label for="jenis-data">Pilih Jenis Data</label>
        <select class="form-control" wire:model="jenis" wire:change="$emit('initJS')">
            <option value="pegawai">Pegawai</option>
            <option value="absensi">Absensi</option>
            <option value="izin">Izin</option>
            <option value="cuti">Cuti</option>
            <option value="dinas-luar">Dinas Luar</option>
        </select>
    </div>


    <div class="form-group mb-3 @if ($jenis != 'pegawai') d-none @endif" wire:ignore>
        <label for="pegawai">Pilih Pegawai</label>
        <select name="pegawai" id="pegawai-select" data-label="Pegawai" multiple>
            @foreach ($pegawai as $item)
            <option value="{{ $item->id }}">{{ $item->nama }}</option>
            @endforeach
        </select>
    </div>

    @if ($jenis == 'cuti')
    <div>
        <div class="form-group mb-3">
            <label for="jenis-cuti">Jenis Cuti</label>
            <select name="jenis-cuti" id="jenis-cuti" data-label="Jenis Cuti" multiple>
                <option value="tahunan">Cuti Tahunan</option>
                <option value="besar">Cuti Besar</option>
                <option value="melahirkan">Cuti Melahirkan</option>
                <option value="penting">Cuti Alasan Penting</option>
                <option value="ctln">Cuti Diluar Tanggungan Negara</option>
            </select>
        </div>
        <script>
            $(document).ready(function() {
                $('#jenis-cuti').selectize({
                    plugins: ['remove_button'],
                    delimiter: ',',
                    persist: false,
                    create: function(input) {
                        return {
                            value: input,
                            text: input
                        }
                    },
                    onChange: function(value) {
                        @this.emit('setJenisCutis', value);
                    }
                });
            });
        </script>
    </div>
    @endif

    @if ($jenis == 'absensi' OR $jenis == 'izin' OR $jenis == 'cuti' OR $jenis == 'dinas-luar')
    <div class="section mb-3">
        <h4 class="text-muted">Pilih Rentang</h4>
        <div class="d-flex gap-3 w-100">
            <div class="form-group w-100">
                <label for="tanggal_awal">Tanggal Awal</label>
                <input type="date" class="form-control" wire:model="tanggal_awal">
                @error('tanggal_awal') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group w-100">
                <label for="tanggal_akhir">Tanggal Akhir</label>
                <input type="date" class="form-control" wire:model="tanggal_akhir">
                @error('tanggal_akhir') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    @endif

    <button class="btn btn-success btn-sm" wire:click="print" target="_blank">
        <i class="fa fa-print" aria-hidden="true"></i> Cetak
    </button>
</div>