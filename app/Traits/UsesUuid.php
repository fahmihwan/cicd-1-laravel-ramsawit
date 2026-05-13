<?php
// app/Traits/UsesUuid.php
namespace App\Traits;

use Illuminate\Support\Str;

trait UsesUuid
{
    public static function bootUsesUuid()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function initializeUsesUuid()
    {
        $this->incrementing = false;
        $this->keyType = 'string';
    }
}
