<div class="modal" id="modal-form" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="store">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" name="nama" id="nama" aria-describedby="nama"
                            placeholder="Nama..." wire:model="form.nama">
                        @error('form.nama') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="jk">Jenis Kelamin</label>
                        <select name="jk" id="jk" class="form-control" wire:model="form.jk">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        @error('form.jk') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir"
                            aria-describedby="tgl_lahir" placeholder="Tanggal lahir..." wire:model="form.tgl_lahir">
                        @error('form.tgl_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" class="form-control" name="nip" id="nip" aria-describedby="nip"
                            placeholder="NIP..." wire:model="form.nip">
                        @error('form.nip') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="golongan">Golongan</label>
                        <input type="text" class="form-control" name="golongan" id="golongan"
                            aria-describedby="golongan" placeholder="Golongan..." wire:model="form.golongan">
                        @error('form.golongan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <select class="form-control" name="jabatan" id="jabatan" wire:model="form.jabatan">
                            <option value="">Pilih</option>
                            <option value="Kabid">Kabid</option>
                            <option value="Kasubbid">Kasubbid</option>
                            <option value="Subbag">Subbag</option>
                            <option value="Kasubbag">Kasubbag</option>
                            <option value="Kasi">Kasi</option>
                            <option value="Sekretaris">Sekretaris</option>
                            <option value="Staff">Staff</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @if ($form['jabatan'] == "Lainnya")
                        <input type="text" class="form-control mt-2" name="jabatan_lainnya" id="jabatan_lainnya"
                            aria-describedby="jabatan_lainnya" placeholder="Jabatan lainnya..."
                            wire:model="form.jabatan_lainnya">
                        @endif
                        @error('form.jabatan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="bidang">Bidang</label>
                        <select class="form-control" name="bidang" id="bidang" wire:model="form.bidang">
                            <option value="">Pilih</option>
                            @foreach (DB::table('tb_bidang')->get() as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        @error('form.bidang') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" name="alamat" id="alamat" aria-describedby="alamat"
                            placeholder="Alamat..." wire:model="form.alamat"></textarea>
                        @error('form.alamat') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="no_telp">No Telepon</label>
                        <input type="text" class="form-control" name="no_telp" id="no_telp" aria-describedby="no_telp"
                            placeholder="No telepon..." wire:model="form.no_telp">
                        @error('form.no_telp') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <select class="form-control" name="level" id="level" wire:model="form.level">
                            <option value="pegawai">Pegawai</option>
                            <option value="kabid">Kabid</option>
                            <option value="admin">Admin</option>
                            <option value="atasan">Pimpinan</option>
                        </select>
                        @error('form.level') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>