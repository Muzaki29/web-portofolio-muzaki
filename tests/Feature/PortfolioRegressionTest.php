<?php

namespace Tests\Feature;

use App\Mail\ContactMail;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PortfolioRegressionTest extends TestCase
{
    public function test_home_keeps_professional_content_and_existing_integrations(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Muzaki Abdullah Irsyad')
            ->assertSee('id="contactForm"', false)
            ->assertSee('id="exportPortfolioPdf"', false)
            ->assertSee('id="exportPortfolioPpt"', false)
            ->assertSee('cv/muzaki-abdullah-irsyad.pdf')
            ->assertSessionHas('captcha_answer')
            ->assertSessionHas('captcha_generated_at');
    }

    public function test_language_switch_persists_english_for_the_homepage(): void
    {
        $this->from('/')->get('/locale/en')
            ->assertRedirect('/')
            ->assertSessionHas('locale', 'en');

        $this->get('/')->assertOk()->assertSee('<html lang="en"', false);
    }

    public function test_home_exposes_brand_metadata_without_server_credentials(): void
    {
        config(['services.gemini.api_key' => 'test-only-server-credential']);
        $this->get('/')->assertOk()
            ->assertSee('property="og:image"', false)
            ->assertSee('images/mai-social.png')
            ->assertSee('name="twitter:card" content="summary_large_image"', false)
            ->assertSee('favicon-32.png')
            ->assertDontSee('test-only-server-credential');
    }

    public function test_unsupported_language_cannot_replace_the_session_locale(): void
    {
        $this->withSession(['locale' => 'id'])->get('/locale/fr')
            ->assertNotFound()
            ->assertSessionHas('locale', 'id');
    }

    public function test_contact_rejects_invalid_input_without_sending_email(): void
    {
        Mail::fake();

        $this->postJson('/contact', ['email' => 'invalid'])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonValidationErrors(['name', 'email', 'subject', 'message', 'captcha']);

        Mail::assertNothingSent();
    }

    public function test_contact_requires_a_captcha_from_the_current_session(): void
    {
        Mail::fake();

        $this->postJson('/contact', $this->contactPayload())
            ->assertUnprocessable()
            ->assertJsonPath('success', false);

        Mail::assertNothingSent();
    }

    public function test_contact_rejects_incorrect_captcha_without_sending_email(): void
    {
        Mail::fake();

        $this->withSession(['captcha_answer' => 9, 'captcha_generated_at' => now()->timestamp])
            ->postJson('/contact', $this->contactPayload())
            ->assertUnprocessable()
            ->assertJsonPath('success', false);

        Mail::assertNothingSent();
    }

    public function test_contact_rejects_expired_captcha_without_sending_email(): void
    {
        Mail::fake();

        $this->withSession(['captcha_answer' => 7, 'captcha_generated_at' => now()->subSeconds(301)->timestamp])
            ->postJson('/contact', $this->contactPayload())
            ->assertUnprocessable()
            ->assertJsonPath('success', false);

        Mail::assertNothingSent();
    }

    public function test_valid_contact_sends_one_email_and_consumes_the_captcha(): void
    {
        Mail::fake();

        $this->withSession(['captcha_answer' => 7, 'captcha_generated_at' => now()->timestamp])
            ->postJson('/contact', $this->contactPayload())
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertSessionMissing('captcha_answer')
            ->assertSessionMissing('captcha_generated_at');

        $this->postJson('/contact', $this->contactPayload())->assertUnprocessable();
        Mail::assertSent(ContactMail::class, 1);
    }

    public function test_chatbot_rejects_missing_or_oversized_input_without_network_requests(): void
    {
        Http::preventStrayRequests();

        $this->postJson('/api/chatbot', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['message', 'locale']);

        $this->postJson('/api/chatbot', ['message' => str_repeat('a', 501), 'locale' => 'fr'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['message', 'locale']);

        Http::assertNothingSent();
    }

    public function test_chatbot_rejects_a_one_character_question_without_network_requests(): void
    {
        Http::preventStrayRequests();

        $this->postJson('/api/chatbot', ['message' => 'a', 'locale' => 'en'])
            ->assertUnprocessable()
            ->assertJsonPath('success', false);

        Http::assertNothingSent();
    }

    public function test_chatbot_sends_the_key_in_a_header_and_caches_valid_text(): void
    {
        config(['services.gemini.api_key' => 'test-only-not-a-secret']);
        Http::preventStrayRequests();
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'Portfolio response']]]]],
        ])]);

        $payload = ['message' => 'Which portfolio projects are available?', 'locale' => 'en'];
        $this->postJson('/api/chatbot', $payload)->assertOk()->assertJsonPath('message', 'Portfolio response');
        $this->postJson('/api/chatbot', $payload)->assertOk()->assertJsonPath('message', 'Portfolio response');
        Http::assertSentCount(1);
        Http::assertSent(fn ($request) => $request->hasHeader('x-goog-api-key', 'test-only-not-a-secret')
            && ! str_contains($request->url(), 'key='));
    }

    public function test_chatbot_connection_failure_does_not_log_exception_details(): void
    {
        config(['services.gemini.api_key' => 'test-only-not-a-secret']);
        Http::preventStrayRequests();
        Http::fake(fn () => throw new ConnectionException('Sensitive upstream connection details'));
        Log::spy();

        $this->postJson('/api/chatbot', ['message' => 'Tell me about the portfolio', 'locale' => 'en'])
            ->assertStatus(503)->assertJsonPath('success', false)
            ->assertDontSee('Sensitive upstream');
        Log::shouldHaveReceived('error')->once()->with('Chatbot provider request failed', [
            'exception' => ConnectionException::class,
        ]);
    }

    public function test_chatbot_rejects_provider_errors_and_malformed_text_without_caching(): void
    {
        config(['services.gemini.api_key' => 'test-only-not-a-secret']);
        Http::preventStrayRequests();
        Http::fake(['generativelanguage.googleapis.com/*' => Http::sequence()
            ->push(['error' => 'Upstream details'], 429)
            ->push(['candidates' => [['content' => ['parts' => [['text' => ['invalid']]]]]]])
            ->push(['candidates' => [['content' => ['parts' => [['text' => ' ']]]]]])]);
        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/chatbot', ['message' => 'Show portfolio skills please', 'locale' => 'en'])
                ->assertStatus(503)->assertJsonPath('success', false)->assertDontSee('Upstream details');
        }
        Http::assertSentCount(3);
    }

    public function test_contact_mail_failure_preserves_fields_and_captcha_for_retry(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('Sensitive mail transport details'));
        Log::spy();
        $this->withSession(['captcha_answer' => 7, 'captcha_generated_at' => now()->timestamp])
            ->postJson('/contact', $this->contactPayload())
            ->assertStatus(500)->assertJsonPath('success', false)
            ->assertSessionHas('captcha_answer', 7)->assertDontSee('Sensitive mail');
        Log::shouldHaveReceived('error')->once()->with('Contact email delivery failed', [
            'exception' => \RuntimeException::class,
        ]);
    }

    private function contactPayload(): array
    {
        return [
            'name' => 'Portfolio Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'Project inquiry',
            'message' => 'I would like to discuss a web development project.',
            'captcha' => 7,
        ];
    }
}
