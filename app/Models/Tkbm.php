<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tkbm extends BaseModel
{
    /** @use HasFactory<\Database\Factories\TkbmFactory> */
    use HasFactory, SoftDeletes, UsesUuid;

    protected $guarded = ['id'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(M_karyawan::class);
    }
}
