<?php

namespace App\View\Components\DinasLuar;

use App\Models\Cuti;
use App\Models\DinasLuar;
use App\Models\Izin;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\View\Component;

class ListComing extends Component
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
        $user = session('user');
        $role = $user->level;
        $data = [];
        $disable_dates = [];
        if ($role == 'pegawai') {
            $data = DinasLuar::where('id_user', $user->id)->where('selesai', '>=', date('Y-m-d'))->get();
        }
        if ($role == 'admin' OR $role == 'atasan') {
            $data = DinasLuar::with('user')->where('selesai', '>=', date('Y-m-d'))->get()->each(function ($x) {
                $x->durasi = Carbon::parse($x->mulai)->diff(Carbon::parse($x->selesai))->days;
            });
        }
        return view('components.dinas-luar.list-coming', compact('data', 'role', 'disable_dates'));
    }
}
