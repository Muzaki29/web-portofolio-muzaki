<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as GoogleUser;
use Mockery;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    private function fakeGoogle(string $email, bool $verified = true): void
    {
        $user = (new GoogleUser)->setRaw(['email_verified' => $verified])->map(['email' => $email, 'name' => 'Google User']);
        $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->shouldReceive('user')->andReturn($user);
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.google.client_id' => 'test-client', 'services.google.client_secret' => 'test-only-secret']);
    }

    public function test_google_login_is_hidden_and_404_when_not_configured(): void
    {
        config(['services.google.client_id' => null]);

        $this->get('/auth/google/redirect')->assertNotFound();
        $this->get('/loginadmin')->assertOk()->assertDontSee('auth/google/redirect');
    }

    public function test_registered_admin_can_log_in_with_verified_google_email(): void
    {
        $admin = User::factory()->create(['email' => 'admin@contoh.test']);
        $this->fakeGoogle('Admin@Contoh.test');

        $this->get('/auth/google/callback')->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_unknown_or_unverified_google_account_is_rejected(): void
    {
        User::factory()->create(['email' => 'admin@contoh.test']);

        $this->fakeGoogle('orang-lain@gmail.com');
        $this->get('/auth/google/callback')->assertRedirect('/loginadmin')->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->fakeGoogle('admin@contoh.test', verified: false);
        $this->get('/auth/google/callback')->assertRedirect('/loginadmin');
        $this->assertGuest();
    }
}
