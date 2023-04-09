<?php

namespace App\Http\Livewire\Cuti;

use App\Models\Cuti;
use Livewire\Component;

class DetailCard extends Component
{
    public $isShow = false;
    public $cuti;
    public function render()
    {
        return view('livewire.cuti.detail-card');
    }

    public function mount()
    {
        $cuti = Cuti::getAktif(session('user')->id) ?? null;
        if ($cuti != null) {
            $this->cuti = $cuti;
            $this->isShow = true;
        }
    }
}
