<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentsAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_natco_admin_alias_cannot_open_payments_index_by_url(): void
    {
        $user = User::factory()->create([
            'email' => 'ehsan@pmrugb.gov.pk',
        ]);

        $response = $this->actingAs($user)->get('/payments');

        $response->assertForbidden();
    }
}
