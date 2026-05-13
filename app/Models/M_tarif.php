<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_tarif extends BaseModel
{
    /** @use HasFactory<\Database\Factories\MTarifFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function type_karyawan(){
        return $this->belongsTo(M_type_karyawan::class);
    }
}
