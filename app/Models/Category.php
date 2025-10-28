<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $fillable = [];

    public $hidden = ['updated_at', 'pivot'];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_category');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($category) {
            $category->articles()->detach();
        });
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translate(string $code, string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $translation = $this->translations
            ->where('locale', $locale)
            ->where('code', $code)
            ->first();

        return $translation ? $translation->value : null;
    }

    public function saveTranslation(array $validated): void
    {
        $this->translations()->whereIn('code', ['name', 'meta_title', 'meta_description', 'text'])->delete();

        foreach (config('langs') as $locale => $langValue) {
            $name = $validated['name'][$locale] ?? null;
            if ($name !== null && $name !== '') {
                $this->translations()->updateOrCreate(
                    ['locale' => $locale, 'code' => 'name'],
                    ['value' => $name]
                );
            }

            $metaTitle = $validated['meta_title'][$locale] ?? null;
            if ($metaTitle !== null && $metaTitle !== '') {
                $this->translations()->updateOrCreate(
                    ['locale' => $locale, 'code' => 'meta_title'],
                    ['value' => $metaTitle]
                );
            }

            $metaDescription = $validated['meta_description'][$locale] ?? null;
            if ($metaDescription !== null && $metaDescription !== '') {
                $this->translations()->updateOrCreate(
                    ['locale' => $locale, 'code' => 'meta_description'],
                    ['value' => $metaDescription]
                );
            }

            $text = $validated['text'][$locale] ?? null;
            if ($text !== null && $text !== '') {
                $this->translations()->updateOrCreate(
                    ['locale' => $locale, 'code' => 'text'],
                    ['value' => $text]
                );
            }
        }
    }

    public function getAdminNameAttribute()
    {
        if ($this->relationLoaded('translations')) {
            $enName = $this->translations
                ->where('code', 'name')
                ->where('locale', 'en')
                ->first();
            if ($enName) {
                return $enName->value;
            }
            $anyName = $this->translations
                ->where('code', 'name')
                ->first();
            return $anyName?->value;
        }
        return null;
    }
}
