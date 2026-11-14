<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        return Inertia::render('projects/index', [
            'projects' => Project::orderBy('sort_order')->get()->map(fn (Project $p) => [
                'id' => $p->id,
                'title' => $p->t('title', 'id'),
                'short' => $p->t('short', 'id'),
                'image' => $p->image ? asset($p->image) : null,
                'tags' => collect($p->tags ?? [])->pluck('label')->all(),
                'link_type' => $p->link_type,
                'is_published' => $p->is_published,
                'sort_order' => $p->sort_order,
                'updated_at' => $p->updated_at?->toDateString(),
            ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('projects/form', ['project' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['sort_order'] = (Project::max('sort_order') ?? -1) + 1;
        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Karya ditambahkan.');
    }

    public function edit(Project $project)
    {
        return Inertia::render('projects/form', [
            'project' => [
                ...$project->only('id', 'title', 'short', 'tagline', 'description', 'bullets', 'tags', 'github_url', 'live_url', 'link_type', 'is_published', 'gradient',
                    'slug', 'featured', 'category', 'status', 'year', 'project_type', 'role', 'problem', 'solution', 'responsibilities', 'technical_decisions', 'lessons'),
                'image_url' => $project->image ? asset($project->image) : null,
                'gallery' => array_map(fn ($g) => ['path' => $g, 'url' => asset($g)], $project->gallery ?? []),
            ],
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $project->update($this->validated($request, $project));

        return redirect()->route('admin.projects.index')->with('success', 'Karya diperbarui.');
    }

    public function destroy(Project $project)
    {
        $this->deleteUploaded($project->image);
        $project->delete();

        return back()->with('success', 'Karya dihapus.');
    }

    public function toggle(Project $project)
    {
        $project->update(['is_published' => ! $project->is_published]);

        return back();
    }

    public function reorder(Request $request)
    {
        $ids = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']])['ids'];
        foreach ($ids as $i => $id) {
            Project::whereKey($id)->update(['sort_order' => $i]);
        }

        return back();
    }

    private function validated(Request $request, ?Project $project = null): array
    {
        $v = $request->validate([
            'title.id' => ['required', 'string', 'max:160'],
            'title.en' => ['required', 'string', 'max:160'],
            'short.id' => ['nullable', 'string', 'max:300'],
            'short.en' => ['nullable', 'string', 'max:300'],
            'tagline.id' => ['nullable', 'string', 'max:200'],
            'tagline.en' => ['nullable', 'string', 'max:200'],
            'description.id' => ['nullable', 'string', 'max:3000'],
            'description.en' => ['nullable', 'string', 'max:3000'],
            'bullets.id' => ['nullable', 'array', 'max:10'],
            'bullets.id.*' => ['nullable', 'string', 'max:500'],
            'bullets.en' => ['nullable', 'array', 'max:10'],
            'bullets.en.*' => ['nullable', 'string', 'max:500'],
            'tags' => ['nullable', 'array', 'max:12'],
            'tags.*.label' => ['required', 'string', 'max:40'],
            'tags.*.icon' => ['nullable', 'string', 'max:60', 'regex:/^[a-z0-9 -]*$/'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'link_type' => ['required', 'in:github,live,none'],
            'is_published' => ['boolean'],
            'gradient' => ['nullable', 'string', 'max:20'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'keep_gallery' => ['nullable', 'array'],
            'keep_gallery.*' => ['string'],
            'new_gallery' => ['nullable', 'array', 'max:8'],
            'new_gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'slug' => ['nullable', 'string', 'max:80', 'regex:/^[a-z0-9-]+$/', \Illuminate\Validation\Rule::unique('projects', 'slug')->ignore($project?->id)],
            'featured' => ['boolean'],
            'category' => ['nullable', \Illuminate\Validation\Rule::in(config('cms.project_categories'))],
            'status' => ['nullable', \Illuminate\Validation\Rule::in(Project::STATUSES)],
            'year' => ['nullable', 'string', 'max:20'],
            ...collect(['project_type' => 160, 'role' => 160, 'problem' => 3000, 'solution' => 3000, 'responsibilities' => 3000, 'technical_decisions' => 3000, 'lessons' => 3000])
                ->flatMap(fn ($max, $f) => ["$f.id" => ['nullable', 'string', "max:$max"], "$f.en" => ['nullable', 'string', "max:$max"]])->all(),
        ]);
        $v['slug'] = $v['slug'] ?? null ?: \Illuminate\Support\Str::slug($v['title']['id']);
        $v['status'] = $v['status'] ?? null ?: 'implemented';

        $clean = fn ($list) => array_values(array_filter($list ?? [], fn ($s) => filled($s)));
        $v['bullets'] = ['id' => $clean($v['bullets']['id'] ?? []), 'en' => $clean($v['bullets']['en'] ?? [])];
        $v['tags'] = array_map(fn ($t) => ['icon' => $t['icon'] ?? 'fas fa-code', 'label' => $t['label']], $v['tags'] ?? []);

        // Interface gallery: keep the listed existing images (in order), delete the rest, append uploads.
        $keep = array_values(array_intersect($request->input('keep_gallery', []), $project?->gallery ?? []));
        foreach (array_diff($project?->gallery ?? [], $keep) as $old) {
            $this->deleteUploaded($old);
        }
        foreach ($request->file('new_gallery', []) as $file) {
            $keep[] = 'storage/'.$file->store('projects/gallery', 'public');
        }
        $v['gallery'] = $keep;
        unset($v['keep_gallery'], $v['new_gallery']);

        unset($v['image']);
        if ($request->hasFile('image')) {
            $this->deleteUploaded($project?->image);
            $v['image'] = 'storage/'.$request->file('image')->store('projects', 'public');
        }

        return $v;
    }

    /** Only remove files the admin uploaded; bundled images in public/images stay. */
    private function deleteUploaded(?string $path): void
    {
        if ($path && str_starts_with($path, 'storage/')) {
            Storage::disk('public')->delete(substr($path, strlen('storage/')));
        }
    }
}
