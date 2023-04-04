<?php

namespace App\View\Components\Absensi;

use App\Models\JamKerja;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\View\Component;

class LogMasuk extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $disable_log = false;
    public function __construct($disableLog)
    {
        $this->disable_log = $disableLog;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $shift = JamKerja::where('status', 'aktif')
            ->where('days', 'like', '%' . $days[date('w')] . '%')
            ->get()
            ->each(function ($x) {
                $x->formatted = Carbon::parse($x->mulai)->format('H:i') . ' - ' . Carbon::parse($x->selesai)->format('H:i \W\I\B');
                $current_time = Carbon::now();
                $x->is_absen_time = $current_time->between(Carbon::parse($x->mulai), Carbon::parse($x->selesai));
            });
        $jam_kerja = null;
        if ($shift->count() > 0) {
            $jam_kerja = $shift->first();
        }
        // dd(count($jam_kerja));
        $setting = Settings::first();
        $user = session('user');

        return view('components.absensi.log-masuk', compact('jam_kerja', 'setting', 'user'));
    }
}
