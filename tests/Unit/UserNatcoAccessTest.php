<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserNatcoAccessTest extends TestCase
{
    public function test_ehsan_email_gets_natco_admin_permissions(): void
    {
        $user = new User([
            'name' => 'Ehsan Ullah',
            'email' => 'ehsan@pmrugb.gov.pk',
            'password' => 'password',
        ]);

        $this->assertTrue($user->isNatcoDepartmentUser());
        $this->assertTrue($user->isNatcoAdminUser());
        $this->assertTrue($user->canManagePayments());
        $this->assertTrue($user->canViewTripsModule());
        $this->assertTrue($user->canCreateTrips());
        $this->assertTrue($user->canEditTrips());
        $this->assertFalse($user->canAccessPaymentsModule());
        $this->assertFalse($user->canSeePaymentsNav());
    }

    public function test_non_natco_admin_users_still_see_payments_nav(): void
    {
        $user = new User([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => 'password',
        ]);

        $this->assertFalse($user->canManagePayments());
        $this->assertTrue($user->canAccessPaymentsModule());
        $this->assertTrue($user->canSeePaymentsNav());
    }
}
