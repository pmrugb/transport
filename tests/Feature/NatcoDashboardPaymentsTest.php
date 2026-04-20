<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Division;
use App\Models\District;
use App\Models\Fare;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\TripCost;
use App\Models\TripDetail;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NatcoDashboardPaymentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_natco_dashboard_counts_legacy_payments_created_by_natco_user(): void
    {
        $natcoDepartment = Department::create([
            'name' => 'NATCO',
            'status' => 'active',
        ]);

        $natcoUser = User::factory()->create([
            'email' => User::NATCO_EMAIL,
        ]);

        $tripContext = $this->createTripContext();

        $trip = TripDetail::create([
            'trip_date' => today(),
            'route_id' => $tripContext['route']->id,
            'vehicle_id' => $tripContext['vehicle']->id,
            'transporter_id' => $tripContext['transporter']->id,
            'driver_name' => 'Legacy NATCO Driver',
            'driver_cnic' => '12345-1234567-1',
            'driver_mobile' => '0312-1234567',
            'fare_id' => $tripContext['fare']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 2,
            'total_amount' => 5000,
            'district_id' => $tripContext['district']->id,
            'department_id' => null,
            'status' => 'active',
            'created_by' => $natcoUser->id,
        ]);

        TripCost::create([
            'trip_id' => $trip->id,
            'route_id' => $tripContext['route']->id,
            'vehicle_id' => $tripContext['vehicle']->id,
            'transporter_id' => $tripContext['transporter']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 2,
            'total_amount' => 5000,
            'calculation_date' => today(),
            'status' => 'paid',
        ]);

        $response = $this->actingAs($natcoUser)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('isNatcoDashboard', true);
        $response->assertViewHas('stats', function (array $stats) use ($natcoDepartment): bool {
            return $natcoDepartment->id > 0
                && $stats['totalPayments'] === 1
                && $stats['paidPayments'] === 1
                && (float) $stats['paidAmount'] === 5000.0;
        });
    }

    public function test_natco_trip_creation_auto_assigns_natco_department(): void
    {
        $natcoDepartment = Department::create([
            'name' => 'NATCO',
            'status' => 'active',
        ]);

        $natcoUser = User::factory()->create([
            'email' => User::NATCO_EMAIL,
        ]);

        $tripContext = $this->createTripContext();

        $response = $this->actingAs($natcoUser)->post(route('trips.store'), [
            'trip_date' => today()->toDateString(),
            'route_id' => $tripContext['route']->id,
            'vehicle_id' => $tripContext['vehicle']->id,
            'transporter_id' => $tripContext['transporter']->id,
            'driver_name' => 'New NATCO Driver',
            'driver_cnic' => '12345-1234567-1',
            'driver_mobile' => '0312-1234567',
            'fare_id' => $tripContext['fare']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 3,
            'total_amount' => 7500,
            'district_id' => $tripContext['district']->id,
            'status' => 'active',
            'remarks' => 'Created from NATCO account',
        ]);

        $response->assertRedirect(route('trips.create'));

        $this->assertDatabaseHas('trip_details', [
            'created_by' => $natcoUser->id,
            'department_id' => $natcoDepartment->id,
            'driver_name' => 'New NATCO Driver',
        ]);

        $this->assertDatabaseHas('trip_costs', [
            'status' => 'due',
            'total_amount' => 7500,
        ]);
    }

    public function test_natco_user_sees_all_payments_on_payments_index(): void
    {
        Department::create([
            'name' => 'NATCO',
            'status' => 'active',
        ]);

        $natcoUser = User::factory()->create([
            'email' => User::NATCO_EMAIL,
        ]);

        $firstTripContext = $this->createTripContext('GLT-201', 'CHASSIS-201');
        $secondTripContext = $this->createTripContext('GLT-202', 'CHASSIS-202');

        $natcoTrip = TripDetail::create([
            'trip_date' => today(),
            'route_id' => $firstTripContext['route']->id,
            'vehicle_id' => $firstTripContext['vehicle']->id,
            'transporter_id' => $firstTripContext['transporter']->id,
            'driver_name' => 'NATCO Visible Driver',
            'driver_cnic' => '12345-1234567-1',
            'driver_mobile' => '0312-1234567',
            'fare_id' => $firstTripContext['fare']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 1,
            'total_amount' => 2500,
            'district_id' => $firstTripContext['district']->id,
            'department_id' => null,
            'status' => 'active',
            'created_by' => $natcoUser->id,
        ]);

        TripCost::create([
            'trip_id' => $natcoTrip->id,
            'route_id' => $firstTripContext['route']->id,
            'vehicle_id' => $firstTripContext['vehicle']->id,
            'transporter_id' => $firstTripContext['transporter']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 1,
            'total_amount' => 2500,
            'calculation_date' => today(),
            'status' => 'due',
        ]);

        $otherUser = User::factory()->create([
            'email' => 'payments@example.com',
        ]);

        $otherTrip = TripDetail::create([
            'trip_date' => today(),
            'route_id' => $secondTripContext['route']->id,
            'vehicle_id' => $secondTripContext['vehicle']->id,
            'transporter_id' => $secondTripContext['transporter']->id,
            'driver_name' => 'General Payment Driver',
            'driver_cnic' => '54321-7654321-0',
            'driver_mobile' => '0300-7654321',
            'fare_id' => $secondTripContext['fare']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 2,
            'total_amount' => 5000,
            'district_id' => $secondTripContext['district']->id,
            'department_id' => null,
            'status' => 'active',
            'created_by' => $otherUser->id,
        ]);

        TripCost::create([
            'trip_id' => $otherTrip->id,
            'route_id' => $secondTripContext['route']->id,
            'vehicle_id' => $secondTripContext['vehicle']->id,
            'transporter_id' => $secondTripContext['transporter']->id,
            'fare_amount' => 2500,
            'no_of_trips' => 2,
            'total_amount' => 5000,
            'calculation_date' => today(),
            'status' => 'paid',
        ]);

        $response = $this->actingAs($natcoUser)->get(route('payments.index'));

        $response->assertOk();
        $response->assertSee('NATCO Transporter 201');
        $response->assertSee('NATCO Transporter 202');
        $response->assertViewHas('stats', function (array $stats): bool {
            return $stats['total'] === 2
                && $stats['due'] === 1
                && (float) $stats['paid_amount'] === 5000.0;
        });
    }

    /**
     * @return array{district: District, route: TransportRoute, transporter: Operator, vehicle: Vehicle, fare: Fare}
     */
    private function createTripContext(string $registrationNo = 'GLT-123', string $chassisNo = 'CHASSIS-123'): array
    {
        $suffix = substr(preg_replace('/[^A-Za-z0-9]/', '', $registrationNo), -3) ?: '001';

        $district = District::create([
            'division_id' => Division::create([
                'name' => 'Gilgit Division '.$suffix,
            ])->id,
            'name' => 'Gilgit '.$suffix,
            'division_name' => 'Gilgit Division '.$suffix,
        ]);

        $route = TransportRoute::create([
            'route_name' => 'Gilgit to Hunza '.$suffix,
            'starting_point' => 'Gilgit '.$suffix,
            'ending_point' => 'Hunza '.$suffix,
            'timing' => 'Morning',
            'total_distance' => 100,
            'district_id' => $district->id,
        ]);

        $transporter = Operator::create([
            'owner_type' => 'company',
            'name' => 'NATCO Transporter '.$suffix,
            'cnic' => null,
            'phone' => '0312-1234567',
            'address' => 'Gilgit '.$suffix,
            'district_id' => $district->id,
        ]);

        $vehicleType = VehicleType::firstOrCreate([
            'name' => 'Coaster',
        ], [
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
