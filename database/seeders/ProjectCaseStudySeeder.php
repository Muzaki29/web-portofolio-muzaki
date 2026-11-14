<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Backfills case-study metadata on existing projects: slug, solution category, project type
 * (taken from the existing tagline) and the featured flag. Only fills fields that are still
 * empty, so it never overwrites edits made in the admin. No problem/solution/role text is
 * invented here — those stay empty until written in the admin, and the case study hides them.
 */
class ProjectCaseStudySeeder extends Seeder
{
    /** Keyed by the GitHub/live URL fragment that identifies each existing project. */
    private const META = [
        'web-merchband' => ['slug' => 'pmo-merch', 'category' => 'ecommerce', 'featured' => true],
        'web-syahdulab' => ['slug' => 'syahdulab', 'category' => 'uiux', 'featured' => true],
        'web-properti' => ['slug' => 'jagad-property', 'category' => 'web', 'featured' => false],
        'website-surat-rt' => ['slug' => 'suratrt', 'category' => 'public', 'featured' => false],
        'website-sekolah' => ['slug' => 'sdn-makasar-08', 'category' => 'education', 'featured' => true],
        'website-presensi-magang-muspen' => ['slug' => 'sinar-presensi-magang', 'category' => 'operational', 'featured' => false],
        'web-pengelolahan-koleksi' => ['slug' => 'sistem-informasi-koleksi', 'category' => 'operational', 'featured' => true],
        'muspen.komdigi.go.id' => ['slug' => 'portal-museum-penerangan', 'category' => 'public', 'featured' => false],
    ];

    public function run(): void
    {
        foreach (Project::all() as $project) {
            $meta = $this->metaFor($project) ?? ['slug' => Str::slug($project->t('title', 'id')), 'category' => 'web', 'featured' => false];

            $project->slug ??= $this->uniqueSlug($meta['slug'], $project->id);
            $project->category ??= $meta['category'];
            if (empty($project->project_type) && ! empty($project->tagline)) {
                $project->project_type = array_map(fn ($v) => Str::of((string) $v)->lower()->ucfirst()->value(), $project->tagline);
            }
            if (! $project->getOriginal('featured') && ! $project->wasRecentlyCreated && $meta['featured'] && $project->getRawOriginal('slug') === null) {
                $project->featured = true;
            }
            $project->save();
        }
    }

    private function metaFor(Project $project): ?array
    {
        foreach (self::META as $needle => $meta) {
            if (str_contains((string) $project->github_url.' '.$project->live_url, $needle)) {
                return $meta;
            }
        }
        // Cumo Cuci Motor has no public link.
        return str_contains(strtolower((string) $project->t('title', 'id')), 'cumo')
            ? ['slug' => 'cumo-cuci-motor', 'category' => 'operational', 'featured' => false]
            : null;
    }

    private function uniqueSlug(string $slug, int $id): string
    {
        return Project::where('slug', $slug)->where('id', '!=', $id)->exists() ? "$slug-$id" : $slug;
    }
}
