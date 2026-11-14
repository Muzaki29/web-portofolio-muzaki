<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use App\Models\PageVisit;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $range = in_array((int) $request->query('range'), [7, 30, 90], true) ? (int) $request->query('range') : 30;
        $from = now()->subDays($range - 1)->startOfDay();
        $prevFrom = (clone $from)->subDays($range);

        $visits = PageVisit::where('visited_on', '>=', $from->toDateString())->get(['visited_on', 'visitor_hash', 'referrer_host', 'device', 'path']);
        $byDay = $visits->groupBy(fn ($v) => $v->visited_on->toDateString());

        $series = collect(range($range - 1, 0))->map(function ($ago) use ($byDay) {
            $day = now()->subDays($ago)->toDateString();
            $rows = $byDay->get($day, collect());

            return ['date' => $day, 'views' => $rows->count(), 'visitors' => $rows->unique('visitor_hash')->count()];
        })->values();

        $prevViews = PageVisit::whereBetween('visited_on', [$prevFrom->toDateString(), (clone $from)->subDay()->toDateString()])->count();

        $projects = Project::orderByDesc('updated_at')->get();
        $counts = ContentItem::selectRaw('section, count(*) as total')->groupBy('section')->pluck('total', 'section');

        return Inertia::render('dashboard', [
            'range' => $range,
            'visits' => [
                'series' => $series,
                'views' => $visits->count(),
                'visitors' => $visits->unique(fn ($v) => $v->visited_on->toDateString().$v->visitor_hash)->count(),
                'today' => $byDay->get(now()->toDateString(), collect())->count(),
                'all_time' => PageVisit::count(),
                'change' => $prevViews > 0 ? round(($visits->count() - $prevViews) / $prevViews * 100) : null,
                'devices' => [
                    ['name' => 'Desktop', 'value' => $visits->where('device', 'desktop')->count()],
                    ['name' => 'Mobile', 'value' => $visits->where('device', 'mobile')->count()],
                ],
                'referrers' => $visits->groupBy(fn ($v) => $v->referrer_host ?: 'Langsung')
                    ->map->count()->sortDesc()->take(5)
                    ->map(fn ($count, $host) => ['host' => $host, 'count' => $count])->values(),
            ],
            // Case-study views in the range, joined to project titles by slug.
            'caseStudies' => $visits->filter(fn ($v) => str_starts_with((string) $v->path, '/work/'))
                ->groupBy(fn ($v) => substr($v->path, 6))->map->count()->sortDesc()->take(5)
                ->map(fn ($count, $slug) => [
                    'slug' => $slug,
                    'count' => $count,
                    'title' => optional($projects->firstWhere('slug', $slug))->t('title', 'id') ?? $slug,
                    'id' => optional($projects->firstWhere('slug', $slug))->id,
                ])->values(),
            'landingViews' => $visits->where('path', '/')->count(),
            // Which case-study sections are still empty, so the admin knows what to write next.
            'completeness' => $projects->sortBy('sort_order')->map(function (Project $p) {
                $checks = [
                    'Gambar' => (bool) $p->image,
                    'Masalah' => filled($p->t('problem', 'id')),
                    'Solusi' => filled($p->t('solution', 'id')),
                    'Peran' => filled($p->t('role', 'id')),
                    'Galeri' => ! empty($p->gallery),
                ];
                $done = count(array_filter($checks));

                return [
                    'id' => $p->id,
                    'title' => $p->t('title', 'id'),
                    'featured' => (bool) $p->featured,
                    'percent' => (int) round($done / count($checks) * 100),
                    'missing' => array_keys(array_filter($checks, fn ($ok) => ! $ok)),
                ];
            })->sortBy('percent')->values(),
            'content' => [
                'projects' => $projects->count(),
                'experience' => (int) ($counts['experience'] ?? 0),
                'timeline' => (int) ($counts['timeline'] ?? 0),
                'certificate' => (int) ($counts['certificate'] ?? 0),
                'drafts' => $projects->where('is_published', false)->count()
                    + ContentItem::where('is_published', false)->count(),
            ],
            'recent' => $projects->take(5)->map(fn (Project $p) => [
                'id' => $p->id,
                'title' => $p->t('title', 'id'),
                'is_published' => $p->is_published,
                'updated_at' => $p->updated_at?->diffForHumans(),
            ])->values(),
        ]);
    }
}
