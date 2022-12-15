<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Izin as ModelsIzin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Izin extends BaseController
{
    public function index()
    {
        $user = session()->get('user');
        $count_pending = ModelsIzin::where('status', 'pending')->get()->count();

        $data = [
            'level' => $user->level,
            'is_waiting' => $count_pending > 0,
            'izin_aktif' => ModelsIzin::with('user')
                ->where('status', 'pending')
                ->orWhere('status', 'accepted_kabid')
                ->where('tgl_selesai', '>=', date('Y-m-d'))
                ->get()
                ->each(function ($x) {
                    $x->durasi = Carbon::parse($x->tgl_mulai)->diffInDays($x->tgl_selesai) . ' Hari';
                    $x->status_class = $this->statusColor($x->status);
                    $x->status_text = $this->statusGetter($x->status);
                    $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
                }),
            'izin_terlewat' => ModelsIzin::with('user')
                ->where('tgl_selesai', '<=', date('Y-m-d'))
                ->where('status', '!=', 'accepted_admin')
                ->get()
                ->each(function ($x) {
                    $x->durasi = Carbon::parse($x->tgl_mulai)->diffInDays($x->tgl_selesai) . ' Hari';
                    $x->status_class = $this->statusColor($x->status);
                    $x->status_text = $this->statusGetter($x->status);
                    $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
                }),
            'izin_selesai' => ModelsIzin::with('user')
                ->where('status', 'accepted_admin')
                ->orWhere('status', 'rejected')
                ->orderBy('created_at', 'desc')
                ->get()
                ->each(function ($x) {
                    $x->durasi = Carbon::parse($x->tgl_mulai)->diffInDays($x->tgl_selesai) . ' Hari';
                    $x->status_class = $this->statusColor($x->status);
                    $x->status_text = $this->statusGetter($x->status);
                    $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
                }),
        ];

        return view('panel.admin.izin.izin', $data);
    }
}
