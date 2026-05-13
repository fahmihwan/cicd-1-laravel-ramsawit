<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_jobs extends BaseModel
{
    /** @use HasFactory<\Database\Factories\MJobsFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];


    public function m_karyawan()
    {
        return $this->belongsTo(M_karyawan::class, 'karyawan_id', 'id');
    }

    public function type_karyawan()
    {
        return $this->belongsTo(M_type_karyawan::class, 'type_karyawan_id', 'id');
    }
}
