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
    static function getAktif($user_id) {
        $data = DinasLuar::where('id_user', $user_id)
            ->where([
                ['mulai', '<=', date('Y-m-d')],
                ['selesai', '>=', date('Y-m-d')],
            ])
            ->get()
            ->each(function ($x) {
                // $x->durasi = Carbon::parse($x->mulai)->diffInDays(Carbon::parse($x->selesai)) + 1 . ' hari';
                $mulai = Carbon::parse($x->mulai);
                $selesai = Carbon::parse($x->selesai);
                $x->mulai = $mulai->format('d/m/Y');
                $x->selesai = $selesai->format('d/m/Y');
                $diff = $mulai->diffInDays($selesai);
                $weekends = 0;
                for($i = 0; $i <= $diff; $i++) {
                    $day = $mulai->addDay()->dayOfWeek;
                    if($day == 0 || $day == 6) {
                        $weekends++;
                    }
                }
                $x->durasi = $diff - $weekends . ' hari kerja';

            })
            ->last();
        return $data;
    }
    static function hasAktif($user_id) {
        return DinasLuar::getAktif($user_id) != null;
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
                $mulai = Carbon::parse($x->mulai);
                $selesai = Carbon::parse($x->selesai);
                $x->mulai = $mulai->format('d/m/Y');
                $x->selesai = $selesai->format('d/m/Y');
                $diff = $mulai->diffInDays($selesai);
                $weekends = 0;
                for($i = 0; $i <= $diff; $i++) {
                    $day = $mulai->addDays($i);
                    if ($day->isWeekend()) {
                        $weekends++;
                    }
                }
                $x->durasi = $diff - $weekends + 1 . ' hari kerja';
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

    static function checkAllowAdd($user_id, $start_date, $end_date, $editId)
    {
        $izin = Izin::where('id_user', $user_id)
            // ->where([
            //     ['tgl_mulai', '<=', $start_date],
            //     ['tgl_selesai', '>=', $end_date],
            //     ['status', 'accepted_pimpinan']
            // ])
            ->where('status', 'accepted_pimpinan')
            ->get()
            ->filter(function ($x) use ($start_date, $end_date) {
                $start = Carbon::parse($x->tgl_mulai);
                $end = Carbon::parse($x->tgl_selesai);
                return rangeCheck($start, $end, [$start_date, $end_date]);
            });

        $cuti = Cuti::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            // ->where('mulai', '<=', $start_date)
            // ->where('selesai', '>=', $end_date)
            ->get()
            ->filter(function ($x) use ($start_date, $end_date) {
                $start = Carbon::parse($x->mulai);
                $end = Carbon::parse($x->selesai);
                return rangeCheck($start, $end, [$start_date, $end_date]);
            });

        $dinas = DinasLuar::where('id_user', $user_id)
            // ->where('mulai', '<=', $start_date)
            // ->where('selesai', '>=', $end_date)
            ->when(!empty($editId), function ($q) use ($editId) {
                $q->where('id', '!=', $editId);
            })
            ->get()
            ->filter(function ($x) use ($start_date, $end_date) {
                $start = Carbon::parse($x->mulai);
                $end = Carbon::parse($x->selesai);
                return rangeCheck($start, $end, [$start_date, $end_date]);
            });
            
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