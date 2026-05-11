<?php

namespace Tests\Feature;

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

    public function test_user_with_payments_view_but_without_edit_status_cannot_update_payment_status(): void
    {
        Role::create([
            'name' => 'Payments View Only',
            'slug' => 'payments_view_only',
            'access_scope' => 'global',
            'can_view' => true,
            'page_permissions' => ['payments.view'],
        ]);

        $user = User::factory()->create([
            'email' => 'payments-view@example.com',
            'role' => 'payments_view_only',
        ]);

        $payment = $this->createPayment();

        $response = $this->actingAs($user)->patch(route('payments.approve', $payment));

        $response->assertForbidden();
    }

    private function createPayment(): TripCost
    {
        $division = Division::create(['name' => 'Payments Division']);
        $district = District::create([
            'division_id' => $division->id,
            'name' => 'Payments District',
            'division_name' => $division->name,
        ]);
        $route = TransportRoute::create([
            'route_name' => 'Payments Route',
            'starting_point' => 'Start',
            'ending_point' => 'End',
            'timing' => 'Morning',
            'total_distance' => 10,
            'district_id' => $district->id,
        ]);
        $transporter = Operator::create([
            'owner_type' => 'private',
            'name' => 'Payments Transporter',
            'cnic' => '12345-1234567-1',
            'phone' => '0312-1234567',
            'address' => 'Payments Address',
            'district_id' => $district->id,
        ]);
        $vehicleType = VehicleType::create([
            'name' => 'Payments Vehicle Type',
            'status' => 'active',
        ]);
        $vehicle = Vehicle::create([
            'transporter_id' => $transporter->id,
            'vehicle_type' => $vehicleType->id,
            'registration_no' => 'PAY-123',
            'chassis_no' => 'PAY-CHASSIS-123',
            'route_id' => $route->id,
            'status' => 'active',
        ]);
        $fare = Fare::create([
            'route_id' => $route->id,
            'amount' => 2000,
            'effective_from' => today(),
            'status' => 'active',
        ]);
        $trip = TripDetail::create([
            'trip_date' => today(),
            'route_id' => $route->id,
            'vehicle_id' => $vehicle->id,
            'transporter_id' => $transporter->id,
            'driver_name' => 'Payment Driver',
            'driver_cnic' => '12345-1234567-1',
            'driver_mobile' => '0312-1234567',
            'fare_id' => $fare->id,
            'fare_amount' => 2000,
            'no_of_trips' => 1,
            'total_amount' => 2000,
            'district_id' => $district->id,
            'status' => 'active',
            'created_by' => User::factory()->create()->id,
        ]);

        return TripCost::create([
            'trip_id' => $trip->id,
            'route_id' => $route->id,
            'vehicle_id' => $vehicle->id,
            'transporter_id' => $transporter->id,
            'fare_amount' => 2000,
            'no_of_trips' => 1,
            'total_amount' => 2000,
            'calculation_date' => today(),
            'status' => 'due',
        ]);
    }
}
