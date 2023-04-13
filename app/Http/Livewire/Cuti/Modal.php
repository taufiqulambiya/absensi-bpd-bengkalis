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
    public $jatahCutiValue;
    public $errorTotal = false;
    // public $jenis;
    // public $keterangan;
    // public $bukti;
    public $form = [
        'id' => '',
        'jenis' => 'tahunan',
        'keterangan' => '',
        'bukti' => '',
        // 'tanggal' => '',
        'mulai' => '',
        'selesai' => '',
        'total' => 0,
    ];

    public $isEdit = false;

    protected $listeners = ['setEditModal', 'setDate', 'resetForm'];

    public function mount()
    {
        $this->jatahCutiValue = $this->jatahCuti['tahunan'];
        $this->disableDates = Cuti::getDisableDates(session('user')->id);
    }
    public function updatedFormMulai()
    {
        $this->setDate('mulai', $this->form['mulai']);
    }
    public function updatedFormSelesai()
    {
        $this->setDate('selesai', $this->form['selesai']);
    }

    public function changeJenis($jenis)
    {
        $this->form['jenis'] = $jenis;
        $this->jatahCutiValue = $this->jatahCuti[$jenis];

        $this->emit('rerenderDatePicker', $this->jatahCutiValue);
    }
    public function changeTanggal($tanggal)
    {
        $this->form['tanggal'] = $tanggal;
    }
    public function setDate($id, $date)
    {
        $this->form[$id] = $date;
        $total = getDurationExceptWeekend($this->form['mulai'], $this->form['selesai']);
        $jatahCuti = $this->jatahCutiValue;
        if ($total > $jatahCuti) {
            $this->addError('form.total', 'Jatah cuti anda hanya ' . $jatahCuti . ' hari');
        } else {
            $this->resetErrorBag('form.total');
        }
        $this->form['total'] = $total;
        $this->errorTotal = $total > $jatahCuti;
    }

    public function resetForm()
    {
        $this->form = [
            'id' => '',
            'jenis' => 'tahunan',
            'keterangan' => '',
            'bukti' => '',
            // 'tanggal' => '',
            'mulai' => '',
            'selesai' => '',
            'total' => 0,
        ];
        $this->jatahCutiValue = $this->jatahCuti['tahunan'];
        $this->isEdit = false;
        $this->resetErrorBag();
    }


    public function setEditModal($id)
    {
        $data = Cuti::find($id);
        // $this->emit('fillDatePicker', $data->tanggal);

        $this->form['id'] = $data->id;
        // $this->form['tanggal'] = $data->tanggal;
        $this->form['mulai'] = $data->mulai;
        $this->form['selesai'] = $data->selesai;
        $this->form['total'] = $data->total;
        $this->form['jenis'] = $data->jenis;
        $this->form['keterangan'] = $data->keterangan;
        $this->isEdit = true;

        $diff = Carbon::parse($data->mulai)->diffInDays(Carbon::parse($data->selesai));
        $jatah = $this->jatahCuti[$data->jenis] + $diff;
        $this->jatahCutiValue = $jatah;

        $this->emit('setDateJS', 'mulai', date('Y-m-d', strtotime($data->mulai)));
        $this->emit('setDateJS', 'selesai', date('Y-m-d', strtotime($data->selesai)));
    }

    public function submit()
    {
        $this->validate([
            'form.jenis' => 'required',
            'form.keterangan' => 'required',
            'form.bukti' => $this->isEdit ? '' : 'required|mimes:pdf,jpeg,png,jpg,gif,svg|max:2048',
            // 'form.tanggal' => 'required',
            'form.mulai' => 'required',
            'form.selesai' => 'required|after_or_equal:form.mulai',
        ], [
                'form.jenis.required' => 'Jenis cuti harus diisi',
                'form.keterangan.required' => 'Keterangan harus diisi',
                'form.bukti.required' => 'Bukti harus diisi',
                'form.bukti.mimes' => 'Bukti harus berupa file PDF, JPG, JPEG, PNG, GIF, SVG',
                'form.bukti.max' => 'Bukti maksimal 2MB',
                // 'form.tanggal.required' => 'Tanggal harus dipilih',
                'form.mulai.required' => 'Tanggal mulai harus diisi',
                'form.selesai.required' => 'Tanggal selesai harus diisi',
                'form.selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            ]);

        if ($this->errorTotal) {
            $this->addError('form.total', 'Jatah cuti anda hanya ' . $this->jatahCutiValue . ' hari');
            return;
        }

        // store cuti
        // $tanggal = $this->form['tanggal'];
        // $total = count(explode(',', $tanggal));
        // $tanggal = array_map(function ($date) {
        //     return Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
        // }, explode(',', $tanggal));
        // $tanggal = implode(',', $tanggal);

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
            // 'tanggal' => $tanggal,
            // 'total' => $total,
            'mulai' => $this->form['mulai'],
            'selesai' => $this->form['selesai'],
            'total' => $this->form['total'],
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