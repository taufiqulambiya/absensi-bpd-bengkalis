<?php

namespace App\Http\Livewire\DinasLuar;

use App\Models\DinasLuar;
use Livewire\Component;

class DetailCard extends Component
{
    public $data;
    public function render()
    {
        return view('livewire.dinas-luar.detail-card');
    }
    public function mount() {
        $this->data = DinasLuar::getAktif(session('user')->id);
    }
}
