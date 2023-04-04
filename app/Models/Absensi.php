<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function shift()
    {
        return $this->hasOne(JamKerja::class, 'id', 'id_jam');
    }

    static function getCurrentAbsensi($user_id)
    {
        $data = Absensi::with('shift')
            ->where([
                ['id_user', $user_id],
                ['tanggal', date('Y-m-d')],
            ])
            ->get()
            ->map(function($item) {
                $item->formatted_shift = Carbon::parse($item->shift->mulai)->format('H:i') . ' - ' . Carbon::parse($item->shift->selesai)->format('H:i') . ' WIB';
                $item->formatted_tanggal = Carbon::parse($item->tanggal)->format('d/m/Y');
                
                $waktu_keluar = Carbon::parse($item->waktu_keluar);
                $item->has_out = $waktu_keluar->format('H:i') != '00:00';

                return $item;
            })
            ->last();
        return $data;
    }

    static function getMissedOut($user_id)
    {
        $data = Absensi::with('shift')
            ->where('id_user', $user_id)
            ->get()
            ->filter(function($item) {
                $item->has_out = Carbon::parse($item->waktu_keluar)->format('H:i') != '00:00';
                $current_date = Carbon::now();
                $item_date = Carbon::parse($item->tanggal);
                $item->is_missed_out = $item->has_out == false && $current_date->diffInDays($item_date) > 0;

                return $item->is_missed_out;
            })
            ->last();
        return $data;
    }

    static function getRiwayat($isPaginate = false) {
        $userId = session('user')->id;
        $data = Absensi::with(['user', 'user.bidangs', 'shift'])
            ->where('id_user', $userId)
            ->orderBy('tanggal', 'desc');

        if ($isPaginate) {
            $data = $data->paginate(10);
        } else {
            $data = $data->paginate(10)
            ->map(function ($x) {
                return formatAbsensi($x);
            });
        }
        
        return $data;
    }

    static function getRiwayatById($idAbsensi) {
        $userId = session('user')->id;
        $data = Absensi::with(['user', 'user.bidangs', 'shift'])
            ->where('id_user', $userId)
            ->where('id', $idAbsensi)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($x) {
                return formatAbsensi($x);
            })
            ->first();
        
        return $data;
    }
}
