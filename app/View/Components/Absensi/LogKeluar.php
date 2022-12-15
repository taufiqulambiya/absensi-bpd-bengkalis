<?php

namespace App\View\Components\Absensi;

use App\Models\Absensi;
use App\Models\JamKerja;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\View\Component;

class LogKeluar extends Component
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
        $shift = JamKerja::where('status', 'aktif')
            ->get()
            ->each(function ($x) {
                $x->formatted = Carbon::parse($x->mulai)->format('H:i') . ' - ' . Carbon::parse($x->selesai)->format('H:i \W\I\B');
                $current_time = Carbon::now();
                $mulai = Carbon::parse($x->mulai);
                $selesai = Carbon::parse($x->selesai);
                $x->is_absen_time = $current_time->between($mulai, $selesai);
            })->filter(function($x){
                $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
                $days_used = explode(', ', $x->days);
                $current_day_index = date('w') - 1;
                $current_day = $days[$current_day_index];

                return array_search($current_day, $days_used);
            });
        $jam_kerja = null;
        if ($shift->count() > 0) {
            $jam_kerja =$shift->first();
        }
        $setting = Settings::first();
        $user = session('user');
        $absensi = Absensi::with('shift')
            ->where('id_user', $user->id)
            ->get()
            ->each(function ($x) {
                $x->has_keluar = Carbon::parse($x->waktu_keluar)->isMidnight() == false;
                $x->terlewat = date('Y-m-d') > $x->tanggal ? 1 : 0;
            })
            ->last();
        $current = Absensi::with('shift')
            ->where(['id_user' => $user->id, 'tanggal' => date('Y-m-d')])
            ->where('waktu_keluar', '!=', '00:00:00')
            ->get()
            ->each(function ($x) {
                $x->formatted_shift = Carbon::parse($x->shift->mulai)->format('H:i') . ' - ' . Carbon::parse($x->shift->selesai)->format('H:i \W\I\B');
            })
            ->first();
        return view('components.absensi.log-keluar', compact('jam_kerja', 'setting', 'user', 'absensi', 'current'));
    }
}
