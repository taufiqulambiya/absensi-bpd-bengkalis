<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class UpdatePassword extends Component
{
    public $oldPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';
    public function render()
    {
        return view('livewire.update-password');
    }

    public function updatePassword() {
        $this->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword',
        ], [
            'oldPassword.required' => 'Password lama harus diisi',
            'newPassword.required' => 'Password baru harus diisi',
            'newPassword.min' => 'Password baru minimal 8 karakter',
            'confirmPassword.required' => 'Konfirmasi password harus diisi',
            'confirmPassword.same' => 'Konfirmasi password tidak sama dengan password baru',
        ]);

        $user = User::findOrFail(session('user')->id);
        
        // check old password
        if (password_verify($this->oldPassword, $user->password)) {
            $user->password = password_hash($this->newPassword, PASSWORD_DEFAULT);
            $user->save();
            $this->emit('success', 'Password berhasil diubah');
        } else {
            $this->addError('oldPassword', 'Password lama salah');
        }
    }
}
