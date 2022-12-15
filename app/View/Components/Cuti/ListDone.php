<?php

namespace App\View\Components\Cuti;

use App\Models\Cuti;
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
    private function filterUser($x)
    {
        return $x->where('jabatan', session('user')->jabatan);
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
            $data = Cuti::with('user')
                ->where('id_user', $user->id)
                ->where('status', 'accepted_pimpinan')
                ->orWhere('status', 'rejected')
                ->get()
                ->each(function ($x) {
                    $x->jenis = mapJenisCuti($x->jenis);
                    $x->status = mapStatus($x->status);
                    $x->tanggal = explode(',', $x->tanggal);
                });
        }
        if ($role == 'kabid') {
            $data = Cuti::with(['user' => function ($x) {
                $this->filterUser($x);
            }])
                ->where('status', '!=', 'pending')
                ->get()
                ->filter(function ($x) {
                    return $x->user;
                })
                ->each(function ($x) {
                    $x->jenis = mapJenisCuti($x->jenis);
                    $x->status = mapStatus($x->status);
                    $x->tanggal = explode(',', $x->tanggal);
                });
        }
        if ($role == 'admin') {
            $data = Cuti::with('user')
                ->where('status', 'accepted_admin')
                ->where('status', 'rejected')
                ->get()
                ->each(function ($x) {
                    $x->jenis = mapJenisCuti($x->jenis);
                    $x->status = mapStatus($x->status);
                    $x->tanggal = explode(',', $x->tanggal);
                });
        }
        if ($role == 'atasan') {
            $data = Cuti::with('user')
                ->where('status', 'accepted_pimpinan')
                ->orWhere('status', 'rejected')
                ->get()
                ->each(function ($x) {
                    $x->jenis = mapJenisCuti($x->jenis);
                    $x->status = mapStatus($x->status);
                    $x->tanggal = explode(',', $x->tanggal);
                });
        }
        return view('components.cuti.list-done', compact('data'));
    }
}
