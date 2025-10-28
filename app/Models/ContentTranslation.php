<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    protected $fillable = [
        'content_id',
        'locale',
        'code',
        'value',
    ];
}
