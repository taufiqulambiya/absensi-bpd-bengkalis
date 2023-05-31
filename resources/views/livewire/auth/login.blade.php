<div class="login_wrapper">
    <img src="{{ asset('img/logo.png') }}" alt="Logo Bapenda" class="d-block mx-auto w-50 mb-3">
    <h4 class="text-center">Sistem Informasi Absensi Pegawai</h4>
    <div class="card">
        <div class="card-body">
            <p class="text-center">Masuk Untuk Memulai Sistem</p>

            {{-- for development --}}
            <div class="form-group">
                <label for="">Quick Fill</label>
                <select name="quick-fill" id="quick-fill" wire:change="quickFill($event.target.value)"
                    class="quick-fill form-control">
                    <option value="">Pilih</option>
                    @foreach ($all_users as $item)
                    <option value="{{ $item->nip }}">{{ $item->nip }} - {{ $item->nama }}</option>
                    @endforeach
                </select>
            </div>
            {{-- for development --}}

            <form id="form-login" wire:submit.prevent="submit" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="nip" id="nip" wire:model="nip" class="form-control"
                        value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai" />

                    @error('nip') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" wire:model="password" class="form-control"
                        placeholder="Password" />
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-block submit" wire:click="submit"
                        wire:loading.attr="disabled">Masuk</button>
                </div>

                <hr>
                <p class="text-center">2022 All Rights Reserved. Sistem Absensi, Bengkalis, Riau.</p>
            </form>
        </div>
    </div>
</div>