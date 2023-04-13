<?php

namespace App\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Izin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'izin';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    static function getIzinAktif($user_id)
    {
        $data = Izin::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            ->where('tgl_mulai', '<=', date('Y-m-d'))
            ->where('tgl_selesai', '>=', date('Y-m-d'))
            ->get()
            ->each(function ($x) {
                $x->total = CarbonPeriod::create($x->tgl_mulai, $x->tgl_selesai)->count() . ' hari';
                $x->tgl_mulai = Carbon::parse($x->tgl_mulai)->format('d/m/Y');
                $x->tgl_selesai = Carbon::parse($x->tgl_selesai)->format('d/m/Y');
            })
            ->last();
        return $data;
    }

    static function hasIzinAktif($user_id)
    {
        return Izin::getIzinAktif($user_id) != null;
    }

    static function lastPengajuan($user_id)
    {
        $data = Izin::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            ->where('tgl_mulai', '<=', date('Y-m-d'))
            ->where('tgl_selesai', '>=', date('Y-m-d'))
            ->get()
            ->last();
        return $data;
    }

    static function getAllowAjukan($user_id)
    {
        $count = Izin::where('id_user', $user_id)
            ->whereNotIn('status', ['accepted_pimpinan', 'rejected'])
            ->where('tgl_mulai', '>=', date('Y-m-d'))
            ->count();
        return $count == 0;
    }

    static function getByStatus($status)
    {
        $statusCond = [
            'pegawai.pending' => ['pending', 'accepted_kabid', 'accepted_admin'],
            'pegawai.missed' => ['pending', 'accepted_kabid', 'accepted_admin'],
            'pegawai.done' => ['accepted_pimpinan', 'rejected'],
            'kabid.pending' => ['pending'],
            'kabid.missed' => ['pending'],
            'kabid.done' => ['accepted_kabid', 'accepted_admin', 'accepted_pimpinan', 'rejected'],
            'admin.pending' => ['accepted_kabid'],
            'admin.missed' => ['accepted_kabid'],
            'admin.done' => ['accepted_admin', 'accepted_pimpinan', 'rejected'],
            'atasan.pending' => ['accepted_admin'],
            'atasan.missed' => ['accepted_admin'],
            'atasan.done' => ['accepted_pimpinan', 'rejected'],
        ];

        $dateCond = [
            'pegawai.pending' => [['tgl_mulai', '>', date('Y-m-d')]],
            'pegawai.missed' => [['tgl_mulai', '<', date('Y-m-d')]],
            'pegawai.done' => [
                // ['tgl_mulai', '<', date('Y-m-d')]
            ],
            'kabid.pending' => [['tgl_mulai', '>', date('Y-m-d')]],
            'kabid.missed' => [['tgl_mulai', '<', date('Y-m-d')]],
            'kabid.done' => [],
            'admin.pending' => [['tgl_mulai', '>', date('Y-m-d')]],
            'admin.missed' => [['tgl_mulai', '<', date('Y-m-d')]],
            'admin.done' => [],
            'atasan.pending' => [['tgl_mulai', '>', date('Y-m-d')]],
            'atasan.missed' => [['tgl_mulai', '<', date('Y-m-d')]],
            'atasan.done' => [],
        ];

        $actions = [
            'pegawai.pending' => ['edit', 'delete', 'track'],
            'pegawai.missed' => ['delete', 'track'],
            'pegawai.done' => ['print', 'track'],
            'kabid.pending' => ['accept', 'reject', 'track'],
            'kabid.missed' => ['track', 'delete'],
            'kabid.done' => ['print', 'track'],
            'admin.pending' => ['accept', 'reject', 'track'],
            'admin.missed' => ['track', 'delete'],
            'admin.done' => ['print', 'track'],
            'atasan.pending' => ['accept', 'reject', 'track'],
            'atasan.missed' => ['track', 'delete'],
            'atasan.done' => ['print', 'track'],
        ];

        $user = session('user');
        $level = $user->level;
        $status = $level . '.' . $status;
        $query = Izin::with('user')
            ->whereIn('status', $statusCond[$status])
            ->where($dateCond[$status]);


        switch ($level) {
            case 'pegawai':
                $query->where('id_user', $user->id);
                break;
            case 'kabid':
                $bidang_kabid = $user->bidang;
                $query->whereHas('user', function ($q) use ($user) {
                    $bidang_kabid = $user->bidang;
                    $q->where('bidang', $bidang_kabid);
                });
                break;
            default:
                # code...
                break;
        }


        $data = $query
            ->get()
            ->sortByDesc('created_at')
            ->map(function ($item) {
                $item->status_text = formatStatusCuti($item->status);
                $item->status_color = formatStatusCutiColor($item->status);
                $item->formatted_tgl_mulai = date('d/m/Y', strtotime($item->tgl_mulai));
                $item->formatted_tgl_selesai = date('d/m/Y', strtotime($item->tgl_selesai));

                $total = getDurationExceptWeekend($item->tgl_mulai, $item->tgl_selesai);
                $start = Carbon::parse($item->tgl_mulai);
                $end = Carbon::parse($item->tgl_selesai);
                // $item->formatted_durasi = $total - $weekends . ' hari';
                $item->formatted_durasi = $total . ' hari';

                return $item;
            });

        foreach ($data as $key => $value) {
            $value->actions = $actions[$status];
        }

        return $data;
    }

    static public function getDisableDates($user_id)
    {
        // get izin with status of 'accepted_pimpinan' and the dates are between tgl_mulai and tgl_selesai but after today
        $izin = Izin::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            ->where('tgl_mulai', '>=', date('Y-m-d'))
            ->get();
        $cuti = Cuti::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            // ->where('tanggal', 'like', date('Y-m-d') . '%')
            ->where('mulai', '>=', date('Y-m-d'))
            ->get();
        $dinas = DinasLuar::where('id_user', $user_id)
            ->where('mulai', '>=', date('Y-m-d'))
            ->get();

        $dates = [];
        // foreach ($data as $key => $value) {
        //     $period = CarbonPeriod::create($value->tgl_mulai, $value->tgl_selesai);
        //     foreach ($period as $date) {
        //         $dates[] = $date->format('Y-m-d');
        //     }
        // }
        foreach ($izin as $key => $value) {
            $period = CarbonPeriod::create($value->tgl_mulai, $value->tgl_selesai);
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }
        foreach ($cuti as $key => $value) {
            $period = CarbonPeriod::create($value->mulai, $value->selesai);
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }
        foreach ($dinas as $key => $value) {
            $period = CarbonPeriod::create($value->mulai, $value->selesai);
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }
        $dates = array_unique($dates);
        sort($dates);
        return $dates;
    }

    static function isInRange($start, $end, $disables = [])
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        $period = CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            if (in_array($date->format('d/m/Y'), $disables)) {
                return true;
            }
            if (in_array($date->format('Y-m-d'), $disables)) {
                return true;
            }
        }
        // dd($disables);
        return false;
    }
}