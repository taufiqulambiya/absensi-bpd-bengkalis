<?php

namespace App\Http\Livewire\Absensi;

use App\Models\Absensi;
use Livewire\Component;

class ListHarian extends Component
{
    public $data;
    public $status = 'all';
    public $tgl;
    public $isFiltered = false;
    public function render()
    {
        return view('livewire.absensi.list-harian');
    }
    public function mount()
    {
        $this->tgl = request()->tgl ?? date('Y-m-d');
        $this->data = Absensi::getByDateAdmin($this->tgl, $this->status);
    }
    public function updated(){
        $this->data = Absensi::getByDateAdmin($this->tgl, $this->status);
    }
    public function detail($id) {
        $url = route('absensi.show', $id);
        
        return redirect($url);
    }

    public function setIsFiltered($bool)
    {
        $this->isFiltered = $bool;
    }

    public function clearFilter()
    {
        $this->tgl = date('Y-m-d');
        $this->status = 'all';
        $this->isFiltered = false;
        $this->data = Absensi::getByDateAdmin($this->tgl, $this->status);
    }

    public function print($id) {
        $qs = http_build_query([
            'print' => 'id',
            'id' => $id,
            'mode' => 'harian',
        ]);
        $url = route('absensi.index') . '?' . $qs;

        $this->data = Absensi::getByDateAdmin($this->tgl, $this->status);
        $this->emit('openUrl', $url);
    }
}