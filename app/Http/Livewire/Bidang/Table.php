<?php

namespace App\Http\Livewire\Bidang;

use App\Models\MasterBidang;
use Livewire\Component;

class Table extends Component
{
    public $data = [];
    public $listeners = ['refreshTable' => 'mount', 'delete' => 'delete'];
    public function render()
    {
        return view('livewire.bidang.table');
    }

    public function mount()
    {
        $this->data = MasterBidang::all();
    }

    public function edit($id)
    {
        $item = MasterBidang::find($id);
        $this->emit('fillEdit', $item);
    }
    public function delete($id)
    {
        $item = MasterBidang::with('users')->find($id);
        if ($item->users->count() > 0) {
            $this->emit('error', 'Data tidak bisa dihapus karena terdapat data user yang terkait.');
            return;
        }
        $item->delete();
        $this->mount();
        $this->emit('success', 'Data berhasil dihapus');
    }
}