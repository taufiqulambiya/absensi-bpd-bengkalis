<div class="modal" id="modal-form" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if (count($disableDates) > 0)
            <div class="disable-dates m-3 p-2 border" style="max-height: 60px;overflow:scroll;">
                <span class="d-block text-danger mb-2">Harap pilih range diluar dari tanggal berikut:</span>
                <div class="chips" style="gap: 4px">
                    @foreach ($disableDates as $item)
                    <span class="chip">{{$item}}</span>
                    @endforeach
                </div>
            </div>
            @endif


            <form enctype="multipart/form-data" wire:submit.prevent="submit">
                {{-- @csrf --}}
                {{-- <div id="method-inner"></div> --}}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jenis">Jenis izin</label>
                        <select name="jenis" id="jenis" class="form-control" wire:model="form.jenis">
                            <option value="">-- PILIH JENIS --</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Urusan Keluarga">Urusan Keluarga</option>
                            <option value="Urusan Pribadi">Urusan Pribadi</option>
                            {{-- <option value="Hari raya keagamaan">Hari raya keagamaan</option> --}}
                            {{-- <option value="Berdukacita">Berdukacita</option> --}}
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @if ($form['jenis'] == 'Lainnya')
                        <input type="text" class="form-control mt-2" placeholder="Harap input jenis lainnya..."
                            name="jenis" required wire:model="form.jenis_lainnya">
                        @endif

                        @error('form.jenis') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label for="tgl_mulai">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" id="tgl_mulai"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}" wire:model="form.tgl_mulai" wire:change="calculateTotalDays"
                                class="form-control">
                            @error ('form.tgl_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6 form-group">
                            <label for="tgl_selesai">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" id="tgl_selesai" wire:model="form.tgl_selesai" wire:change="calculateTotalDays"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="form-control">
                            @error ('form.tgl_selesai') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        {{-- total hari --}}
                        <label for="total_hari">Total Hari</label>
                        <input type="number" name="total_hari" id="total_hari" value="{{$form['total_hari']}}" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" id="keterangan" wire:model="form.keterangan" placeholder="Keterangan...">
                        @error ('form.keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="bukti">Bukti</label>
                        <input type="file" class="form-control-file" name="bukti" wire:model="form.bukti">
                        @error ('form.bukti') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" wire:click.prevent="submit"
                        wire:loading.attr="disabled" wire:target="submit">
                        <span wire:loading wire:target="submit">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                        <span wire:loading.remove wire:target="submit">
                            Simpan
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>