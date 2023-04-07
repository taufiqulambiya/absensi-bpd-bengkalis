<?php

namespace App\Http\Livewire\Cuti;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Cuti;

class Modal extends Component
{
    use WithFileUploads;
    public $disableDates;
    public $jatahCuti;
    public $jatahCutiView;
    // public $jenis;
    // public $keterangan;
    // public $bukti;
    public $form = [
        'id' => '',
        'jenis' => 'tahunan',
        'keterangan' => '',
        'bukti' => '',
        'tanggal' => '',
    ];

    public $isEdit = false;

    protected $listeners = ['updateEditModal'];

    public function mount()
    {
        $this->jatahCutiView = $this->jatahCuti['tahunan'];
        $this->disableDates = Cuti::getDisableDates(session('user')->id);
    }

    public function changeJenis($jenis)
    {
        $this->form['jenis'] = $jenis;
        $this->jatahCutiView = $this->jatahCuti[$jenis];

        $this->emit('rerenderDatePicker', $this->jatahCutiView);
    }
    public function changeTanggal($tanggal)
    {
        $this->form['tanggal'] = $tanggal;
    }

    public function updateEditModal($data)
    {
        $data = (object) $data;
        $this->emit('fillDatePicker', $data->tanggal);

        $this->form['id'] = $data->id;
        $this->form['tanggal'] = $data->tanggal;
        $this->form['jenis'] = $data->jenis;
        $this->jatahCutiView = $this->jatahCuti[$data->jenis];
        $this->form['keterangan'] = $data->keterangan;
        // $this->form['bukti'] = $data->bukti;
        $this->isEdit = true;
    }

    public function submit()
    {
        $this->validate([
            'form.jenis' => 'required',
            'form.keterangan' => 'required',
            'form.bukti' => $this->isEdit ? '' : 'required|mimes:pdf,jpeg,png,jpg,gif,svg|max:2048',
            'form.tanggal' => 'required',
        ], [
                'form.jenis.required' => 'Jenis cuti harus diisi',
                'form.keterangan.required' => 'Keterangan harus diisi',
                'form.bukti.required' => 'Bukti harus diisi',
                'form.bukti.mimes' => 'Bukti harus berupa file PDF, JPG, JPEG, PNG, GIF, SVG',
                'form.bukti.max' => 'Bukti maksimal 2MB',
                'form.tanggal.required' => 'Tanggal harus dipilih',
            ]);

        // store cuti
        $tanggal = $this->form['tanggal'];
        $total = count(explode(',', $tanggal));
        $tanggal = array_map(function ($date) {
            return Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
        }, explode(',', $tanggal));
        $tanggal = implode(',', $tanggal);

        $tracking = [
            "status" => $this->isEdit ? "Pengajuan diperbarui" : "Pengajuan dibuat",
            "date" => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $realUser = User::find(session('user')->id);
        $status = [
            'pegawai' => 'pending',
            'kabid' => 'accepted_kabid',
            'admin' => 'accepted_admin',
        ];
        $payload = [
            'id_user' => session('user')->id,
            'jenis' => $this->form['jenis'],
            'keterangan' => $this->form['keterangan'],
            // 'bukti' => $this->form['bukti']->hashName(),
            'tanggal' => $tanggal,
            'total' => $total,
            'status' => $status[$realUser->level],
            'tracking' => json_encode([$tracking]),
        ];
        // store file
        if ($this->form['bukti']) {
            $this->form['bukti']->store('public/cuti');
            $payload['bukti'] = $this->form['bukti']->hashName();
        }


        if ($this->isEdit) {
            $cuti = Cuti::find($this->form['id']);
            $cuti->update($payload);
        } else {
            Cuti::create($payload);
        }

        $this->emit('cutiSubmitted', $this->isEdit);
    }

    public function render()
    {
        return view('livewire.cuti.modal');
    }
}