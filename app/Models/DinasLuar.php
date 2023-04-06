<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DinasLuar extends Model
{
    use HasFactory;
    protected $table = 'dinas_luar';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    static function lastPengajuan($user_id)
    {
        $data = DinasLuar::where('id_user', $user_id)
            ->where([
                ['mulai', '<=', date('Y-m-d')],
                ['selesai', '>=', date('Y-m-d')],
            ])
            ->get()
            ->last();
        return $data;
    }

    static function getByTab($tab)
    {
        $user = session('user');
        $level = $user->level;

        $dateCond = [
            'active' => [
                ['mulai', '>=', date('Y-m-d')],
            ],
            'done' => [
                ['selesai', '<', date('Y-m-d')],
            ],
        ];

        $actions = [
            'admin.active' => ['edit', 'delete'],
            'admin.done' => ['print'],
            'atasan.active' => ['print'],
            'atasan.done' => ['print'],
            'pegawai.active' => ['print'],
            'pegawai.done' => ['print'],
        ];

        $q = DinasLuar::where($dateCond[$tab])
            ->with('user');
        if($level == 'pegawai') {
            $q->where('id_user', $user->id);
        }
        $data = $q->get()
            ->each(function ($x) use ($level, $actions, $tab) {
                $x->durasi = Carbon::parse($x->mulai)->diffInDays(Carbon::parse($x->selesai)) + 1 . ' hari';
                $x->mulai = Carbon::parse($x->mulai)->format('d/m/Y');
                $x->selesai = Carbon::parse($x->selesai)->format('d/m/Y');
                $x->action = $actions[$level . '.' . $tab];
            });

        return $data;
    }

    static function getByRange($user_id, $start_date, $end_date)
    {
        $data = DinasLuar::where('id_user', $user_id)
            ->where([
                ['mulai', '>=', $start_date],
                ['selesai', '<=', $end_date],
            ])
            ->get();

        return $data;
    }

    static function checkAllowAdd($user_id, $start_date, $end_date)
    {
        $izin = Izin::where('id_user', $user_id)
            ->where([
                ['tgl_mulai', '<=', $start_date],
                ['tgl_selesai', '>=', $end_date],
                ['status', 'accepted_pimpinan']
            ])
            ->get();

        $cuti = Cuti::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            ->get()
            ->filter(function ($x) use ($start_date, $end_date) {
                $tgls = explode(',', $x->tgl);
                $start = Carbon::parse($tgls[0]);
                $end = Carbon::parse($tgls[count($tgls) - 1]);
                return $start->lessThanOrEqualTo($start_date) && $end->greaterThanOrEqualTo($end_date);
            })
            ->values();

        $dinas = DinasLuar::where('id_user', $user_id)
            ->where([
                ['mulai', '<=', $start_date],
                ['selesai', '>=', $end_date],
            ])
            ->get();
        $message = '';
        if ($izin->count() > 0) {
            $message = 'Pegawai sedang izin pada tanggal tersebut';
        } else if ($cuti->count() > 0) {
            $message = 'Pegawai sedang cuti pada tanggal tersebut';
        } else if ($dinas->count() > 0) {
            $message = 'Pegawai sedang dinas luar pada tanggal tersebut';
        }

        return [
            'allow' => $izin->count() + $cuti->count() + $dinas->count() == 0,
            'message' => $message,
        ];
    }
}