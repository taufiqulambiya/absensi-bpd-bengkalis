<div class="card">
    <div class="card-header">
        <h4 class="card-title">Profil Utama</h4>
    </div>
    <div class="card-body">
        <button class="btn btn-info position-absolute" style="z-index: 2" title="Perbarui" wire:click="toggleEdit">
            <i class="fa fa-pencil" aria-hidden="true"></i>
        </button>
        <table class="table table-bordered prfltbl">
            <tbody>
                <tr>
                    <td colspan="2">
                        <div class="image-container mb-3">
                            @if(Storage::has('public/user_images/' .
                            $user->gambar))
                            <img src="{{ Storage::url('public/user_images/'.$user->gambar) }}" alt="Profile"
                                class="d-block w-50 rounded-circle mx-auto img-thumbnail" id="user-image" wire:ignore>
                            @else
                            <img src="https://via.placeholder.com/200?text={{ $user->nama }}" alt="Profile"
                                class="d-block w-50 rounded-circle mx-auto img-thumbnail" id="user-image" wire:ignore>
                            @endif

                            <div class="upload-image text-center mt-3 input">
                                <label for="gambar" style="cursor: pointer">
                                    <button type="button" style="pointer-events: none"
                                        class="btn btn-primary rounded-circle">
                                        <i class="fas fa-camera" style="pointer-events: none"></i>
                                    </button>
                                </label>
                                <input type="file" name="gambar" id="gambar" accept="image/*" hidden
                                    wire:model="form.gambar" onchange="previewImage(event)">
                            </div>

                            @error ('form.gambar') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Nama</td>
                    @if ($isEdit)
                    <td class="input">
                        <input type="text" class="form-control" name="nama" placeholder="Nama..."
                            value="{{ $user->nama }}" wire:model="form.nama">
                        @error ('form.nama') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                    @else
                    <td class="text">{{ $user->nama }}</td>
                    @endif
                </tr>
                <tr>
                    <td>NIP</td>
                    @if ($isEdit)
                    <td class="input">
                        <input type="text" class="form-control" name="nip" placeholder="NIP..." value="{{ $user->nip }}"
                            wire:model="form.nip">
                        @error ('form.nip') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                    @else
                    <td class="text">{{ $user->nip }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Golongan</td>
                    @if ($isEdit)
                    <td class="input">
                        <input type="text" class="form-control" name="golongan" placeholder="Golongan..."
                            value="{{ $user->golongan }}" wire:model="form.golongan">
                            @error ('form.golongan') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                    @else
                    <td class="text">{{ $user->golongan }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Jabatan</td>
                    @if ($isEdit)
                    <td class="input">
                        <div class="form-group">
                            <select class="form-control" name="jabatan" id="jabatan" wire:model="form.jabatan">
                                <option value="Kabid">Kabid</option>
                                <option value="Kasubbid">Kasubbid</option>
                                <option value="Subbag">Subbag</option>
                                <option value="Kasubbag">Kasubbag</option>
                                <option value="Kasi">Kasi</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Staff">Staff</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        @if ($form['jabatan'] == 'Lainnya')
                        <div>
                            <input type="text" class="form-control" name="jabatan" id="jabatan-lainnya"
                                placeholder="Isikan jabatan..." value="{{ $user->jabatan }}" required
                                wire:model="form.jabatan_lainnya">
                            @error ('form.jabatan_lainnya') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </td>
                    @else
                    <td class="text">{{ $user->jabatan }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Bidang</td>
                    @if ($isEdit)
                    <td class="input">
                        <select name="bidang" id="bidang" class="form-control" disabled wire:model="form.bidang">
                            <option value="">-- PILIH --</option>
                            @foreach ($bidangs as $item)
                            <option value="{{ $item->id }}" @if ($user->bidangs and $user->bidangs->id ==
                                $item->id)
                                selected
                                @endif>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        @error ('form.bidang') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                    @else
                    <td class="text">{{ $user->bidangs->nama ?? '-' }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    @if ($isEdit)
                    <td class="input">
                        <input type="date" class="form-control" name="tgl_lahir" value="{{ $user->tgl_lahir }}"
                            wire:model="form.tgl_lahir">
                        @error ('form.tgl_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                    @else
                    <td class="text">{{ $user->tgl_lahir }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    @if ($isEdit)
                    <td class="input">
                        <select name="jk" id="jk" class="form-control" wire:model="form.jk">
                            <option value="">-- PILIH --</option>
                            <option value="Laki-laki" @if ($user->jk == 'Laki-laki')
                                selected
                                @endif>Laki-laki</option>
                            <option value="Perempuan" @if ($user->jk == 'Perempuan')
                                selected
                                @endif>Perempuan</option>
                        </select>
                        @error ('form.jk') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                    @else
                    <td class="text">{{ $user->jk }}</td>
                    @endif
                </tr>
                <tr>
                    <td>Alamat</td>
                    @if ($isEdit)
                    <td class="input">
                        <input type="text" class="form-control" name="alamat" placeholder="Alamat..."
                            value="{{ $user->alamat }}" wire:model="form.alamat">
                        @error ('form.alamat') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                    @else
                    <td class="text">{{ $user->alamat }}</td>
                    @endif
                </tr>
                <tr>
                    <td>No. Telp</td>
                    @if ($isEdit)
                    <td class="input">
                        <input type="text" class="form-control" name="no_telp" placeholder="No. HP..."
                            value="{{ $user->no_telp }}" wire:model="form.no_telp">
                        @error ('form.no_telp') <span class="text-danger">{{ $message }}</span> @enderror
                    </td>
                    @else
                    <td class="text">{{ $user->no_telp }}</td>
                    @endif
                </tr>
                @if ($isEdit)
                <tr>
                    <td colspan="2">
                        <div class="text-center input">
                            <button class="btn btn-primary" id="btn-submit" type="submit" wire:click="update">
                                <span wire:loading wire:target="update">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                                <span wire:loading.remove wire:target="update">
                                    <i class="fas fa-save"></i>
                                </span>
                                Simpan
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <script>
        function previewImage(e) {
            var reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.querySelector('#user-image');
                preview.src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    </script>
</div>