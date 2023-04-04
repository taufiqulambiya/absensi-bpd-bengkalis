<?php

namespace App\Http\Livewire\Cuti;

use Livewire\Component;

class JatahCutiCard extends Component
{
    public $data = [];
    public $show = 'tahunan';
    public $enableAdd = false;
    public $level = '';

    public function render()
    {
        return view('livewire.cuti.jatah-cuti-card');
    }

    public function mount()
    {
        $user = session('user');
        $this->level = $user->level;
    }
}
