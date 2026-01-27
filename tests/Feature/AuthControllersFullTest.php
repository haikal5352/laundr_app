<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

class AuthControllersFullTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_is_accessible()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function registration_page_is_accessible()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function forgot_password_page_is_accessible()
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    /** @test */
    public function password_reset_link_can_be_requested()
    {
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function password_reset_page_is_accessible()
    {
        $user = User::factory()->create();
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->get('/reset-password/' . $token);
        $response->assertStatus(200);
    }

    /** @test */
    public function password_can_be_reset()
    {
        $user = User::factory()->create();
        $token = app('auth.password.broker')->createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function confirm_password_page_is_accessible()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');
        $response->assertStatus(200);
    }

    /** @test */
    public function password_can_be_confirmed()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function email_verification_page_is_accessible()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify-email');
        $response->assertStatus(200);
    }

    /** @test */
    public function email_verification_notification_can_be_resent()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/email/verification-notification');
        $response->assertRedirect();
    }

    /** @test */
    public function email_can_be_verified()
    {
        Event::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function authenticated_user_redirected_from_login()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function authenticated_user_redirected_from_register()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect('/dashboard');
    }
}
