<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_replace_recursive([
            'title' => ['id' => 'Proyek Uji', 'en' => 'Test Project'],
            'short' => ['id' => 'Singkat', 'en' => 'Short'],
            'bullets' => ['id' => ['<strong>Satu</strong> poin', ''], 'en' => ['Point one']],
            'tags' => [['label' => 'Laravel 12', 'icon' => 'fab fa-laravel']],
            'link_type' => 'none',
            'is_published' => true,
        ], $overrides);
    }

    public function test_admin_area_requires_login_and_login_lives_at_loginadmin(): void
    {
        $this->get('/admin')->assertRedirect('/loginadmin');
        $this->get('/loginadmin')->assertOk();
    }

    public function test_admin_can_log_in_and_wrong_password_is_rejected(): void
    {
        $user = User::factory()->create(['password' => 'rahasia-panjang']);

        $this->post('/loginadmin', ['email' => $user->email, 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/loginadmin', ['email' => $user->email, 'password' => 'rahasia-panjang'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }

    public function test_created_project_shows_on_landing_in_both_locales_and_drafts_are_hidden(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/projects', $this->payload())
            ->assertRedirect('/admin/projects');

        $project = Project::firstOrFail();
        $this->assertSame(['id' => ['<strong>Satu</strong> poin'], 'en' => ['Point one']], $project->bullets);

        $this->get('/')->assertSee('Proyek Uji');
        $this->get('/locale/en');
        $this->get('/')->assertSee('Test Project');

        $project->update(['is_published' => false]);
        $this->get('/')->assertDontSee('Test Project');
    }

    public function test_bullets_strip_unsafe_html_on_landing(): void
    {
        $this->actingAs(User::factory()->create())->post('/admin/projects', $this->payload([
            'bullets' => ['id' => ['<script>alert(1)</script><strong>Aman</strong>']],
        ]));

        $this->get('/')->assertSee('<strong>Aman</strong>', false)->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_landing_falls_back_to_config_when_cms_is_empty(): void
    {
        $this->get('/')->assertOk()->assertSee('PMO Merch');
    }
}
