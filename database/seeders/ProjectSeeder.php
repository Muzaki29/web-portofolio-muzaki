<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * Imports the works that used to live in config/portfolio.php + lang files.
 * Idempotent: does nothing once projects exist, so it never overwrites admin edits.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        if (Project::exists()) {
            return;
        }

        $tr = fn (string $key) => ['id' => __($key, [], 'id'), 'en' => __($key, [], 'en')];

        foreach (config('portfolio.works_projects', []) as $i => $p) {
            Project::create([
                'sort_order' => $i,
                'is_published' => true,
                'image' => $p['image'] ?? null,
                'gradient' => $p['gradient'] ?? null,
                'title' => $tr($p['title_key']),
                'short' => $tr($p['short_key']),
                'tagline' => $tr($p['tagline_key']),
                'description' => $tr($p['desc_key']),
                'bullets' => [
                    'id' => array_map(fn ($k) => __($k, [], 'id'), $p['bullets'] ?? []),
                    'en' => array_map(fn ($k) => __($k, [], 'en'), $p['bullets'] ?? []),
                ],
                'tags' => $p['tags'] ?? [],
                'github_url' => $p['github_url'] ?? null,
                'live_url' => $p['live_url'] ?? null,
                'link_type' => $p['link_type'] ?? 'none',
            ]);
        }
    }
}
