<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\District;
use App\Models\Fare;
use App\Models\Operator;
use App\Models\Role;
use App\Models\TransportRoute;
use App\Models\TripDetail;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripTotalAmountPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_trip_total_amount_is_recalculated_without_manual_total_permission(): void
    {
        Role::create([
            'name' => 'Trip Creator',
            'slug' => 'trip_creator',
            'access_scope' => 'global',
            'can_view' => true,
            'can_create' => true,
            'page_permissions' => [
                'trips.view',
                'trips.create',
            ],
        ]);

        $user = User::factory()->create([
            'email' => 'trip-creator@example.com',
            'role' => 'trip_creator',
        ]);

        $tripContext = $this->createTripContext();

        $this->actingAs($user)->post(route('trips.store'), [
            'trip_date' => today()->toDateString(),
            'route_id' => $tripContext['route']->id,
            'vehicle_id' => $tripContext['vehicle']->id,
            'transporter_id' => $tripContext['transporter']->id,
            'driver_name' => 'Test Driver',
            'driver_cnic' => '12345-1234567-1',
            'driver_mobile' => '0312-1234567',
            'fare_id' => $tripContext['fare']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 2,
            'total_amount' => 9999,
            'district_id' => $tripContext['district']->id,
            'status' => 'active',
            'remarks' => 'No manual total permission',
        ])->assertRedirect(route('trips.create'));

        $trip = TripDetail::query()->latest('id')->firstOrFail();

        $this->assertSame(5000.0, (float) $trip->total_amount);
    }

    public function test_trip_total_amount_is_saved_as_entered_with_manual_total_permission(): void
    {
        Role::create([
            'name' => 'Trip Total Editor',
            'slug' => 'trip_total_editor',
            'access_scope' => 'global',
            'can_view' => true,
            'can_create' => true,
            'page_permissions' => [
                'trips.view',
                'trips.create',
                'trips.edit_total_amount',
            ],
        ]);

        $user = User::factory()->create([
            'email' => 'trip-total-editor@example.com',
            'role' => 'trip_total_editor',
        ]);

        $tripContext = $this->createTripContext('GLT-TOTAL-2', 'CHASSIS-TOTAL-2', '12345-1234567-2');

        $this->actingAs($user)->post(route('trips.store'), [
            'trip_date' => today()->toDateString(),
            'route_id' => $tripContext['route']->id,
            'vehicle_id' => $tripContext['vehicle']->id,
            'transporter_id' => $tripContext['transporter']->id,
            'driver_name' => 'Test Driver',
            'driver_cnic' => '12345-1234567-1',
            'driver_mobile' => '0312-1234567',
            'fare_id' => $tripContext['fare']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 2,
            'total_amount' => 4700,
            'district_id' => $tripContext['district']->id,
            'status' => 'active',
            'remarks' => 'Manual total permission',
        ])->assertRedirect(route('trips.create'));

        $trip = TripDetail::query()->latest('id')->firstOrFail();

        $this->assertSame(4700.0, (float) $trip->total_amount);
    }

    /**
     * @return array{district: District, route: TransportRoute, transporter: Operator, vehicle: Vehicle, fare: Fare}
     */
    private function createTripContext(
        string $registrationNo = 'GLT-TOTAL-1',
        string $chassisNo = 'CHASSIS-TOTAL-1',
        string $cnic = '12345-1234567-1'
    ): array {
        $suffix = substr(preg_replace('/[^A-Za-z0-9]/', '', $registrationNo), -2) ?: '01';

        $division = Division::create([
            'name' => 'Total Division '.$suffix,
        ]);

        $district = District::create([
            'division_id' => $division->id,
            'name' => 'Total District '.$suffix,
            'division_name' => $division->name,
        ]);

        $route = TransportRoute::create([
            'route_name' => 'Total Route '.$suffix,
            'starting_point' => 'Start '.$suffix,
            'ending_point' => 'End '.$suffix,
            'timing' => 'Morning',
            'total_distance' => 50,
            'district_id' => $district->id,
        ]);

        $transporter = Operator::create([
            'owner_type' => 'private',
            'name' => 'Total Transporter '.$suffix,
            'cnic' => $cnic,
            'phone' => '0312-1234567',
            'address' => 'Total Address '.$suffix,
            'district_id' => $district->id,
        ]);

        $vehicleType = VehicleType::create([
            'name' => 'Total Vehicle Type '.$suffix,
            'status' => 'active',
        ]);

        $vehicle = Vehicle::create([
            'transporter_id' => $transporter->id,
            'vehicle_type' => $vehicleType->id,
            'registration_no' => $registrationNo,
            'chassis_no' => $chassisNo,
            'route_id' => $route->id,
            'status' => 'active',
        ]);

        $fare = Fare::create([
            'route_id' => $route->id,
            'amount' => 2500,
            'effective_from' => today(),
            'status' => 'active',
        ]);

        return compact('district', 'route', 'transporter', 'vehicle', 'fare');
    }
}
