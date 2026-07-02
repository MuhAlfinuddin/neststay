<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Homestay;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class StayNestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test multi-tenant isolation.
     */
    public function test_multi_tenant_isolation(): void
    {
        // 1. Create Tenant A
        $tenantA = Homestay::create([
            'name' => 'Homestay A',
            'slug' => 'homestay-a',
            'status' => 'active',
        ]);

        $ownerA = User::create([
            'name' => 'Owner A',
            'email' => 'ownerA@staynest.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'homestay_id' => $tenantA->id,
        ]);

        // Create a room under Tenant A
        $roomA = Room::create([
            'homestay_id' => $tenantA->id,
            'room_number' => '101',
            'room_type' => 'Standard',
            'price_per_night' => 100000,
            'status' => 'available',
        ]);

        // 2. Create Tenant B
        $tenantB = Homestay::create([
            'name' => 'Homestay B',
            'slug' => 'homestay-b',
            'status' => 'active',
        ]);

        // Create a room under Tenant B
        $roomB = Room::create([
            'homestay_id' => $tenantB->id,
            'room_number' => '201',
            'room_type' => 'Standard',
            'price_per_night' => 150000,
            'status' => 'available',
        ]);

        // 3. Act as Owner A and fetch rooms
        $this->actingAs($ownerA);

        // Fetch rooms via Eloquent
        $rooms = Room::all();

        // Assert Owner A ONLY sees room A (101) and NOT room B (201)
        $this->assertCount(1, $rooms);
        $this->assertEquals('101', $rooms->first()->room_number);
        $this->assertFalse($rooms->contains('room_number', '201'));
    }

    /**
     * Test overbooking prevention logic.
     */
    public function test_overbooking_prevention(): void
    {
        $tenant = Homestay::create([
            'name' => 'Homestay C',
            'slug' => 'homestay-c',
            'status' => 'active',
        ]);

        $owner = User::create([
            'name' => 'Owner C',
            'email' => 'ownerC@staynest.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'homestay_id' => $tenant->id,
        ]);

        $room = Room::create([
            'homestay_id' => $tenant->id,
            'room_number' => '301',
            'room_type' => 'Standard',
            'price_per_night' => 100000,
            'status' => 'available',
        ]);

        $guest = Guest::create([
            'homestay_id' => $tenant->id,
            'name' => 'Tamu C',
            'phone' => '0812345678',
            'identity_number' => '3273010101',
        ]);

        // Create first reservation from July 10 to July 15
        Reservation::create([
            'homestay_id' => $tenant->id,
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in' => '2026-07-10',
            'check_out' => '2026-07-15',
            'total_price' => 500000,
            'status' => 'confirmed',
        ]);

        $this->actingAs($owner);

        // Try to create overlapping reservation: July 12 to July 14
        $response = $this->post(route('reservations.store'), [
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in' => '2026-07-12',
            'check_out' => '2026-07-14',
            'status' => 'confirmed',
        ]);

        // Assert it is redirected back with errors (due to overlap)
        $response->assertStatus(302);
        $response->assertSessionHasErrors('room_id');
        $this->assertEquals(1, Reservation::count()); // No new reservation was stored
    }
}
