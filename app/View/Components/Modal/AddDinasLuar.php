<?php

namespace App\View\Components\Modal;

use App\Models\Cuti;
use App\Models\Izin;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\View\Component;

class AddDinasLuar extends Component
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $users = User::with('bidangs')->where('level', '!=', 'atasan')->get()->each(function ($x) {
            $tgl_izin = [];
            $izin = Izin::where('id_user', $x->id)
                ->where('status', 'accepted_pimpinan')->get();
            foreach ($izin as $item) {
                $period = CarbonPeriod::between(Carbon::parse($item->tgl_mulai), Carbon::parse($item->tgl_selesai));
                foreach ($period as $p) {
                    array_push($tgl_izin, $p->format('Y-m-d'));
                }
            }
            $x->tgl_izin = $tgl_izin;
            $tgl_cuti = [];
            $izin = Cuti::where('id_user', $x->id)->where('status', 'accepted_pimpinan')->get();
            foreach ($izin as $item) {
                $tgl_cutis = explode(',', $item->tanggal);
                $tgl_cuti = array_merge($tgl_cuti, $tgl_cutis);
            }
            $x->tgl_cuti = $tgl_cuti;
        });
        return view('components.modal.add-dinas-luar', compact('users'));
    }
}
