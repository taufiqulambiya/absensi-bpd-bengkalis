<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'users';

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_user', 'id');
    }

    public function bidang()
    {
        return $this->hasOne(MasterBidang::class, 'id', 'bidang');
    }
    public function bidangs()
    {
        return $this->hasOne(MasterBidang::class, 'id', 'bidang');
    }

    public function cuti()
    {
        return $this->hasMany(Cuti::class, 'id_user', 'id');
    }

    public function izin()
    {
        return $this->hasMany(Izin::class, 'id_user', 'id');
    }
    public function dinas_luar()
    {
        return $this->hasMany(DinasLuar::class, 'id_user', 'id');
    }
}
