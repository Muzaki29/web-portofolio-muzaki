<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Setting;
use App\Services\LandingPage;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    private function project(string $title, int $order, bool $published = true): Project
    {
        return Project::create([
            'title' => ['id' => $title], 'sort_order' => $order, 'is_published' => $published,
            'slug' => str($title)->slug()->value(), 'link_type' => 'none',
        ]);
    }

    public function test_only_published_projects_in_sort_order(): void
    {
        $this->project('Kedua', 2);
        $this->project('Pertama', 1);
        $this->project('Draft', 0, published: false);

        $titles = array_column(app(LandingPage::class)->data()['worksProjects'], 'title');

        $this->assertSame(['Pertama', 'Kedua'], $titles);
    }

    public function test_a_setting_from_the_database_wins_over_its_default(): void
    {
        $profile = app(LandingPage::class)->data()['profile'];
        $this->assertSame('Jakarta, Indonesia', $profile['location']); // default

        Setting::create(['key' => 'location', 'value' => 'Bandung']);
        Setting::flush();

        $this->assertSame('Bandung', app(LandingPage::class)->data()['profile']['location']);
    }

    public function test_database_failure_is_reported_and_the_page_still_renders(): void
    {
        Exceptions::fake();
        foreach (['projects', 'content_items', 'settings'] as $table) {
            Schema::drop($table);
        }

        $data = app(LandingPage::class)->data();

        $this->assertSame([], $data['worksProjects']);
        $this->assertTrue($data['experiences']->isEmpty());
        $this->assertSame('Jakarta, Indonesia', $data['profile']['location']); // defaults take over
        Exceptions::assertReported(QueryException::class);

        $this->get('/')->assertOk();
    }

    public function test_a_failed_settings_read_is_not_cached(): void
    {
        Exceptions::fake();
        Schema::drop('settings');
        $this->assertSame([], Setting::all_values());
        $this->assertFalse(Cache::has('cms.settings')); // retried next request, not stuck on defaults
    }
}
