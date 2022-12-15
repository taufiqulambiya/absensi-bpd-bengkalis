<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBidang extends Model
{
    use HasFactory;

    protected $table = 'tb_bidang';
    protected $guarded = ['id'];

    public function users() {
        return $this->hasMany(User::class, 'jabatan', 'id');
    }
    public function kabids() {
        return $this->hasOne(User::class, 'id', 'kabid');
    }
}
