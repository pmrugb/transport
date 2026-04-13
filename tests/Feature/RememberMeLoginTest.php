<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RememberMeLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_remember_me_queues_recaller_cookie(): void
    {
        $user = User::factory()->create([
            'name' => 'remember-user',
            'email' => 'remember@example.com',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'login' => $user->email,
            'password' => 'password',
            'remember' => '1',
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertNotNull($user->fresh()->remember_token);

        $cookieNames = collect($response->headers->getCookies())
            ->map(fn ($cookie) => $cookie->getName());

        $this->assertTrue(
            $cookieNames->contains(fn ($name) => str_starts_with($name, 'remember_web_')),
            'Expected remember_web_* cookie to be queued on login.'
        );
    }
}
