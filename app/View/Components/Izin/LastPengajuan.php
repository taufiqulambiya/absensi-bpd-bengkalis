<?php

namespace App\View\Components\Izin;

use App\Models\Izin;
use Carbon\Carbon;
use Illuminate\View\Component;

class LastPengajuan extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $data = null,
    )
    {
        //
    }

    private function get($data, $key, $default = null)
    {
        return Arr::get($data, $key, $default);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $user = session('user');
        // $query = Izin::where(['id_user' => $user->id])
        //     ->where('tgl_selesai', '>=', date('Y-m-d'));

        // $data = $query->where('status', '!=', 'rejected')
        //     ->orderBy('created_at', 'desc')
        //     ->get()
        //     ->each(function ($x) {
        //         $x->can_cancel = Carbon::parse($x->tgl_mulai)->isAfter(Carbon::now());
        //         $x->can_cancel = $x->status != 'accepted_pimpinan';
        //         $x->status = mapStatus($x->status);
        //     })
        //     ->last();
        // $izin = $query->where('status', 'accepted_pimpinan')
        //     ->get()
        //     ->each(function ($x) {
        //         $x->tgl_mulai = Carbon::parse($x->tgl_mulai)->format('d/m/Y');
        //         $x->tgl_selesai = Carbon::parse($x->tgl_selesai)->format('d/m/Y');
        //     })
        //     ->first();


        $data = $this->data;

        if (!$data) {
            return '';
        }

        $status_color = [
            'pending' => 'secondary',
            'accepted_kabid' => 'info',
            'accepted_admin' => 'primary',
            'accepted_pimpinan' => 'success',
            'rejected' => 'danger',
        ];
        $status_text = [
            'pending' => 'Menunggu Persetujuan',
            'accepted_kabid' => 'Disetujui Kabid',
            'accepted_admin' => 'Disetujui Admin',
            'accepted_pimpinan' => 'Disetujui Pimpinan',
            'rejected' => 'Ditolak',
        ];

        $data->status_color = $status_color[$data->status];
        $data->status_text = $status_text[$data->status];
        $tgl_mulai = Carbon::parse($data->tgl_mulai);
        $tgl_selesai = Carbon::parse($data->tgl_selesai);
        $data->tgl_mulai = $tgl_mulai->format('d/m/Y');
        $data->tgl_selesai = $tgl_selesai->format('d/m/Y');
        $data->total_hari = $tgl_mulai->diffInDays($tgl_selesai) + 1 . ' hari';

        return view('components.izin.last-pengajuan', compact('data'));
    }
}
