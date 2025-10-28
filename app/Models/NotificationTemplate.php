<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasTranslations;

    protected $fillable = [
        'code',
        'name',
        'is_mass'
    ];

    const TRANSLATION_FIELDS = [
        'title',
        'message',
    ];

    public function translations()
    {
        return $this->hasMany(NotificationTemplateTranslation::class);
    }
}
