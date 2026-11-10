<?php

namespace Tests\Feature;

use App\Mail\ContactMail;
use App\Models\ContentItem;
use App\Models\PageVisit;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    private const BROWSER = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/130.0 Safari/537.36';

    public function test_every_admin_menu_page_opens(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['/admin', '/admin?range=7', '/admin/settings', '/admin/content/experience', '/admin/content/timeline', '/admin/content/certificate', '/admin/content/experience/create'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/admin/content/unknown')->assertNotFound();
    }

    public function test_experience_created_in_admin_appears_on_landing_with_highlights(): void
    {
        $this->actingAs(User::factory()->create())->post('/admin/content/experience', [
            'data' => [
                'role' => ['id' => 'Associate Project Manager', 'en' => ''],
                'company' => ['id' => 'PT. Darma Sarana Niaga', 'en' => ''],
                'highlights' => ['id' => "Koordinasi tim\nIntegrasi AI", 'en' => ''],
            ],
            'is_published' => true,
        ])->assertRedirect('/admin/content/experience');

        auth()->logout();
        $this->get('/')
            ->assertSee('Associate Project Manager')
            ->assertSee('<li>Integrasi AI</li>', false);

        // Blank English falls back to Indonesian.
        $this->get('/locale/en');
        $this->get('/')->assertSee('Associate Project Manager');
    }

    public function test_required_indonesian_field_is_validated(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/content/timeline', ['data' => ['title' => ['id' => '', 'en' => 'x']]])
            ->assertSessionHasErrors(['data.title.id', 'data.period.id']);
        $this->assertSame(0, ContentItem::count());
    }

    public function test_draft_items_are_hidden_from_landing(): void
    {
        ContentItem::create(['section' => 'timeline', 'is_published' => false, 'data' => ['title' => ['id' => 'Rahasia Draft'], 'period' => ['id' => '2026']]]);

        $this->get('/')->assertDontSee('Rahasia Draft');
    }

    public function test_settings_update_changes_landing_contact(): void
    {
        $this->actingAs(User::factory()->create())
            ->put('/admin/settings', ['values' => ['contact_email' => 'halo@contoh.test', 'github_username' => 'contohgh']])
            ->assertSessionHasNoErrors();

        $this->assertSame('halo@contoh.test', Setting::find('contact_email')->value);
        auth()->logout();
        $this->get('/')->assertSee('mailto:halo@contoh.test', false)->assertSee('github.com/contohgh', false);
    }

    public function test_landing_visits_are_counted_but_bots_and_admins_are_not(): void
    {
        $this->withHeaders(['User-Agent' => self::BROWSER, 'Referer' => 'https://www.linkedin.com/feed'])->get('/')->assertOk();
        $this->withHeaders(['User-Agent' => 'Googlebot/2.1'])->get('/');
        $this->actingAs(User::factory()->create())->withHeaders(['User-Agent' => self::BROWSER])->get('/');

        $this->assertSame(1, PageVisit::count());
        $visit = PageVisit::first();
        $this->assertSame('www.linkedin.com', $visit->referrer_host);
        $this->assertSame(64, strlen($visit->visitor_hash));
    }

    public function test_contact_form_delivers_to_the_mailbox_set_in_admin(): void
    {
        Mail::fake();
        Setting::create(['key' => 'contact_email', 'value' => 'muzaki@syahdulab.biz.id']);
        Setting::flush();

        $this->withSession(['captcha_answer' => 7, 'captcha_generated_at' => now()->timestamp])
            ->postJson('/contact', ['name' => 'Rekruter', 'email' => 'hr@contoh.test', 'subject' => 'Lowongan', 'message' => 'Halo, kami tertarik dengan profil Anda.', 'captcha' => 7])
            ->assertOk();

        Mail::assertSent(ContactMail::class, fn ($mail) => $mail->hasTo('muzaki@syahdulab.biz.id'));
        $this->get('/')->assertSee('mailto:muzaki@syahdulab.biz.id', false);
    }

    public function test_chatbot_is_disabled_by_default(): void
    {
        $this->get('/')->assertOk()->assertDontSee('id="aiChatWidget"', false);
        $this->postJson('/api/chatbot', ['message' => 'Halo', 'locale' => 'id'])->assertNotFound();
    }
}
