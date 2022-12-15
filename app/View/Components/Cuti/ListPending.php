<?php

namespace App\View\Components\Cuti;

use App\Models\Cuti;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ListPending extends Component
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

    private function filterDate($tgl)
    {
        $tanggal = explode(',', $tgl);
        $current = date('Y-m-d');
        $first = $tanggal[0];
        return $first > $current;
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
            $data = Cuti::with('user')->where('id_user', $user->id)
                ->where('status', '!=', 'accepted_pimpinan')
                ->where('status', '!=', 'rejected')
                ->get()
                ->filter(function ($x) {
                    return $this->filterDate($x->tanggal);
                })
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
                ->where('status', 'pending')
                ->get()
                ->filter(function ($x) {
                    return $this->filterDate($x->tanggal);
                })
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
                ->where('status', 'accepted_kabid')
                ->get()
                ->filter(function ($x) {
                    return $this->filterDate($x->tanggal);
                })
                ->each(function ($x) {
                    $x->jenis = mapJenisCuti($x->jenis);
                    $x->status = mapStatus($x->status);
                    $x->tanggal = explode(',', $x->tanggal);
                });

        }
        if ($role == 'atasan') {
            $data = Cuti::with('user')
                ->where('status', 'accepted_admin')
                ->get()
                ->filter(function ($x) {
                    return $this->filterDate($x->tanggal);
                })
                ->each(function ($x) {
                    $x->jenis = mapJenisCuti($x->jenis);
                    $x->status = mapStatus($x->status);
                    $x->tanggal = explode(',', $x->tanggal);
                });
        }
        return view('components.cuti.list-pending', compact('data'));
    }
}
