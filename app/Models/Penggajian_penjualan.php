<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penggajian_penjualan extends BaseModel
{
    /** @use HasFactory<\Database\Factories\PenggajianPenjualanFactory> */
    use HasFactory, UsesUuid;

    protected $guarded = ['id'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
