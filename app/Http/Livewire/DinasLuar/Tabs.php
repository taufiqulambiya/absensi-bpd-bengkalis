<?php

namespace App\Http\Livewire\DinasLuar;

use App\Models\DinasLuar;
use Livewire\Component;

class Tabs extends Component
{
    public $tab = 'active';
    public $data = [];
    public function render()
    {
        $user = session('user');
        $level = $user->level;
        return view('livewire.dinas-luar.tabs', compact('level'));
    }

    public function mount()
    {
        $this->tab = session('tab') ?? 'active';
        $this->data = DinasLuar::getByTab($this->tab);
    }

    public function changeTab($tab)
    {
        $this->tab = $tab;
        $this->data = DinasLuar::getByTab($this->tab);
        session(['tab' => $tab]);
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
}
