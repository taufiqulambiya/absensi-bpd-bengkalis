<div class="card">
    <div class="card-header">
        <h4 class="card-title">Perbarui Password</h4>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="old-password">Password Lama</label>
            <input type="password" class="form-control" name="old-password" id="old-password"
                placeholder="Password lama..." wire:model="oldPassword">
            @error ('oldPassword') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password">Password Baru</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password baru..." wire:model="newPassword">
            @error ('newPassword') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="password2">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" name="password2" id="password2"
                placeholder="Konfirmasi password baru..." wire:model="confirmPassword">
            @error ('confirmPassword') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>

        <button class="btn btn-warning" type="submit" wire:click="updatePassword" wire:loading.attr="disabled">
            <span wire:loading wire:target="updatePassword">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
            <span wire:loading.remove wire:target="updatePassword">
                <i class="fas fa-key"></i>
            </span>
            <span>Perbarui Password</span>
        </button>
    </div>
</div>