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
        
        $data = DinasLuar::where($dateCond[$tab])
            ->with('user')
            ->get()
            ->each(function ($x) use ($level, $actions, $tab) {
                $x->durasi = Carbon::parse($x->mulai)->diffInDays(Carbon::parse($x->selesai)) + 1 . ' hari';
                $x->mulai = Carbon::parse($x->mulai)->format('d/m/Y');
                $x->selesai = Carbon::parse($x->selesai)->format('d/m/Y');
                $x->action = $actions[$level . '.' . $tab];
            });

        return $data;
    }
}
