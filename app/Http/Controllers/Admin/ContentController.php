<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

/** CRUD for experience, timeline and certificate entries, driven by config/cms.php. */
class ContentController extends Controller
{
    private function schema(string $section): array
    {
        return config("cms.sections.$section") ?? abort(404);
    }

    private function item(string $section, int $id): ContentItem
    {
        return ContentItem::where('section', $section)->findOrFail($id);
    }

    public function index(string $section)
    {
        $schema = $this->schema($section);

        return Inertia::render('content/index', [
            'section' => $section,
            'schema' => $schema,
            'items' => ContentItem::where('section', $section)->orderBy('sort_order')->get()->map(fn (ContentItem $i) => [
                'id' => $i->id,
                'title' => $i->v($schema['title_field'], 'id'),
                'subtitle' => $i->v($schema['subtitle_field'], 'id'),
                'meta' => $i->v('period', 'id') ?? $i->v('date', 'id'),
                'thumb' => isset($i->data['images'][0]) ? asset($i->data['images'][0]['preview'] ?? $i->data['images'][0]['full']) : null,
                'is_published' => $i->is_published,
            ]),
        ]);
    }

    public function create(string $section)
    {
        return Inertia::render('content/form', ['section' => $section, 'schema' => $this->schema($section), 'item' => null]);
    }

    public function store(Request $request, string $section)
    {
        ContentItem::create([
            'section' => $section,
            'sort_order' => (ContentItem::where('section', $section)->max('sort_order') ?? -1) + 1,
            'is_published' => $request->boolean('is_published', true),
            'data' => $this->validated($request, $section),
        ]);

        return redirect()->route('admin.content.index', $section)->with('success', ucfirst($this->schema($section)['singular']).' ditambahkan.');
    }

    public function edit(string $section, int $id)
    {
        $item = $this->item($section, $id);
        $data = $item->data;
        $data['images'] = array_map(fn ($img) => [...$img, 'url' => asset($img['preview'] ?? $img['full'])], $data['images'] ?? []);

        return Inertia::render('content/form', [
            'section' => $section,
            'schema' => $this->schema($section),
            'item' => ['id' => $item->id, 'is_published' => $item->is_published, 'data' => $data],
        ]);
    }

    public function update(Request $request, string $section, int $id)
    {
        $item = $this->item($section, $id);
        $item->update([
            'is_published' => $request->boolean('is_published'),
            'data' => $this->validated($request, $section, $item),
        ]);

        return redirect()->route('admin.content.index', $section)->with('success', 'Perubahan disimpan.');
    }

    public function destroy(string $section, int $id)
    {
        $item = $this->item($section, $id);
        foreach ($item->data['images'] ?? [] as $img) {
            $this->deleteUploaded($img['full'] ?? null);
        }
        $item->delete();

        return back()->with('success', ucfirst($this->schema($section)['singular']).' dihapus.');
    }

    public function toggle(string $section, int $id)
    {
        $item = $this->item($section, $id);
        $item->update(['is_published' => ! $item->is_published]);

        return back();
    }

    public function reorder(Request $request, string $section)
    {
        $ids = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']])['ids'];
        foreach ($ids as $i => $id) {
            ContentItem::where('section', $section)->whereKey($id)->update(['sort_order' => $i]);
        }

        return back();
    }

    private function validated(Request $request, string $section, ?ContentItem $item = null): array
    {
        $rules = [];
        foreach ($this->schema($section)['fields'] as $f) {
            $base = match ($f['type']) {
                'url' => ['url', 'max:255'],
                'textarea' => ['string', 'max:3000'],
                'images' => null,
                default => ['string', 'max:255'],
            };
            if ($base === null) {
                continue;
            }
            $presence = ($f['required'] ?? false) ? 'required' : 'nullable';
            if ($f['translatable'] ?? false) {
                $rules["data.{$f['name']}.id"] = [$presence, ...$base];
                $rules["data.{$f['name']}.en"] = ['nullable', ...$base];
            } else {
                $rules["data.{$f['name']}"] = ['nullable', ...$base];
            }
        }
        $rules['keep_images'] = ['nullable', 'array'];
        $rules['keep_images.*'] = ['string'];
        $rules['new_images'] = ['nullable', 'array', 'max:8'];
        $rules['new_images.*'] = ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];

        $v = $request->validate($rules, [], ['data.*.id' => 'isian (Indonesia)']);
        $data = $v['data'] ?? [];

        if (collect($this->schema($section)['fields'])->contains('type', 'images')) {
            $existing = collect($item?->data['images'] ?? []);
            $keep = $request->input('keep_images', []);
            $existing->reject(fn ($img) => in_array($img['full'], $keep, true))->each(fn ($img) => $this->deleteUploaded($img['full']));
            $images = $existing->filter(fn ($img) => in_array($img['full'], $keep, true))->sortBy(fn ($img) => array_search($img['full'], $keep, true))->values()->all();
            foreach ($request->file('new_images', []) as $file) {
                $path = 'storage/'.$file->store('certificates', 'public');
                $images[] = ['full' => $path, 'preview' => $path];
            }
            $data['images'] = $images;
        }

        return $data;
    }

    private function deleteUploaded(?string $path): void
    {
        if ($path && str_starts_with($path, 'storage/')) {
            Storage::disk('public')->delete(substr($path, strlen('storage/')));
        }
    }
}
