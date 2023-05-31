<?php

namespace App\Http\Livewire\Bidang;

use App\Models\MasterBidang;
use Livewire\Component;

class Table extends Component
{
    public $data = [];
    public $listeners = ['refreshTable' => 'mount', 'delete' => 'delete'];

    private function transform($data) {
        return $data->map(function($item) {
            $item->formatted_created_at = $item->created_at->format('d/m/Y H:i');
            $item->formatted_updated_at = $item->updated_at->format('d/m/Y H:i');
            return $item;
        });
    }

    public function render()
    {
        return view('livewire.bidang.table');
    }

    public function mount()
    {
        // $this->data = MasterBidang::all();
        $this->data = $this->transform(MasterBidang::all());
    }

    public function edit($id)
    {
        $item = MasterBidang::find($id);
        $this->emit('fillEdit', $item);
        $this->data = $this->transform(MasterBidang::all());
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