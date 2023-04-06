<?php

namespace App\Http\Livewire\JamKerja;

use App\Models\JamKerja;
use Livewire\Component;

class Modal extends Component
{
    public $allowed = [];
    public $form = [
        'days' => [],
        'mulai' => '',
        'selesai' => '',
        'keterangan' => '',
        'status' => 'nonaktif',
    ];
    public $isEdit = false;
    protected $listeners = ['edit', 'daysChanged'];
    public function render()
    {
        return view('livewire.jam-kerja.modal');
    }

    public function mount()
    {
        $this->allowed = JamKerja::getAllowedDays();
    }

    public function submit()
    {
        $this->validate([
            'form.mulai' => 'required',
            'form.selesai' => 'required|after:form.mulai',
            'form.keterangan' => 'required',
            'form.days' => 'required|array|min:1',
        ], [
            'form.mulai.required' => 'Jam mulai harus diisi.',
            'form.selesai.required' => 'Jam selesai harus diisi.',
            'form.selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
            'form.keterangan.required' => 'Keterangan harus diisi.',
            'form.days.required' => 'Hari harus diisi.',
        ]);

        $payload = $this->form;
        $payload['days'] = join(', ', $this->form['days']);
        $pluck = ['days', 'mulai', 'selesai', 'keterangan', 'status'];
        $payload = collect($payload)->only($pluck)->toArray();
        
        if ($this->isEdit) {
            $item = JamKerja::where('id', $this->form['id'])->first();
            $item->update($payload);
            $this->reset('form');
            $this->emit('success', 'Data berhasil diubah.');
            $this->emit('refreshTable');
        } else {
            JamKerja::create($payload);
            $this->reset('form');
            $this->emit('success', 'Data berhasil ditambahkan.');
            $this->emit('refreshTable');
        }
    }

    public function edit($item)
    {
        $this->form = $item;
        $this->form['days'] = explode(', ', $item['days']);
        $this->isEdit = true;
    }

    public function daysChanged($days)
    {
        $this->form['days'] = $days;
    }
}