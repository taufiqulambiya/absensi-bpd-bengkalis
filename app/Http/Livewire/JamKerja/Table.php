<?php

namespace App\Http\Livewire\JamKerja;

use App\Models\JamKerja;
use Livewire\Component;

class Table extends Component
{
    public $data = [];
    protected $listeners = ['refreshTable' => 'mount','delete'];
    public function render()
    {
        return view('livewire.jam-kerja.table');
    }

    public function mount() {
        $this->data = JamKerja::whereNull('deleted_at')->get();
    }
    public function toggleStatus($id) {
        $item = JamKerja::where('id', $id)->first();
        // check days
        $days = explode(', ', $item->days);
        $currentActive = JamKerja::where('status', 'aktif')->get()->pluck('days')->map(function($item) {
            return explode(', ', $item);
        })->flatten()->unique()->toArray();
        $exist = array_intersect($days, $currentActive);
        if ($item->status == 'nonaktif' && count($exist) > 0) {
            $this->emit('error', 'Jam kerja aktif pada hari yang sama sudah ada.');
            $this->emit('cancelSwitch', "customSwitch$id");
            return;
        }

        $item->status = $item->status == 'aktif' ? 'nonaktif' : 'aktif';
        $item->save();
        $this->mount();
    }

    public function edit($id) {
        $item = JamKerja::where('id', $id)->first();
        $allowed = JamKerja::getAllowedDays();
        $merged = array_unique(array_merge($allowed, explode(', ', $item->days)));
        $implode = implode(', ', $merged);
        $item->allDays = $implode;
        $this->emit('edit', $item);
        // $this->emit('renderSelect');
    }

    public function delete($id) {
        $item = JamKerja::where('id', $id)->first();
        $item->delete();
        $this->emit('success', 'Data berhasil dihapus.');
        $this->mount();
    }
}
