<?php

namespace App\Models;

use App\Helpers\Utils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{


    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)
            ->timezone('Asia/Jakarta')
            ->translatedFormat('l, d-F-Y H:i') . " WIB";
    }
}
