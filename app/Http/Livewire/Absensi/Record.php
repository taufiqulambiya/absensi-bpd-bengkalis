<?php

namespace App\Http\Livewire\Absensi;

use App\Models\Absensi;
use App\Models\JamKerja;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Record extends Component
{
    public $mode = 'in';
    public $disable_log = true;
    public $currentRecord = null;
    public $idJam = null;
    public $jamKerja = null;
    public $missedOut = null;
    public $showRecord = false;
    public $captureState = 'ready';
    public $loadings = [];
    public $form = [];
    public $isLocationDone = false;
    protected $listeners = ['setLocation', 'setCaptureState', 'setCapture', 'startCapture'];

    public function getLocation()
    {
        $this->emit('getLocation');
        $this->toggleLoading('getLocation');
    }

    private function toggleLoading($key)
    {
        if (in_array($key, $this->loadings)) {
            array_splice($this->loadings, array_search($key, $this->loadings), 1);
        } else {
            array_push($this->loadings, $key);
        }
    }

    public function setLocation($val)
    {
        foreach ($val as $key => $value) {
            $this->form[$key] = $value;
        }
        $this->form['geolocation'] = $val['location']['latitude'] . ', ' . $val['location']['longitude'];
        $this->toggleLoading('getLocation');
        $this->isLocationDone = true;
    }

    public function showRecord()
    {
        $this->showRecord = true;
        session(['showRecord' => true]);
    }

    public function startCapture()
    {
        $this->captureState = 'started';
    }

    public function setCaptureState($state)
    {
        $this->captureState = $state;
    }

    public function setCapture($dataURL)
    {
        $this->form['dok'] = $dataURL;
    }
    public function finishCapture()
    {
        $this->captureState = 'finished';
    }

    public function submitRecord()
    {
        $suffix = $this->mode == 'in' ? 'masuk' : 'keluar';

        $dokumentasi = $this->form['dok'] ?? null;
        $filename = null;
        if ($dokumentasi) {
            $filename = 'dokumentasi_' . $suffix . '_' . time() . '.png';
            $dokumentasi = str_replace('data:image/png;base64,', '', $dokumentasi);
            $dokumentasi = str_replace(' ', '+', $dokumentasi);
            $dokumentasi = base64_decode($dokumentasi);
            Storage::disk('public')->put('uploads/' . $filename, $dokumentasi);
        }

        $user = session('user');
        $payload = [
            "id_user" => $user->id,
            "id_jam" => $this->idJam,
            "tanggal" => $this->missedOut->tanggal ?? date('Y-m-d'),
            "waktu_$suffix" => date('H:i:s'),
            "lat_$suffix" => $this->form['location']['latitude'],
            "long_$suffix" => $this->form['location']['longitude'],
            "lokasi_$suffix" => $this->form['place'],
            "jarak_$suffix" => $this->form['distance'],
            "dok_$suffix" => $filename,
            "status" => "hadir",
        ];
        // dd($payload);

        if ($this->mode == 'in') {
            Absensi::create($payload);
            $this->emit('setResponse', [
                'type' => 'success',
                'message' => 'Absensi masuk berhasil'
            ]);
        } else {
            $data = null;
            if (!empty($this->missedOut)) {
                $data = Absensi::find($this->missedOut->id);

                $data->update($payload);
                $this->emit('setResponse', [
                    'type' => 'success',
                    'message' => 'Absensi keluar berhasil'
                ]);
            } else {
                $id = $this->currentRecord->id;
                $data = Absensi::find($id);
                $data->update($payload);
                $this->emit('setResponse', [
                    'type' => 'success',
                    'message' => 'Absensi keluar berhasil'
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.absensi.record');
    }

    public function mount($mode)
    {
        // $this->currentRecord = Absensi::getCurrentAbsensi(session('user')->id);

        // if (!empty($this->missedOut)) {
        //     $data = $this->missedOut;
        //     $this->idJam = $data->id_jam;

        //     $jam_kerja = JamKerja::find($data->id_jam);
        //     $jam_kerja_str = Carbon::parse($jam_kerja->mulai)->format('H:i') . ' - ' . Carbon::parse($jam_kerja->selesai)->format('H:i');
        //     $this->jamKerja = $jam_kerja_str;
        // }
        $currentRecord = Absensi::getCurrentAbsensi(session('user')->id);
        $this->currentRecord = $currentRecord;
        
        $missedOut = Absensi::getMissedOut(session('user')->id);
        $this->missedOut = $missedOut;

        if ($missedOut != null && $mode == 'out') {
            $this->disable_log = false;
            $jamKerja = JamKerja::find($missedOut->id_jam);
            $jamKerjaStr = Carbon::parse($jamKerja->mulai)->format('H:i') . ' - ' . Carbon::parse($jamKerja->selesai)->format('H:i');
            $this->jamKerja = $jamKerjaStr;
            $this->idJam = $missedOut->id_jam;
        } else {
            if ($currentRecord) {
                $hasOut = $currentRecord->has_out;
                if (!$hasOut && $mode == 'out') {
                    $this->disable_log = false;
                }
            } else {
                $this->disable_log = $mode != 'in';
            }
            $jamKerja = JamKerja::getAktif();
            $jamKerjaStr = Carbon::parse($jamKerja->mulai)->format('H:i') . ' - ' . Carbon::parse($jamKerja->selesai)->format('H:i');
            $this->jamKerja = $jamKerjaStr;
            $this->idJam = $jamKerja->id;
        }
        $this->mode = $mode;
    }
}