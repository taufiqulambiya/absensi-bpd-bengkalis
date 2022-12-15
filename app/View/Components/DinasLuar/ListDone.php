<?php

namespace App\View\Components\DinasLuar;

use App\Models\DinasLuar;
use Carbon\Carbon;
use Illuminate\View\Component;

class ListDone extends Component
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
        if ($role == 'pegawai') {
            $data = DinasLuar::with('user')
                ->where('id_user', $user->id)
                ->where('selesai', '<', date('Y-m-d'))
                ->get()
                ->each(function($x){
                    $x->durasi = Carbon::parse($x->mulai)->diff(Carbon::parse($x->selesai))->days;
                });
        }
        if ($role == 'admin' OR $role == 'atasan') {
            $data = DinasLuar::with('user')
                ->where('selesai', '<', date('Y-m-d'))
                ->get()
                ->each(function($x){
                    $x->durasi = Carbon::parse($x->mulai)->diff(Carbon::parse($x->selesai))->days;
                    $x->mulai = Carbon::parse($x->mulai)->format('d/m/Y');
                    $x->selesai = Carbon::parse($x->selesai)->format('d/m/Y');
                });
        }
        return view('components.dinas-luar.list-done', compact('data', 'role'));
    }
}
