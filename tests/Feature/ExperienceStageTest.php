<?php

namespace Tests\Feature;

use App\Models\ContentItem;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExperienceStageTest extends TestCase
{
    use RefreshDatabase;

    private function create(array $data, array $extra = []): void
    {
        $this->actingAs(User::factory()->create())->post('/admin/content/experience', [
            'data' => $data + ['role' => ['id' => 'Peran Uji', 'en' => '']],
            'is_published' => true,
        ] + $extra)->assertRedirect('/admin/content/experience');
        auth()->logout();
    }

    public function test_uploaded_image_becomes_a_stage_frame_on_landing(): void
    {
        Storage::fake('public');
        $this->create([], ['new_images' => [UploadedFile::fake()->image('scene.jpg', 1600, 900)]]);

        $path = ContentItem::where('section', 'experience')->firstOrFail()->data['images'][0]['full'];
        $this->assertStringStartsWith('storage/experience/', $path);
        $this->get('/')->assertSee('class="exp-frame is-active"', false)->assertSee($path, false);
    }

    public function test_role_without_image_still_renders_stage_and_chapter(): void
    {
        $this->create([]);

        $this->get('/')->assertSee('exp-stage-grid', false)->assertSee('Peran Uji');
    }

    public function test_certificate_button_follows_certificate_visibility(): void
    {
        $this->create(['cert_key' => 'abc']);
        $this->get('/')->assertDontSee('exp-btn-cert', false);

        Setting::updateOrCreate(['key' => 'show_certifications'], ['value' => '1']);
        Setting::flush();
        $this->get('/')->assertSee('exp-btn-cert', false);
    }
}
