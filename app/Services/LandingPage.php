<?php

namespace App\Services;

use App\Models\ContentItem;
use App\Models\Project;
use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * Everything the public Landing page shows, assembled in one place: published Projects,
 * Content items (experience, timeline, certificates) and Settings with their defaults.
 *
 * Read-only. If the database fails the page still renders with empty sections and the
 * error is reported to the log (never swallowed silently).
 */
class LandingPage
{
    /**
     * Variables for the `portfolio` view. Names match what the view already uses.
     *
     * @return array{worksProjects: array, experiences: Collection, timelineItems: Collection, certificates: Collection, profile: array}
     */
    public function data(): array
    {
        return [
            'worksProjects' => rescue(
                fn () => Project::where('is_published', true)->orderBy('sort_order')->get()->map->toLanding()->all(),
                []
            ),
            'experiences' => $this->section('experience'),
            'timelineItems' => $this->section('timeline'),
            'certificates' => $this->section('certificate'),
            'profile' => $this->profile(),
        ];
    }

    private function section(string $name): Collection
    {
        return rescue(fn () => ContentItem::landing($name)->get(), collect());
    }

    /** Profile & contact settings; the second argument is what shows until the admin sets a value. */
    private function profile(): array
    {
        $s = fn (string $key, $default) => Setting::get($key, $default);

        return [
            'hero_role' => $s('hero_role', __('hero.kicker')),
            'about_title' => $s('about_title', __('about.title')),
            'about_description' => $s('about_description', __('about.description')),
            'location' => $s('location', 'Jakarta, Indonesia'),
            'contact_email' => $s('contact_email', 'muzaki@syahdulab.biz.id'),
            'github_username' => $s('github_username', 'Muzaki29'),
            'linkedin_url' => $s('linkedin_url', 'https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/'),
            'linkedin_label' => $s('linkedin_label', 'muzaki-abdullah-irsyad'),
            'dribbble_url' => $s('dribbble_url', 'https://dribbble.com/muzaki-abdullah-irsyad'),
            'pinterest_url' => $s('pinterest_url', 'https://id.pinterest.com/syahdulab/'),
            'pinterest_label' => $s('pinterest_label', 'SyahduLab'),
            'instagram_username' => $s('instagram_username', 'priasyahdu'),
            'threads_username' => $s('threads_username', 'tuanpriasyahdu'),
        ];
    }
}
