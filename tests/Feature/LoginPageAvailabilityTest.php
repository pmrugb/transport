<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LoginPageAvailabilityTest extends TestCase
{
    public function test_login_page_still_renders_when_settings_query_fails(): void
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', '/definitely-missing/login-page.sqlite');

        Cache::flush();

        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Sign in');
    }
}
