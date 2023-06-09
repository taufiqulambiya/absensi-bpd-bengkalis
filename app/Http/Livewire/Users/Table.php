<?php

namespace App\Http\Livewire\Users;

use App\Models\MasterBidang;
use App\Models\User;
use Livewire\Component;

class Table extends Component
{
    public $data = [];
    public $level = '';
    protected $listeners = ['refreshTable' => 'mount', 'filterBidang', 'resetPassword' => 'resetPassword', 'delete' => 'delete'];

    public function render()
    {
        return view('livewire.users.table');
    }

    public function mount()
    {
        $currentUser = User::find(session('user')->id);
        if ($currentUser->level == 'admin') {
            $this->data = User::all();
        } else {
            $bidang = $currentUser->bidang;
            $this->data = User::where('bidang', $bidang)->get();
        }
        $this->level = session('user')->level;
    }


    public function edit($id)
    {
        $item = User::find($id);
        $this->emit('fillEdit', $item);
    }

    public function delete($id)
    {
        $currentUser = User::find(session('user')->id);
        $item = User::find($id);
        if ($currentUser->id == $item->id) {
            $this->emit('error', 'Tidak bisa menghapus diri sendiri');
            return;
        }

        $allAdmin = User::where('level', 'admin')->get();
        if ($item->level == 'admin' && count($allAdmin) == 1) {
            $this->emit('error', 'Tidak bisa menghapus admin terakhir');
            return;
        }
        $allAtasan = User::where('level', 'atasan')->get();
        if ($item->level == 'atasan' && count($allAtasan) == 1) {
            $this->emit('error', 'Tidak bisa menghapus atasan terakhir');
            return;
        }

        // deletes all related data from, absensi, izin, cuti, and dinas luar
        $item->absensi()->delete();
        $item->izin()->delete();
        $item->cuti()->delete();
        $item->dinas_luar()->delete();

        $item->delete();
        $this->emit('success', 'Data berhasil dihapus');
        $this->emit('refreshTable');
    }

    public function resetPassword($id)
    {
        $user = User::find($id);
        $newPassword = $this->getRandomPassword();
        $user->password = bcrypt($newPassword);
        $user->save();

        $msgHtml = '<p>Password berhasil direset. Password baru adalah <b>' . $newPassword . '</b></p>';
        $this->emit('successHtml', $msgHtml);
        $this->emit('refreshTable');
    }

    private function getRandomPassword()
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $key = '';
        for ($i = 0; $i < 8; $i++) {
            $key .= $pattern[rand(0, 35)];
        }
        return $key;
    }

    public function filterBidang($id)
    {
        if (empty($id)) {
            $this->mount();
            return;
        }
        $bidang = MasterBidang::find($id);
        $this->data = User::where('bidang', $bidang->id)->get();
    }
}
