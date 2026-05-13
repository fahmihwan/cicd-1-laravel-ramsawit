<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_pabrik extends BaseModel
{
    /** @use HasFactory<\Database\Factories\MPabrikFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    // protected static function newFactory()
    // {
    //     return M_PabrikFactory::new();
    // }
}
