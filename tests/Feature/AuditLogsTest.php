<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Division;
use App\Models\District;
use App\Models\Fare;
use App\Models\Operator;
use App\Models\Role;
use App\Models\TransportRoute;
use App\Models\TripCost;
use App\Models\TripDetail;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogsTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_approval_creates_audit_log(): void
    {
        $user = User::factory()->create([
            'email' => 'superadmin@pmrugb.gov.pk',
        ]);

        $trip = $this->createTrip();
        $payment = TripCost::create([
            'trip_id' => $trip->id,
            'route_id' => $trip->route_id,
            'vehicle_id' => $trip->vehicle_id,
            'transporter_id' => $trip->transporter_id,
            'fare_amount' => $trip->fare_amount,
            'no_of_trips' => $trip->no_of_trips,
            'total_amount' => $trip->total_amount,
            'calculation_date' => $trip->trip_date,
            'status' => 'due',
            'remarks' => null,
        ]);

        $this->actingAs($user)
            ->patch(route('payments.approve', $payment))
            ->assertRedirect(route('payments.index'));

        $log = AuditLog::query()->where('action', 'payment.approved')->first();

        $this->assertNotNull($log);
        $this->assertSame($user->id, $log->user_id);
        $this->assertSame(TripCost::class, $log->auditable_type);
        $this->assertSame($payment->id, $log->auditable_id);
        $this->assertSame('due', $log->old_values['status']);
        $this->assertSame('paid', $log->new_values['status']);
    }

    public function test_role_access_update_creates_audit_log(): void
    {
        $user = User::factory()->create([
            'email' => 'superadmin@pmrugb.gov.pk',
        ]);

        $role = Role::create([
            'name' => 'Audit Role',
            'slug' => 'audit_role',
            'access_scope' => 'global',
            'can_view' => true,
        ]);

        $this->actingAs($user)
            ->put(route('settings.roles.access.update', $role), [
                'access_scope' => 'global',
                'sidebar_permissions' => ['dashboard', 'logs.audit'],
                'page_permissions' => ['roles.manage', 'logs.audit.view'],
            ])
            ->assertRedirect(route('settings.roles.index'));

        $log = AuditLog::query()->where('action', 'role.access_updated')->first();

        $this->assertNotNull($log);
        $this->assertSame(Role::class, $log->auditable_type);
        $this->assertSame($role->id, $log->auditable_id);
        $this->assertSame([], $log->old_values['sidebar_permissions'] ?? []);
        $this->assertSame(['dashboard', 'logs.audit'], $log->new_values['sidebar_permissions']);
        $this->assertSame(['roles.manage', 'logs.audit.view'], $log->new_values['page_permissions']);
    }

    public function test_trip_update_and_delete_create_audit_logs(): void
    {
        $user = User::factory()->create([
            'email' => 'superadmin@pmrugb.gov.pk',
        ]);

        $trip = $this->createTrip();

        $payload = [
            'trip_date' => today()->toDateString(),
            'route_id' => $trip->route_id,
            'vehicle_id' => $trip->vehicle_id,
            'transporter_id' => $trip->transporter_id,
            'driver_name' => 'Updated Driver',
            'driver_cnic' => '12345-1234567-1',
            'driver_mobile' => '0312-1234567',
            'fare_id' => $trip->fare_id,
            'fare_amount' => (float) $trip->fare_amount,
            'no_of_trips' => 1,
            'total_amount' => (float) $trip->fare_amount,
            'district_id' => $trip->district_id,
            'status' => 'active',
            'remarks' => 'Updated by test',
        ];

        $this->actingAs($user)
            ->put(route('trips.update', $trip), $payload)
            ->assertRedirect(route('trips.edit', $trip));

        $this->actingAs($user)
            ->delete(route('trips.destroy', $trip))
            ->assertRedirect(route('trips.index'));

        $updateLog = AuditLog::query()->where('action', 'trip.updated')->first();
        $deleteLog = AuditLog::query()->where('action', 'trip.deleted')->first();

        $this->assertNotNull($updateLog);
        $this->assertNotNull($deleteLog);
        $this->assertSame('Test Driver', $updateLog->old_values['driver_name']);
        $this->assertSame('Updated Driver', $updateLog->new_values['driver_name']);
        $this->assertSame('Updated Driver', $deleteLog->old_values['driver_name']);
    }

    private function createTrip(): TripDetail
    {
        $division = Division::create([
            'name' => 'Audit Division',
        ]);

        $district = District::create([
            'division_id' => $division->id,
            'name' => 'Audit District',
            'division_name' => $division->name,
        ]);

        $route = TransportRoute::create([
            'route_name' => 'Audit Route',
            'starting_point' => 'Start Point',
            'ending_point' => 'End Point',
            'timing' => 'Morning',
            'total_distance' => 50,
            'district_id' => $district->id,
        ]);

        $transporter = Operator::create([
            'owner_type' => 'private',
            'name' => 'Audit Transporter',
            'cnic' => '12345-1234567-1',
            'phone' => '0312-1234567',
            'address' => 'Audit Address',
            'district_id' => $district->id,
        ]);

        $vehicleType = VehicleType::create([
            'name' => 'Audit Vehicle Type',
            'status' => 'active',
        ]);

        $vehicle = Vehicle::create([
            'transporter_id' => $transporter->id,
            'vehicle_type' => $vehicleType->id,
            'registration_no' => 'GLT-AUDIT-1',
            'chassis_no' => 'CHASSIS-AUDIT-1',
            'route_id' => $route->id,
            'status' => 'active',
        ]);

        $fare = Fare::create([
            'route_id' => $route->id,
            'amount' => 2500,
            'effective_from' => today(),
            'status' => 'active',
        ]);

        return TripDetail::create([
            'trip_date' => today(),
            'route_id' => $route->id,
            'vehicle_id' => $vehicle->id,
            'transporter_id' => $transporter->id,
            'driver_name' => 'Test Driver',
            'driver_cnic' => '12345-1234567-1',
            'driver_mobile' => '0312-1234567',
            'fare_id' => $fare->id,
            'fare_amount' => 2500,
            'no_of_trips' => 1,
            'total_amount' => 2500,
            'district_id' => $district->id,
            'status' => 'active',
            'remarks' => 'Audit trip',
            'created_by' => User::factory()->create()->id,
        ]);
    }
}
