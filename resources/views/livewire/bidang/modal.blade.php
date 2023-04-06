<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Bidang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" name="nama" id="nama" aria-describedby="namaId"
                        placeholder="Nama..." required wire:model="nama">
                    @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                    <small id="namaId" class="form-text text-muted">Masukkan nama bidang</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" form="form" wire:click="store" wire:loading.attr="disabled">Simpan</button>
            </div>
        </div>
    </div>
</div>