<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WorkCaseStudyTest extends TestCase
{
    use RefreshDatabase;

    private function project(array $attrs = []): Project
    {
        return Project::create(array_merge([
            'title' => ['id' => 'Proyek Uji', 'en' => 'Test Project'],
            'short' => ['id' => 'Ringkasan uji.'],
            'slug' => 'proyek-uji',
            'category' => 'education',
            'status' => 'in_development',
            'featured' => true,
            'is_published' => true,
            'link_type' => 'none',
        ], $attrs));
    }

    public function test_case_study_page_shows_only_sections_with_data(): void
    {
        $this->project(['problem' => ['id' => 'Sekolah butuh portal berita.'], 'github_url' => 'https://github.com/Muzaki29/x']);

        $this->get('/work/proyek-uji')
            ->assertOk()
            ->assertSee('Proyek Uji')
            ->assertSee('Sekolah butuh portal berita.')
            ->assertSee('In development')
            ->assertSee('<link rel="canonical" href="'.url('/work/proyek-uji').'">', false)
            ->assertSee('"@type":"CreativeWork"', false)
            ->assertSee('https://github.com/Muzaki29/x')
            ->assertDontSee(__('work.solution'))   // empty sections are hidden
            ->assertDontSee(__('work.live'));       // no live URL → no live button
    }

    public function test_unknown_or_draft_case_study_is_404(): void
    {
        $this->project(['is_published' => false]);

        $this->get('/work/proyek-uji')->assertNotFound();
        $this->get('/work/tidak-ada')->assertNotFound();
    }

    public function test_landing_lists_featured_and_library_with_category_filters(): void
    {
        $this->project();
        $this->project(['title' => ['id' => 'Toko Daring'], 'slug' => 'toko', 'category' => 'ecommerce', 'featured' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('href="'.url('/work/proyek-uji').'"', false)
            ->assertSee('data-filter="education"', false)
            ->assertSee('data-filter="ecommerce"', false)
            ->assertDontSee('data-filter="creative3d"', false) // empty categories are not offered
            ->assertSee('aria-pressed="true"', false);
    }

    public function test_admin_can_save_case_study_fields(): void
    {
        $project = $this->project();

        $this->actingAs(User::factory()->create())->put("/admin/projects/{$project->id}", [
            'title' => ['id' => 'Proyek Uji', 'en' => 'Test Project'],
            'link_type' => 'none',
            'status' => 'verified',
            'category' => 'public',
            'featured' => false,
            'slug' => 'proyek-baru',
            'role' => ['id' => 'Full-Stack Developer', 'en' => ''],
            'responsibilities' => ['id' => "Analisis kebutuhan\nPengujian API", 'en' => ''],
        ])->assertSessionHasNoErrors();

        $project->refresh();
        $this->assertSame(['verified', 'public', 'proyek-baru', false], [$project->status, $project->category, $project->slug, $project->featured]);
        $this->get('/work/proyek-baru')->assertSee('<li>Pengujian API</li>', false)->assertSee('Full-Stack Developer');
    }

    public function test_sitemap_lists_published_case_studies_and_landing_has_person_schema(): void
    {
        $this->project();
        $this->project(['title' => ['id' => 'Draft'], 'slug' => 'draft', 'is_published' => false]);

        $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(url('/work/proyek-uji'))
            ->assertDontSee(url('/work/draft'));
        $this->get('/')->assertSee('"@type":"Person"', false)->assertSee('id="process"', false);
    }

    public function test_admin_gallery_upload_shows_on_case_study(): void
    {
        Storage::fake('public');
        $project = $this->project();

        $this->actingAs(User::factory()->create())->post("/admin/projects/{$project->id}", [
            '_method' => 'put',
            'title' => ['id' => 'Proyek Uji', 'en' => 'Test Project'],
            'link_type' => 'none',
            'slug' => 'proyek-uji',
            'new_gallery' => [UploadedFile::fake()->image('ui.png', 1600, 1000)],
        ])->assertSessionHasNoErrors();

        $path = $project->refresh()->gallery[0];
        $this->assertStringStartsWith('storage/projects/gallery/', $path);
        $this->get('/work/proyek-uji')->assertSee(__('work.gallery'))->assertSee($path);
    }
}
