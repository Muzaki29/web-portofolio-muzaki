<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContentItem extends Model
{
    protected $fillable = ['section', 'sort_order', 'is_published', 'data'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'data' => 'array'];
    }

    public function scopeLanding(Builder $query, string $section): void
    {
        $query->where('section', $section)->where('is_published', true)->orderBy('sort_order');
    }

    /** Field value; translatable fields resolve to the current locale, falling back to Indonesian. */
    public function v(string $field, ?string $locale = null): mixed
    {
        $value = $this->data[$field] ?? null;
        if (is_array($value) && (array_key_exists('id', $value) || array_key_exists('en', $value))) {
            $locale ??= app()->getLocale();

            return filled($value[$locale] ?? null) ? $value[$locale] : ($value['id'] ?? null);
        }

        return $value;
    }

    /** Multi-line textarea field as a list of non-empty lines. */
    public function lines(string $field): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->v($field)))));
    }
}
