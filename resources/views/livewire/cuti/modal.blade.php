<div class="modal" id="modal-form" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @csrf
                @if (count($disableDates) > 0)
                <div class="not-allowed mb-3">
                    <span class="d-block text-danger">Harap pilih selain dari tanggal berikut:</span>
                    <div class="d-flex flex-wrap chips" style="gap: 4px">
                        @foreach ($disableDates as $item)
                        <span class="chip bg-secondary text-white">{{$item}}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <label for="jenis">Jenis Cuti</label>
                    <select class="form-control" name="jenis" wire:model="form.jenis"
                        wire:change="changeJenis($event.target.value)">
                        <option value="tahunan" {{ $form['jenis']=='tahunan' ? 'selected' : '' }}>
                            Cuti Tahunan</option>
                        <option value="besar" {{ $form['jenis']=='besar' ? 'selected' : '' }}>
                            Cuti Besar</option>
                        <option value="melahirkan" {{ $form['jenis']=='melahirkan' ? 'selected' : '' }}>
                            Cuti Melahirkan
                        </option>
                        <option value="penting" {{ $form['jenis']=='penting' ? 'selected' : '' }}>
                            Cuti Karena Alasan Penting</option>
                        <option value="ctln" {{ $form['jenis']=='ctln' ? 'selected' : '' }}>Cuti Diluar
                            Tanggungan Negara</option>
                    </select>
                    {{-- <span class="d-block font-weight-bold">Jatah tersisa : <span id="jcf-value">{{
                            $jatahCuti['tahunan'] }}</span></span> --}}
                    <span class="d-block font-weight-bold">Jatah tersisa : <span id="jatah-cuti-value">{{ $jatahCutiView
                            }}</span></span>
                    @error('form.jenis') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="p-2 form-group">
                    <label for="ctmdp">Pilih Tanggal</label>
                    <div id="ctmdp" wire:ignore></div>
                    <input type="hidden" id="tanggal" name="tanggal" wire:model="form.tanggal">
                    @error('form.tanggal') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
                {{-- <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal-selector" aria-describedby="tanggal"
                        min="{{ date('Y-m-d', strtotime('+1day')) }}" max="{{ date('Y-m-d', strtotime('12/31')) }}"
                        placeholder="">
                    <div class="d-flex flex-wrap chips mt-3" id="tanggal-list" style="gap: 4px">
                    </div>
                </div> --}}
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" id="keterangan" required
                        wire:model="form.keterangan">
                    @error('form.keterangan') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label for="bukti">Bukti</label>
                    <input type="file" class="form-control-file" id="bukti" name="bukti" required
                        wire:model="form.bukti">
                    {{-- <span class="text-info" id="bukti-helper"></span> --}}
                    {{-- show error when there is an error in validating --}}
                    @error('form.bukti') <span class="error text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" wire:click="submit" wire:loading.attr="disabled">
                    @if($isEdit) Ubah @else Simpan @endif
                </button>
            </div>
        </div>
    </div>
</div>