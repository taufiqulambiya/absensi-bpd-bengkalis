<?php

namespace App\Http\Livewire\DinasLuar;

use App\Models\Absensi;
use App\Models\DinasLuar;
use Livewire\Component;

class Tabs extends Component
{
    public $tab = 'active';
    public $data = [];
    protected $listeners = ['delete'];
    public function render()
    {
        $user = session('user');
        $level = $user->level;
        return view('livewire.dinas-luar.tabs', compact('level'));
    }

    public function mount()
    {
        $this->tab = session('activeTabDinas') ?? 'active';
        $this->data = DinasLuar::getByTab($this->tab);
    }

    public function changeTab($tab)
    {
        $this->tab = $tab;
        $this->data = DinasLuar::getByTab($this->tab);
        session(['activeTabDinas' => $tab]);
        $this->emit('initDataTable');
    }

    public function print($id) {
        $url = route('dinas_luar.index', ['print' => $id]);

        $this->data = DinasLuar::getByTab($this->tab);
        $this->emit('print', $url);
    }

    public function edit($id) {
        $item = DinasLuar::find($id);
        $this->data = DinasLuar::getByTab($this->tab);
        $this->emit('edit', $item);
    }

    public function delete($id) {
        $item = DinasLuar::find($id);
        // dump($item->record_id);
        // deelte all absensi with record_id = $item->record_id
        $absensis = Absensi::where('dinas_id', $item->record_id)->get();
        foreach ($absensis as $absensi) {
            $absensi->delete();
        }
        // unlink file in public/dinas-luar/
        $file = public_path('dinas-luar/' . $item->file);
        if (file_exists($file)) {
            unlink($file);
        }
        $item->delete();
        $this->data = DinasLuar::getByTab($this->tab);

        $this->emit('success', 'Data berhasil dihapus');
    }
}
