<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\TransportRoute;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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

    public function test_route_scoped_user_can_have_multiple_routes(): void
    {
        $user = new User([
            'name' => 'Route User',
            'email' => 'route@example.com',
            'password' => 'password',
            'all_routes_access' => false,
        ]);

        $user->setRelation('accessRole', new Role([
            'slug' => 'route_user',
            'access_scope' => 'route',
        ]));
        $user->setRelation('routes', new EloquentCollection([
            (new TransportRoute())->forceFill(['id' => 11, 'route_name' => 'Route 11']),
            (new TransportRoute())->forceFill(['id' => 14, 'route_name' => 'Route 14']),
        ]));

        $this->assertSame([11, 14], $user->scopedRouteIds());
        $this->assertTrue($user->hasRouteAccess(11));
        $this->assertTrue($user->hasRouteAccess(14));
        $this->assertFalse($user->hasRouteAccess(19));
    }
}
