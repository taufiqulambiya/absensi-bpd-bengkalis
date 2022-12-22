<?php

namespace App\View\Components\Cuti;

use App\Models\Cuti;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\View\Component;

class JatahCutiCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function totalMapper($x)
    {
        $tanggal = explode(',', $x->tanggal);
        $x->total_tahunan = 0;
        $x->total_besar = 0;
        $x->total_melahirkan = 0;
        $x->total_penting = 0;
        $x->total_ctln = 0;
        switch ($x->jenis) {
            case 'tahunan':
                $x->total_tahunan = count($tanggal);
                break;
            case 'besar':
                $x->total_besar = count($tanggal);
                break;
            case 'melahirkan':
                $x->total_melahirkan = count($tanggal);
                break;
            case 'penting':
                $x->total_penting = count($tanggal);
                break;
            case 'ctln':
                $x->total_ctln = count($tanggal);
                break;
            default:
                $x->total_tahunan = count($tanggal);
                break;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $user = session('user');
        $level = $user->level;
        $setting = Settings::first();
        $data = $setting;
        $data->is_waiting = false;
        $data->has_cuti = false;

        if ($level == 'pegawai') {
            $count_queue = Cuti::where([
                ['id_user', $user->id],
                [function ($q) {
                    return $q->where('status', '!=', 'accepted_pimpinan')->orWhere('status', '!=', 'rejected');
                }]
            ])
                ->get()->count();
            $is_waiting = $count_queue > 0;
            $all_cuti = Cuti::where(['id_user' => $user->id, 'status' => 'accepted_pimpinan'])->get()
                ->filter(function ($x) {
                    return Carbon::parse($x->created_at)->year == date('Y');
                })
                ->each(function ($x) {
                    return $this->totalMapper($x);
                });

            $data->jatah_cuti_tahunan =  $setting->jatah_cuti_tahunan - $all_cuti->sum('total_tahunan');
            $data->jatah_cuti_besar = $setting->jatah_cuti_besar - $all_cuti->sum('total_besar');
            $data->jatah_cuti_melahirkan = $setting->jatah_cuti_melahirkan - $all_cuti->sum('total_melahirkan');
            $data->jatah_cuti_penting = $setting->jatah_cuti_penting - $all_cuti->sum('total_penting');
            $data->jatah_cuti_ctln = $setting->jatah_cuti_ctln - $all_cuti->sum('total_ctln');

            $data->is_waiting = $is_waiting;

            $has_cuti = Cuti::where('id_user', $user->id)
                ->where('status', 'accepted_admin')
                ->get()
                ->filter(function ($x) {
                    $tanggal = explode(',', $x->tanggal);
                    $found = array_filter($tanggal, function ($y) {
                        $current_date = date('Y-m-d');
                        return $y > $current_date;
                    });
                    return count($found) > 0;
                })
                ->each(function ($x) {
                    $x->tanggal = array_map(function ($item) {
                        return Carbon::parse($item)->format('d/m/Y');
                    }, explode(',', $x->tanggal));
                    $x->total = count($x->tanggal) . ' Hari';
                })
                ->last();
            $data->has_cuti = !empty($has_cuti);
        }

        return view('components.cuti.jatah-cuti-card', compact('data', 'level'));
    }
}
