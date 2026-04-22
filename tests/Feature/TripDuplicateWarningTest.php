<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\District;
use App\Models\Fare;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripDuplicateWarningTest extends TestCase
{
    use RefreshDatabase;

    public function test_second_trip_for_same_vehicle_on_today_shows_warning_toast_message(): void
    {
        $user = User::factory()->create([
            'email' => 'trip-warning@example.com',
        ]);

        $tripContext = $this->createTripContext();

        $payload = [
            'trip_date' => today()->toDateString(),
            'route_id' => $tripContext['route']->id,
            'vehicle_id' => $tripContext['vehicle']->id,
            'transporter_id' => $tripContext['transporter']->id,
            'driver_name' => 'Warning Driver',
            'driver_cnic' => '12345-1234567-1',
            'driver_mobile' => '0312-1234567',
            'fare_id' => $tripContext['fare']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 1,
            'total_amount' => 2500,
            'district_id' => $tripContext['district']->id,
            'status' => 'active',
            'remarks' => 'Trip warning test',
        ];

        $this->actingAs($user)->post(route('trips.store'), $payload)
            ->assertRedirect(route('trips.create'))
            ->assertSessionMissing('warning');

        $this->actingAs($user)->post(route('trips.store'), $payload)
            ->assertRedirect(route('trips.create'))
            ->assertSessionHas('warning', "You have added a second entry for today's date with the same vehicle registration.");
    }

    /**
     * @return array{district: District, route: TransportRoute, transporter: Operator, vehicle: Vehicle, fare: Fare}
     */
    private function createTripContext(): array
    {
        $division = Division::create([
            'name' => 'Warning Division',
        ]);

        $district = District::create([
            'division_id' => $division->id,
            'name' => 'Warning District',
            'division_name' => $division->name,
        ]);

        $route = TransportRoute::create([
            'route_name' => 'Warning Route',
            'starting_point' => 'Start Point',
            'ending_point' => 'End Point',
            'timing' => 'Morning',
            'total_distance' => 50,
            'district_id' => $district->id,
        ]);

        $transporter = Operator::create([
            'owner_type' => 'private',
            'name' => 'Warning Transporter',
            'cnic' => '12345-1234567-1',
            'phone' => '0312-1234567',
            'address' => 'Warning Address',
            'district_id' => $district->id,
        ]);

        $vehicleType = VehicleType::create([
            'name' => 'Warning Vehicle Type',
            'status' => 'active',
        ]);

        $vehicle = Vehicle::create([
            'transporter_id' => $transporter->id,
            'vehicle_type' => $vehicleType->id,
            'registration_no' => 'GLT-WARN-1',
            'chassis_no' => 'CHASSIS-WARN-1',
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
