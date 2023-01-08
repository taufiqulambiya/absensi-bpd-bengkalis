<?php

namespace App\View\Components\Izin;

use App\Models\Izin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $user = session('user');
        $role = $user->level;

        $data = Izin::with('user')
            ->where([
                ['id_user', $user->id],
                ['tgl_mulai', '<=', date('Y-m-d')],
                [function ($q) {
                    return $q->where('status', '!=', 'accepted_pimpinan')->where('status', '!=', 'rejected');
                }]
            ])
            ->orderBy('created_at', 'desc')->get()->each(function ($x) {
                $x->durasi = Carbon::parse($x->tgl_mulai)->diffInDays($x->tgl_selesai) . ' Hari';
                $x->status = mapStatus($x->status);
                $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
                if ($x->status == 'pending') {
                    $tgl_selesai = Carbon::parse($x->tgl_selesai);
                    $current_date = Carbon::parse(date('Y-m-d'));
                    $x->terlewat = $current_date->isAfter($tgl_selesai);
                }
            });

        // dd($data);

        if ($role == 'kabid') {
            $data = Izin::with(['user' => function ($q) {
                $bidang_id = session('user')->bidang;
                return $q->user->bidang == $bidang_id;
            }])
                ->where('status', 'pending')->where('tgl_mulai', '<=', date('Y-m-d'))
                ->get()
                ->filter(function ($x) {
                    return $x->user;
                })
                ->each(function ($x) {
                    $x->durasi = Carbon::parse($x->tgl_mulai)->diff(Carbon::parse($x->tgl_selesai))->d . ' Hari';
                    $x->status = mapStatus($x->status);
                    $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
                    if ($x->status == 'pending') {
                        $tgl_selesai = Carbon::parse($x->tgl_selesai);
                        $current_date = Carbon::parse(date('Y-m-d'));
                        $x->terlewat = $current_date->isAfter($tgl_selesai);
                    }
                });
        }

        if ($role == 'admin') {
            $data = Izin::with('user')->where('tgl_mulai', '<=', date('Y-m-d'))->where('status', 'accepted_kabid')->get()
                ->each(function ($x) {
                    $x->durasi = Carbon::parse($x->tgl_mulai)->diff(Carbon::parse($x->tgl_selesai))->d . ' Hari';
                    $x->status = mapStatus($x->status);
                    $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
                    if ($x->status == 'pending') {
                        $tgl_selesai = Carbon::parse($x->tgl_selesai);
                        $current_date = Carbon::parse(date('Y-m-d'));
                        $x->terlewat = $current_date->isAfter($tgl_selesai);
                    }
                });
        }

        if ($role == 'atasan') {
            $data =  Izin::with('user')->where('status', 'accepted_admin')->where('tgl_mulai', '<=', date('Y-m-d'))->get();
        }
        return view('components.izin.list-missed', compact('data', 'role'));
    }
}
