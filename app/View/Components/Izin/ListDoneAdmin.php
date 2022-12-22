<?php

namespace App\View\Components\Izin;

use App\Models\Izin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class ListDoneAdmin extends Component
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
        $data = Izin::with('user')
            ->where([
                ['tgl_mulai', '>', date('Y-m-d')],
                [function ($x) {
                    return $x->where('status', 'accepted_admin')
                        ->orWhere('status', 'rejected');
                }]
            ])
            ->get()
            ->each(function ($x) {
                $x->durasi = Carbon::parse($x->tgl_mulai)->diff(Carbon::parse($x->tgl_selesai))->d . ' Hari';
                $x->status_text = $this->statusGetter($x->status);
                $x->status_class = $this->statusColor($x->status);
                $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
                if ($x->status == 'pending') {
                    $tgl_selesai = Carbon::parse($x->tgl_selesai);
                    $current_date = Carbon::parse(date('Y-m-d'));
                    $x->terlewat = $current_date->isAfter($tgl_selesai);
                }
            });
        $is_with_rejected = $data->filter(function ($x) {
            return $x->status == 'rejected';
        })->count() > 0;


        return view('components.izin.list-done-admin', compact('data', 'is_with_rejected'));
    }
}
