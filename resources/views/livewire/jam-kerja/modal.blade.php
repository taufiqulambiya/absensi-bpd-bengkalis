<div class="modal" id="modal-form" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Jam Kerja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group d-flex flex-column" wire:ignore>
                    <label for="days">Hari</label>
                    <select name="days[]" id="days-select" style="width: 100%" multiple wire:model="form.days">
                        {{-- @foreach ($allowed as $item)
                        <option value="{{ $item }}">{{ strtoupper($item) }}</option>
                        @endforeach --}}
                    </select>
                    @error('form.days') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="d-flex">
                    <div class="form-group" style="flex-grow: 1">
                        <label for="mulai">Jam Mulai</label>
                        <input type="time" class="form-control" name="mulai" wire:model="form.mulai">
                        @error('form.mulai') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group" style="flex-grow: 1">
                        <label for="selesai">Jam Berakhir</label>
                        <input type="time" class="form-control" name="selesai" wire:model="form.selesai">
                        @error('form.selesai') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" cols="10" rows="5" class="form-control" placeholder="Keterangan"
                        wire:model="form.keterangan"></textarea>
                    @error('form.keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <input type="checkbox" name="status" class="form-control-checkbox" id="status"
                        wire:model="form.status" {{ $form['status']=='aktif' ? 'checked' : '' }}>
                    <label for="status">Aktif</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" wire:click="submit" wire:loading.attr="disabled">
                    <span wire:loading wire:target="submit" class="spinner-border spinner-border-sm" role="status"
                        aria-hidden="true"></span>
                    Simpan
                </button>
            </div>
        </div>
    </div>

</div>