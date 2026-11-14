<?php

namespace App\Http\Controllers;

use App\Models\Project;

/** Public case-study page for one project: /work/{slug}. */
class WorkController extends Controller
{
    public function show(string $slug)
    {
        $projects = Project::where('is_published', true)->orderBy('sort_order')->get();
        $index = $projects->search(fn (Project $p) => $p->slug === $slug);
        abort_if($index === false, 404);

        $project = $projects[$index];
        $count = $projects->count();

        return view('work', [
            'project' => $project,
            'prev' => $count > 1 ? $projects[($index - 1 + $count) % $count] : null,
            'next' => $count > 1 ? $projects[($index + 1) % $count] : null,
        ]);
    }
}
