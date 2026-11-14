<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HostingerMailTest extends TestCase
{
    use RefreshDatabase;

    private const API = 'https://api.mail.hostinger.com';

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config(['services.hostinger_mail.token' => 'test-only-token']);
    }

    private function fakeApi(): void
    {
        Http::fake([
            self::API.'/api/v1/me' => Http::response(['data' => ['orderResourceId' => 'o1', 'mailboxes' => [['resourceId' => 'mb1', 'address' => 'muzaki@syahdulab.biz.id']]]]),
            self::API.'/api/v1/mailboxes/mb1/folders*' => function ($request) {
                if (str_contains($request->url(), '/messages/42/text')) {
                    return Http::response(['data' => ['text' => null, 'html' => '<p>Halo <b>Muzaki</b></p><script>alert(1)</script>']]);
                }
                if (str_contains($request->url(), '/messages')) {
                    return Http::response(['data' => [['uid' => 42, 'date' => '2026-11-14T10:00:00Z', 'unseen' => true, 'subject' => 'Tawaran proyek', 'from' => ['name' => 'Klien', 'address' => 'klien@contoh.test'], 'size' => 1200, 'attachments' => []]], 'pagination' => ['page' => 1, 'perPage' => 20, 'total' => 1, 'totalPages' => 1]]);
                }

                return Http::response(['data' => [['path' => 'INBOX', 'name' => 'INBOX', 'specialUse' => null, 'messageCount' => 10, 'unreadCount' => 3]], 'pagination' => []]);
            },
            self::API.'/api/v1/mailboxes/mb1/quota' => Http::response(['data' => ['totalUsage' => 5e8, 'totalLimit' => 1e10, 'totalPercentage' => 5, 'supported' => true, 'quotas' => []]]),
        ]);
    }

    public function test_mail_page_shows_setup_when_token_missing(): void
    {
        config(['services.hostinger_mail.token' => null]);
        Http::fake();

        $this->actingAs(User::factory()->create())->get('/admin/mail')->assertOk()->assertSee('"enabled":false', false);
        Http::assertNothingSent();
    }

    public function test_mail_page_lists_inbox_with_bearer_token(): void
    {
        $this->fakeApi();

        $this->actingAs(User::factory()->create())->get('/admin/mail')
            ->assertOk()
            ->assertSee('muzaki@syahdulab.biz.id')
            ->assertSee('Tawaran proyek');

        Http::assertSent(fn ($r) => $r->hasHeader('Authorization', 'Bearer test-only-token') && str_ends_with($r->url(), '/api/v1/me'));
        Http::assertNotSent(fn ($r) => $r->method() !== 'GET'); // browsing never writes
    }

    public function test_message_body_is_returned_as_plain_text_and_scoped_to_known_mailboxes(): void
    {
        $this->fakeApi();
        $this->actingAs(User::factory()->create());

        // Script/style content is dropped entirely; HTML reduced to plain text.
        $this->getJson('/admin/mail/mb1/messages/42?folder=INBOX')->assertOk()->assertJson(['text' => 'Halo Muzaki']);
        // Opening a message marks it as read.
        Http::assertSent(fn ($r) => $r->method() === 'PATCH' && str_ends_with($r->url(), '/messages/42') && $r['addFlags'] === ['\Seen']);
        $this->getJson('/admin/mail/other-box/messages/42')->assertNotFound();
    }

    public function test_api_errors_show_a_message_instead_of_crashing(): void
    {
        Http::fake([self::API.'/*' => Http::response(['message' => 'Unauthenticated'], 401)]);

        $this->actingAs(User::factory()->create())->get('/admin/mail')
            ->assertOk()
            ->assertSee('Token Hostinger Mail ditolak');
        $this->get('/admin')->assertOk();
    }

    public function test_mail_routes_require_login(): void
    {
        $this->get('/admin/mail')->assertRedirect('/loginadmin');
    }

    private function fakeWrites(): void
    {
        Http::fake([
            self::API.'/api/v1/me' => Http::response(['data' => ['mailboxes' => [['resourceId' => 'mb1', 'address' => 'info@syahdulab.biz.id']]]]),
            // Specific routes first: the first matching pattern wins.
            self::API.'/api/v1/mailboxes/mb1/folders/INBOX/messages/7/move' => Http::response(['data' => []]),
            self::API.'/api/v1/mailboxes/mb1/folders/INBOX/messages/7' => Http::response(['data' => []]),
            self::API.'/api/v1/mailboxes/mb1/folders/INBOX/messages/search*' => Http::response(['data' => [], 'pagination' => ['page' => 1, 'totalPages' => 1, 'total' => 0]]),
            self::API.'/api/v1/mailboxes/mb1/send' => Http::response(['data' => ['messageId' => '<x@y>']]),
            self::API.'/api/v1/mailboxes/mb1/folders?*' => Http::response(['data' => [
                ['path' => 'INBOX', 'name' => 'INBOX', 'specialUse' => null, 'messageCount' => 1, 'unreadCount' => 1],
                ['path' => 'Trash', 'name' => 'Trash', 'specialUse' => '\Trash', 'messageCount' => 0, 'unreadCount' => 0],
            ]]),
            self::API.'/api/v1/mailboxes/mb1/quota' => Http::response(['data' => ['supported' => false]]),
        ]);
    }

    public function test_flag_move_and_reply_send_the_right_requests(): void
    {
        $this->fakeWrites();
        $this->actingAs(User::factory()->create());

        $this->post('/admin/mail/mb1/messages/7/flags', ['folder' => 'INBOX', 'flagged' => true, 'seen' => false])->assertSessionHasNoErrors();
        Http::assertSent(fn ($r) => $r->method() === 'PATCH' && $r['addFlags'] === ['\Flagged'] && $r['removeFlags'] === ['\Seen']);

        $this->post('/admin/mail/mb1/messages/7/move', ['folder' => 'INBOX', 'target' => 'Trash'])->assertSessionHasNoErrors();
        Http::assertSent(fn ($r) => str_ends_with($r->url(), '/messages/7/move') && $r['targetFolder'] === 'Trash');
        // Unknown target folder is refused; nothing is ever deleted.
        $this->post('/admin/mail/mb1/messages/7/move', ['folder' => 'INBOX', 'target' => 'Nowhere'])->assertStatus(422);
        Http::assertNotSent(fn ($r) => $r->method() === 'DELETE');

        $this->post('/admin/mail/mb1/send', [
            'to' => ['klien@contoh.test'], 'subject' => 'Re: Tawaran', 'text' => 'Terima kasih!', 'mode' => 'reply',
            'source' => ['uid' => 7, 'folder' => 'INBOX'],
        ])->assertSessionHasNoErrors()->assertSessionHas('success', 'Email terkirim.');
        Http::assertSent(fn ($r) => str_ends_with($r->url(), '/send') && $r['to'] === ['klien@contoh.test'] && $r['inReplyTo'] === ['uid' => 7, 'folder' => 'INBOX']);
    }

    public function test_send_validates_recipients_and_search_uses_text_criteria(): void
    {
        $this->fakeWrites();
        $this->actingAs(User::factory()->create());

        $this->post('/admin/mail/mb1/send', ['to' => ['bukan-email'], 'subject' => '', 'text' => ''])->assertSessionHasErrors(['to.0', 'subject', 'text']);
        Http::assertNotSent(fn ($r) => str_ends_with($r->url(), '/send'));

        $this->get('/admin/mail?q=invoice')->assertOk();
        Http::assertSent(fn ($r) => str_contains($r->url(), '/messages/search') && $r['text'] === 'invoice');
    }
}
