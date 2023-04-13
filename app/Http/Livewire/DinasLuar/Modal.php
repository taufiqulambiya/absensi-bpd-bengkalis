<?php

namespace App\Http\Livewire\DinasLuar;

use App\Models\Absensi;
use App\Models\DinasLuar;
use App\Models\JamKerja;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class Modal extends Component
{
    use WithFileUploads;
    public $users = [];
    public $isEdit = false;
    public $form = [
        'id_user' => '',
        'mulai' => '',
        'selesai' => '',
        'maksud' => '',
        'lokasi' => '',
        'keterangan' => '',
        'file' => '',
    ];
    public $selectedPegawai = null;
    protected $listeners = ['edit'];

    public function render()
    {
        return view('livewire.dinas-luar.modal');
    }

    public function mount()
    {
        $this->users = User::with('bidangs')->whereNot('level', 'atasan')->get();
    }

    public function getPegawaiInfo($id)
    {
        $item = User::with('bidangs')->find($id);

        $this->selectedPegawai = $item;
    }

    public function submit()
    {
        // dd($this->form);
        $this->validate(
            [
                'form.id_user' => 'required',
                'form.mulai' => 'required',
                'form.selesai' => 'required|after_or_equal:form.mulai',
                'form.maksud' => 'required',
                'form.lokasi' => 'required',
                'form.file' => ($this->isEdit ? '' : 'required|') . 'file|mimes:pdf,jpeg,png|max:2048',
            ],
            [
                'form.id_user.required' => 'Pegawai harus dipilih',
                'form.mulai.required' => 'Tanggal mulai harus diisi',
                'form.selesai.required' => 'Tanggal selesai harus diisi',
                'form.selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
                'form.maksud.required' => 'Maksud harus diisi',
                'form.lokasi.required' => 'Lokasi harus diisi',
                'form.file.required' => 'File harus diisi',
                'form.file.file' => 'File harus berupa file',
                'form.file.mimes' => 'File harus berupa PDF, JPEG, atau PNG',
            ]
        );

        $payload = $this->form;
       

        $isAllowAdd = DinasLuar::checkAllowAdd($payload['id_user'], $payload['mulai'], $payload['selesai'], $payload['id'] ?? null);
        $isAllow = $isAllowAdd['allow'];
        $isAllowMessage = $isAllowAdd['message'];
        
        if (!$isAllow) {
            $this->emit('error', $isAllowMessage);
            return;
        }

        if (isset($this->form['file'])) {
            $this->form['file']->store('public/dinas-luar');
            $payload['file'] = $this->form['file']->hashName();
        }

        $uniqueId = uniqid('dinas-luar-', true);

        // generate absensi for range of date
        $absensis = [];
        $periodCarbon = CarbonPeriod::create($this->form['mulai'], $this->form['selesai']);
        // $jamKerja = JamKerja::getAktif();
        foreach ($periodCarbon as $date) {
            $isWeekend = $date->isWeekend();
            if ($isWeekend) {
                continue;
            }
            $dayIndex = $date->dayOfWeek;
            $indoDays = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
            $day = $indoDays[$dayIndex];
            $jamKerja = JamKerja::where('days', 'like', '%' . $day . '%')->first();

            $absensis[] = [
                'id_jam' => $jamKerja->id,
                'id_user' => $this->form['id_user'],
                'tanggal' => $date->format('Y-m-d'),
                'waktu_masuk' => $jamKerja->mulai,
                'waktu_keluar' => $jamKerja->selesai,
                'total_jam' => Carbon::parse($jamKerja->selesai)->diffInMinutes(Carbon::parse($jamKerja->mulai)) / 60,
                'dinas_id' => $uniqueId,
                'status' => 'dinas',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // dd($absensis);

        $payload['record_id'] = $uniqueId;

        if ($this->isEdit) {
            $dinas = DinasLuar::find($this->form['id']);
            $record_id = $dinas->record_id;
            $absensi = Absensi::where('dinas_id', $record_id)->get();
            foreach ($absensi as $item) {
                $item->delete();
            }
            $dinas->update($payload);
            Absensi::insert($absensis);
            $this->emit('success', 'Dinas luar berhasil diubah', true);
        } else {
            DinasLuar::insert($payload);
            Absensi::insert($absensis);
            $this->emit('success', 'Dinas luar berhasil ditambahkan', true);
        }


    }

    public function edit($item)
    {
        $item = (object) $item;

        $this->isEdit = true;
        $this->selectedPegawai = User::with('bidangs')->find($item->id_user);
        $this->form = [
            'id' => $item->id,
            'id_user' => $item->id_user,
            'mulai' => $item->mulai,
            'selesai' => $item->selesai,
            'maksud' => $item->maksud,
            'lokasi' => $item->lokasi,
            'keterangan' => $item->keterangan,
        ];
    }
}