<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Dinas Luar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="form-submit" enctype="multipart/form-data">
                    @csrf
                    <div class="border p-3 mb-3">
                        <div class="form-group">
                            <label for="id_user">Pegawai</label>
                            <select class="form-control" name="id_user" id="id_user" required wire:model="form.id_user"
                                wire:change="getPegawaiInfo($event.target.value)">
                                <option value="">-- PILIH --</option>
                                @foreach ($users as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error ('form.id_user') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @if ($selectedPegawai)
                        <div id="pegawai-info">
                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="">NIP</label>
                                    <input type="text" class="form-control" id="pegawai-nip" readonly
                                        aria-describedby="helpId" placeholder="NIP..."
                                        value="{{ $selectedPegawai->nip }}">
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Golongan</label>
                                    <input type="text" class="form-control" id="pegawai-golongan" readonly
                                        aria-describedby="helpId" placeholder="Golongan..."
                                        value="{{ $selectedPegawai->golongan }}">
                                </div>
                                <div class="form-group col-4">
                                    <label for="">Jabatan</label>
                                    <input type="text" class="form-control" id="pegawai-jabatan" readonly
                                        aria-describedby="helpId" placeholder="Jabatan..."
                                        value="{{ $selectedPegawai->jabatan }} - {{ $selectedPegawai->bidangs->nama }}">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="border p-3 mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="mulai" id="mulai"
                                        min="{{ date('Y-m-d', strtotime('+1day')) }}" aria-describedby="mulaiId"
                                        placeholder="Tanggal Mulai..." required wire:model="form.mulai">
                                    @error ('form.mulai') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selesai">Tanggal Selesai</label>
                                    <input type="date" class="form-control" name="selesai" id="tgl-selesai"
                                        min="{{ date('Y-m-d', strtotime('+1day')) }}" aria-describedby="selesaiId"
                                        placeholder="Tanggal Selesai..." required wire:model="form.selesai">
                                    @error ('form.selesai') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div id="disable-dates"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="maksud">Maksud Perjalanan Dinas</label>
                        <input type="text" class="form-control" name="maksud" id="maksud" required
                            aria-describedby="maksudId" placeholder="Maksud Perjalanan Dinas..."
                            wire:model="form.maksud">
                        @error ('form.maksud') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi Dinas</label>
                        <input type="text" class="form-control" name="lokasi" id="lokasi" required
                            aria-describedby="lokasiId" placeholder="Lokasi Perjalanan Dinas (Nama Kota)..."
                            wire:model="form.lokasi">
                        @error ('form.lokasi') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan"
                            placeholder="Jelaskan lebih rinci tentang detail perjalanan" rows="3"
                            wire:model="form.keterangan"></textarea>
                        @error ('form.keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="file">Surat Jalan</label>
                        <input type="file" class="form-control-file" name="file" id="file" required
                            placeholder="Surat Jalan..." aria-describedby="fileId" wire:model="form.file">
                        @error ('form.file') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" form="form-submit" wire:click.prevent="submit"
                    wire:loading.attr="disabled">
                    <span wire:loading wire:target="submit">
                        <i class="fa fa-spinner fa-spin"></i>
                    </span>
                    <span wire:loading.remove wire:target="submit">
                        Simpan
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>