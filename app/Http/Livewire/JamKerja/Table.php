<?php

namespace App\Http\Livewire\JamKerja;

use App\Models\JamKerja;
use Livewire\Component;

class Table extends Component
{
    public $data = [];
    protected $listeners = ['refreshTable' => 'mount','delete'];

    private function transform($data) {
        return $data->map(function($item) {
            $item->formatted_created_at = $item->created_at->format('d/m/Y H:i');
            return $item;
        });
    }

    public function render()
    {
        return view('livewire.jam-kerja.table');
    }

    public function mount() {
        // $this->data = JamKerja::all();
        $this->data = $this->transform(JamKerja::all());
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
        $this->data = $this->transform(JamKerja::all());
    }

    public function delete($id) {
        $item = JamKerja::where('id', $id)->first();
        $item->delete();
        $this->emit('success', 'Data berhasil dihapus.');
        $this->mount();
    }
}
