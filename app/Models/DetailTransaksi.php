<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail';
    protected $primaryKey = 'id_detail';
    protected $guarded = [];

    public function paket()
    {
        return $this->hasOne(Paket::class, 'id_paket', 'id_paket');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }
}
