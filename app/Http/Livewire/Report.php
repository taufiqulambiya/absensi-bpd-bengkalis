<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use URL;

class Report extends Component
{
    public $jenis = 'pegawai';
    public $pegawai_ids = [];
    public $jenis_cutis = [];
    public $tanggal_awal = '';
    public $tanggal_akhir = '';

    protected $listeners = ['setPegawaiIds', 'setJenisCutis', 'setSelect'];

    public function render()
    {
        $pegawai = User::where('level', 'pegawai')->get();
        
        $data = [
            'pegawai' => $pegawai,
        ];
        return view('livewire.report')->with($data);
    }
    public function mount() {
        // $this->emit('renderSelectize');
    }
    public function updated() {
        $this->emit('changeType', $this->jenis);
        // $this->emit('reinitSelectize');
    }

    public function setSelect($name, $value) {
        $this->$name = $value;
    }

    public function setPegawaiIds($pegawai_ids)
    {
        $this->pegawai_ids = $pegawai_ids;
    }

    public function setJenisCutis($jenis_cutis)
    {
        $this->jenis_cutis = $jenis_cutis;
    }

    public function print() 
    {
        $this->emit('reinitSelectize');
        if ($this->jenis == 'absensi' || $this->jenis == 'cuti' || $this->jenis == 'izin' || $this->jenis == 'dinas-luar') {
            $this->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required|after_or_equal:tanggal_awal',
            ], [
                'tanggal_awal.required' => 'Tanggal awal harus diisi',
                'tanggal_akhir.required' => 'Tanggal akhir harus diisi',
            ]);
        }

        $filter = [
            'jenis' => $this->jenis,
            'pegawai' => $this->pegawai_ids,
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
            'jenis_cuti' => $this->jenis_cutis,
        ];

        $url = URL::signedRoute('report.index', $filter);
        
        $this->emit('openBlank', $url);
    }
}
