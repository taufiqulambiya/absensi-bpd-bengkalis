<?php

namespace App\Http\Livewire\Izin;

use App\Models\Izin;
use Livewire\Component;

class DetailCard extends Component
{
    public $isShow = false;
    public $data;
    public function render()
    {
        return view('livewire.izin.detail-card');
    }

    public function mount()
    {
        $data = Izin::getIzinAktif(session('user')->id) ?? null;
        if ($data) {
            $this->data = $data;
            $this->isShow = true;
        }
    }
}
