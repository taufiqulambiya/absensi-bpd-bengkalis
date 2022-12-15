<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'izin';

    public function user(){
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
