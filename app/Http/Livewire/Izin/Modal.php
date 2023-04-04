<?php

namespace App\Http\Livewire\Izin;

use App\Models\Izin;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class Modal extends Component
{
    use WithFileUploads;
    public $form = [
        'jenis' => '',
        'tgl_mulai' => '',
        'tgl_selesai' => '',
        'total_hari' => 0,
        'keterangan' => '',
        'bukti' => '',
    ];
    public $isEdit = false;
    public $disableDates = [];

    protected $listeners = ['updateEditModal'];

    public function submit() {
        $this->validate([
            'form.jenis' => 'required',
            'form.tgl_mulai' => 'required|date|after:today',
            'form.tgl_selesai' => 'required|after_or_equal:form.tgl_mulai',
            'form.keterangan' => 'required',
            'form.bukti' => $this->isEdit ? [] : 'required|mimes:pdf,jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'form.jenis.required' => 'Jenis izin harus diisi',
            'form.tgl_mulai.required' => 'Tanggal mulai harus diisi',
            'form.tgl_mulai.after' => 'Tanggal mulai harus setelah hari ini',
            'form.tgl_selesai.required' => 'Tanggal selesai harus diisi',
            'form.tgl_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
            'form.keterangan.required' => 'Keterangan harus diisi',
            'form.bukti.required' => 'Bukti harus diisi',
            'form.bukti.mimes' => 'Bukti, harus berupa file PDF, JPG, JPEG, PNG, GIF, SVG',
            'form.bukti.max' => 'Bukti maksimal berukuran 2MB',
        ]);

        if ($this->form['jenis'] == 'Lainnya' AND empty($this->form['jenis_lainnya'])) {
            $this->addError('form.jenis', 'Harap isi jenis izin lainnya');
            return;
        }


        // check if tgl_mulai and tgl_selesai range is in disableDates
        $disableDates = $this->disableDates;
        $tgl_mulai = $this->form['tgl_mulai'];
        $tgl_selesai = $this->form['tgl_selesai'];
        $isInRange = Izin::isInRange($tgl_mulai, $tgl_selesai, $disableDates);
        if ($isInRange) {
            $this->addError('form.tgl_mulai', 'Tanggal mulai dan tanggal selesai harus berada di luar tanggal izin yang sudah diajukan');
            $this->addError('form.tgl_selesai', 'Tanggal mulai dan tanggal selesai harus berada di luar tanggal izin yang sudah diajukan');
            return;
        }

        $tracking = [
            'date' => date('Y-m-d H:i:s'),
            'status' => 'Pengajuan dibuat',
        ];

        $realUser = User::find(session('user')->id);
        $status = [
            'pegawai' => 'pending',
            'kabid' => 'accepted_kabid',
            'admin' => 'accepted_admin',
        ];

        $payload = [
            'id_user' => session('user')->id,
            'jenis' => $this->form['jenis'] == 'Lainnya' ? $this->form['jenis_lainnya'] : $this->form['jenis'],
            'tgl_mulai' => $this->form['tgl_mulai'],
            'tgl_selesai' => $this->form['tgl_selesai'],
            'keterangan' => $this->form['keterangan'],
            'status' => $status[$realUser->level],
            'tracking' => json_encode([$tracking]),
        ];

        if ($this->form['bukti']) {
            $this->form['bukti']->store('public/izin');
            $payload['bukti'] = $this->form['bukti']->hashName();
        }

        if ($this->isEdit) {
            $item = Izin::find($this->form['id']);
            $item->update($payload);
            $this->emit('success', 'Pengajuan izin berhasil diubah');
        } else {
            Izin::create($payload);
            $this->emit('success', 'Pengajuan izin berhasil dibuat');
        }

    }

    public function calculateTotalDays() {
        if (!$this->form['tgl_mulai'] || !$this->form['tgl_selesai']) {
            return;
        }
        $tgl_mulai = $this->form['tgl_mulai'];
        $tgl_selesai = $this->form['tgl_selesai'];
        $total_hari = Carbon::parse($tgl_mulai)->diffInDays($tgl_selesai) + 1;
        $this->form['total_hari'] = $total_hari;
    }

    public function render()
    {
        return view('livewire.izin.modal');
    }

    public function mount()
    {
        $user_id = session('user')->id;
        $this->disableDates = Izin::getDisableDates($user_id);
    }

    public function updateEditModal($data)
    {
        $this->isEdit = true;
        $this->form['jenis'] = $data['jenis'];
        $this->form['tgl_mulai'] = $data['tgl_mulai'];
        $this->form['tgl_selesai'] = $data['tgl_selesai'];
        $total_hari = Carbon::parse($data['tgl_mulai'])->diffInDays($data['tgl_selesai']) + 1;
        $this->form['total_hari'] = $total_hari;
        $this->form['keterangan'] = $data['keterangan'];
        $this->form['id'] = $data['id'];
    }
}