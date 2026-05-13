<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penggajian_karyawan extends BaseModel
{
    /** @use HasFactory<\Database\Factories\PenggajianKaryawanFactory> */
    use HasFactory, SoftDeletes, UsesUuid;

    protected $guarded = ['id'];


    public function karyawan()
    {
        return $this->belongsTo(M_karyawan::class);
    }
}
