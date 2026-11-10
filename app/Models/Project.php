<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'sort_order', 'is_published', 'image', 'gradient',
        'title', 'short', 'tagline', 'description', 'bullets', 'tags',
        'github_url', 'live_url', 'link_type',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'title' => 'array',
            'short' => 'array',
            'tagline' => 'array',
            'description' => 'array',
            'bullets' => 'array',
            'tags' => 'array',
        ];
    }

    /** Value of a translatable field in the given locale, falling back to Indonesian. */
    public function t(string $field, ?string $locale = null): mixed
    {
        $value = $this->{$field} ?? [];
        $locale ??= app()->getLocale();

        return $value[$locale] ?? $value['id'] ?? null;
    }

    /** Shape consumed by the landing page works section. */
    public function toLanding(): array
    {
        return [
            'gradient' => $this->gradient,
            'image' => $this->image,
            'title' => $this->t('title'),
            'short' => $this->t('short'),
            'tagline' => $this->t('tagline'),
            'desc' => $this->t('description'),
            'bullets' => $this->t('bullets') ?? [],
            'tags' => $this->tags ?? [],
            'github_url' => $this->github_url,
            'live_url' => $this->live_url,
            'link_type' => $this->link_type,
        ];
    }
}
