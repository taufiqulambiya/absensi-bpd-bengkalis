<?php

namespace App\View\Components\Izin;

use App\Models\Izin;
use Carbon\Carbon;
use Illuminate\View\Component;

class DetailCard extends Component
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
        $has_izin = Izin::where(['id_user' => $user->id])
                ->where('tgl_mulai', '<=', date('Y-m-d'))
                ->where('tgl_selesai', '>=', date('Y-m-d'))
                ->where('status', 'accepted_pimpinan')
                ->get()
                ->each(function ($x) {
                    $x->tgl_mulai = Carbon::parse($x->tgl_mulai)->format('d/m/Y');
                    $x->tgl_selesai = Carbon::parse($x->tgl_selesai)->format('d/m/Y');
                })
                ->first();
        return view('components.izin.detail-card', compact('has_izin'));
    }
}
