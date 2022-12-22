<?php

namespace App\View\Components\Cuti;

use App\Models\Cuti;
use Illuminate\View\Component;

class ListMissed extends Component
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

    private function filterDate($x)
    {
        $tanggal = explode(',', $x->tanggal);
        $current = date('Y-m-d');
        $first = $tanggal[0];
        return $current > $first;
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
            $data = Cuti::where([
                    ['id_user', $user->id],
                    [function ($x) {
                        return $x->where('status', 'pending')
                            ->orWhere('status', 'accepted_kabid')
                            ->orWhere('status', 'accepted_admin');
                    }]
                ])
                ->get()
                ->filter(function ($x) {
                    return $this->filterDate($x);
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
                    return $this->filterDate($x);
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
                    return $this->filterDate($x);
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
        if ($role == 'atasan') {
            $data = Cuti::with('user')
                ->where('status', 'accepted_admin')
                ->get()
                ->filter(function ($x) {
                    return $this->filterDate($x);
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
        return view('components.cuti.list-missed', compact('data'));
    }
}
