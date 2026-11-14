<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'sort_order', 'is_published', 'image', 'gradient',
        'title', 'short', 'tagline', 'description', 'bullets', 'tags',
        'github_url', 'live_url', 'link_type',
        'slug', 'featured', 'category', 'status', 'year', 'project_type', 'role',
        'problem', 'solution', 'responsibilities', 'technical_decisions', 'lessons', 'gallery',
    ];

    public const STATUSES = ['implemented', 'verified', 'partial', 'in_development', 'concept'];

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
            'featured' => 'boolean',
            'project_type' => 'array',
            'role' => 'array',
            'problem' => 'array',
            'solution' => 'array',
            'responsibilities' => 'array',
            'technical_decisions' => 'array',
            'lessons' => 'array',
            'gallery' => 'array',
        ];
    }

    /** Value of a translatable field in the given locale, falling back to Indonesian. */
    public function t(string $field, ?string $locale = null): mixed
    {
        $value = $this->{$field} ?? [];
        $locale ??= app()->getLocale();

        return filled($value[$locale] ?? null) ? $value[$locale] : ($value['id'] ?? null);
    }

    /** Multi-line translatable field as a list of non-empty lines. */
    public function lines(string $field): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->t($field)))));
    }

    public function caseStudyUrl(): ?string
    {
        return $this->slug ? route('work.show', $this->slug) : null;
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
            'slug' => $this->slug,
            'url' => $this->caseStudyUrl(),
            'featured' => $this->featured,
            'category' => $this->category,
            'status' => $this->status,
            'year' => $this->year,
            'project_type' => $this->t('project_type'),
            'role' => $this->t('role'),
        ];
    }
}
