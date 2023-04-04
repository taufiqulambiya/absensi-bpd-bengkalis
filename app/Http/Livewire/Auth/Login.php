<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;

class Login extends Component
{
    public $all_users = null;
    public $nip = null;
    public $password = null;

    public function mount()
    {
        $this->all_users = User::all();
    }

    // testing purpose
    public function quickFill($nip)
    {
        $this->nip = $nip;
        $this->password = '12345';
    }

    public function submit()
    {
        $this->validate([
            'nip' => 'required|numeric|min:16',
            'password' => 'required',
        ], [
                'required' => ':Attribute tidak boleh kosong.',
                'numeric' => ':ATTRIBUTE harus berupa angka.',
                'min' => ':ATTRIBUTE harus berupa angka minimal :min.'
            ]);

        $user = User::where(["nip" => $this->nip])->first();
        if ($user) {
            $is_password_correct = password_verify($this->password, $user->password);

            if ($is_password_correct) {
                $saveKeys = ['id', 'nip', 'bidang', 'jabatan', 'level', 'nama'];
                $user = $user->only($saveKeys);
                // save in object
                $user = (object) $user;
                session()->put('user', $user);
                return redirect()->route('dashboard');
            } else {
                $this->addError('password', 'Password salah.');
            }
        } else {
            $this->addError('nip', 'User tidak ditemukan.');
        }

    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}