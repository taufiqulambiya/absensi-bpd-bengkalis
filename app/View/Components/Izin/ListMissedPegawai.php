<?php

namespace App\View\Components\Izin;

use App\Models\Izin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class ListMissedPegawai extends Component
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

    private function statusGetter($status)
    {
        switch ($status) {
            case 'accepted_kabid':
                return 'Diterima oleh Kabid';
            case 'accepted_admin':
                return 'Diterima oleh Admin';
            case 'rejected':
                return 'Ditolak';
            default:
                return 'Pending';
        }
    }

    private function statusColor($status)
    {
        switch ($status) {
            case 'accepted_kabid':
                return 'warning';
            case 'accepted_admin':
                return 'success';
            case 'rejected':
                return 'danger';
            default:
                return 'secondary';
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
        $data = Izin::with('user')
            ->where([
                ['id_user', $user->id],
                ['tgl_mulai', '<=', date('Y-m-d')],
                [function ($x) {
                    return $x->where('status', 'pending')
                        ->orWhere('status', 'accepted_kabid');
                }]
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(function ($x) {
                $x->durasi = Carbon::parse($x->tgl_mulai)->diffInDays($x->tgl_selesai) . ' Hari';
                $x->status_text = $this->statusGetter($x->status);
                $x->status_class = $this->statusColor($x->status);
                $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
                if ($x->status == 'pending') {
                    $tgl_selesai = Carbon::parse($x->tgl_selesai);
                    $current_date = Carbon::parse(date('Y-m-d'));
                    $x->terlewat = $current_date->isAfter($tgl_selesai);
                }
            });
        return view('components.izin.list-missed-pegawai', compact('data'));
    }
}
