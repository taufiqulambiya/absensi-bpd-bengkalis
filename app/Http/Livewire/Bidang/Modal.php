<?php

namespace App\Http\Livewire\Bidang;

use App\Models\MasterBidang;
use Livewire\Component;

class Modal extends Component
{
    public $nama;
    public $isEdit = false;
    public $editId = null;
    public $listeners = ['fillEdit' => 'edit'];
    public function render()
    {
        return view('livewire.bidang.modal');
    }

    public function store() {
        $this->validate([
            'nama' => 'required|unique:tb_bidang,nama,' . $this->editId 
        ], [
            'nama.required' => 'Nama bidang harus diisi.',
            'nama.unique' => 'Nama bidang sudah ada.'
        ]);

        $item = new MasterBidang();
        if ($this->isEdit) {
            $item = MasterBidang::find($this->editId);
        }

        $item->nama = $this->nama;
        $item->save();
        $this->emit('success', 'Data berhasil disimpan.');
        $this->emit('refreshTable');
    }

    public function edit($item) {
        $item = (object) $item;
        $this->isEdit = true;
        $this->editId = $item->id;
        $this->nama = $item->nama;
    }
}
