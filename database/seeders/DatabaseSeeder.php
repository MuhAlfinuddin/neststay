<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Homestay;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Super Admin User
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@staynest.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'homestay_id' => null,
        ]);

        // 2. Setup Tenant 1: StayNest Dago Suite
        $dago = Homestay::create([
            'name' => 'StayNest Dago Suite',
            'address' => 'Jl. Ir. H. Juanda No. 120, Coblong, Bandung',
            'phone' => '022-2501234',
            'slug' => 'staynest-dago-suite',
            'status' => 'active',
        ]);

        // Owner for Tenant 1
        User::create([
            'name' => 'Hendra Wijaya',
            'email' => 'ownerA@staynest.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'homestay_id' => $dago->id,
        ]);

        // Staff for Tenant 1
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'staffA@staynest.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'homestay_id' => $dago->id,
        ]);

        // Rooms for Tenant 1
        $dagoRoom1 = Room::create([
            'homestay_id' => $dago->id,
            'room_number' => '101',
            'room_type' => 'Standard Double',
            'price_per_night' => 250000,
            'status' => 'occupied',
            'description' => 'Fasilitas: Queen Bed, AC, Free Wi-Fi, Kamar Mandi Dalam, Smart TV.',
        ]);

        $dagoRoom2 = Room::create([
            'homestay_id' => $dago->id,
            'room_number' => '102',
            'room_type' => 'Deluxe King',
            'price_per_night' => 450000,
            'status' => 'available',
            'description' => 'Fasilitas: King Bed, AC, Free Wi-Fi, Kamar Mandi Dalam dengan Bathtub, Minibar, Smart TV, Balkon.',
        ]);

        $dagoRoom3 = Room::create([
            'homestay_id' => $dago->id,
            'room_number' => '103',
            'room_type' => 'Family Suite',
            'price_per_night' => 750000,
            'status' => 'available',
            'description' => 'Fasilitas: 2 Queen Bed, AC, Ruang Tamu Kecil, Dapur Mini, Kulkas, Smart TV.',
        ]);

        $dagoRoom4 = Room::create([
            'homestay_id' => $dago->id,
            'room_number' => '104',
            'room_type' => 'Standard Single',
            'price_per_night' => 180000,
            'status' => 'maintenance',
            'description' => 'Dalam proses pengecatan ulang dan perbaikan keran air kamar mandi.',
        ]);

        // Guests for Tenant 1
        $guest1 = Guest::create([
            'homestay_id' => $dago->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'phone' => '081234567890',
            'identity_number' => '3273012304910002',
        ]);

        $guest2 = Guest::create([
            'homestay_id' => $dago->id,
            'name' => 'Rina Kartika',
            'email' => 'rina.k@yahoo.com',
            'phone' => '087890123456',
            'identity_number' => '3273015409950003',
        ]);

        // Reservations for Tenant 1
        // Active check-in (Guest 1 staying in Room 101)
        $res1 = Reservation::create([
            'homestay_id' => $dago->id,
            'guest_id' => $guest1->id,
            'room_id' => $dagoRoom1->id,
            'check_in' => Carbon::now()->subDays(2)->toDateString(),
            'check_out' => Carbon::now()->addDays(2)->toDateString(),
            'total_price' => 1000000, // 4 nights * 250000
            'status' => 'checked_in',
        ]);

        // Historical check-out (Guest 2 in Room 102, completed)
        $res2 = Reservation::create([
            'homestay_id' => $dago->id,
            'guest_id' => $guest2->id,
            'room_id' => $dagoRoom2->id,
            'check_in' => Carbon::now()->subDays(7)->toDateString(),
            'check_out' => Carbon::now()->subDays(4)->toDateString(),
            'total_price' => 1350000, // 3 nights * 450000
            'status' => 'checked_out',
        ]);

        // Upcoming booking (Guest 1 booking Room 103 for next week)
        $res3 = Reservation::create([
            'homestay_id' => $dago->id,
            'guest_id' => $guest1->id,
            'room_id' => $dagoRoom3->id,
            'check_in' => Carbon::now()->addDays(5)->toDateString(),
            'check_out' => Carbon::now()->addDays(7)->toDateString(),
            'total_price' => 1500000, // 2 nights * 750000
            'status' => 'confirmed',
        ]);

        // Payments for Tenant 1
        // Payment for active check-in (paid down payment)
        Payment::create([
            'homestay_id' => $dago->id,
            'reservation_id' => $res1->id,
            'amount' => 500000,
            'payment_method' => 'transfer',
            'payment_status' => 'down_payment',
            'payment_date' => Carbon::now()->subDays(2),
        ]);

        // Payment for historical booking (fully paid)
        Payment::create([
            'homestay_id' => $dago->id,
            'reservation_id' => $res2->id,
            'amount' => 1350000,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'payment_date' => Carbon::now()->subDays(7),
        ]);

        // Payment for upcoming booking (fully paid in advance)
        Payment::create([
            'homestay_id' => $dago->id,
            'reservation_id' => $res3->id,
            'amount' => 1500000,
            'payment_method' => 'transfer',
            'payment_status' => 'paid',
            'payment_date' => Carbon::now()->subDays(1),
        ]);


        // 3. Setup Tenant 2: StayNest Ubud Villa (Demonstrating Multi-Tenancy separation)
        $ubud = Homestay::create([
            'name' => 'StayNest Ubud Villa',
            'address' => 'Jl. Raya Ubud No. 45, Ubud, Gianyar, Bali',
            'phone' => '0361-975432',
            'slug' => 'staynest-ubud-villa',
            'status' => 'active',
        ]);

        // Owner for Tenant 2
        User::create([
            'name' => 'Wayan Sudarta',
            'email' => 'ownerB@staynest.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'homestay_id' => $ubud->id,
        ]);

        // Rooms for Tenant 2
        $ubudRoom1 = Room::create([
            'homestay_id' => $ubud->id,
            'room_number' => 'V-01',
            'room_type' => 'Private Pool Villa',
            'price_per_night' => 1500000,
            'status' => 'occupied',
            'description' => 'Fasilitas: King Bed, Private Swimming Pool, Open Kitchen, Garden View.',
        ]);

        $ubudRoom2 = Room::create([
            'homestay_id' => $ubud->id,
            'room_number' => 'V-02',
            'room_type' => 'Jungle View Cabin',
            'price_per_night' => 950000,
            'status' => 'available',
            'description' => 'Fasilitas: King Bed, Semi-outdoor Bathroom, AC, Free Breakfast.',
        ]);

        // Guest for Tenant 2
        $guestUbud = Guest::create([
            'homestay_id' => $ubud->id,
            'name' => 'Michael Green',
            'email' => 'michael@gmail.com',
            'phone' => '+14152345678',
            'identity_number' => 'PASSPORT-US-99120',
        ]);

        // Reservation for Tenant 2
        $resUbud = Reservation::create([
            'homestay_id' => $ubud->id,
            'guest_id' => $guestUbud->id,
            'room_id' => $ubudRoom1->id,
            'check_in' => Carbon::now()->subDays(1)->toDateString(),
            'check_out' => Carbon::now()->addDays(3)->toDateString(),
            'total_price' => 6000000, // 4 nights * 1.5M
            'status' => 'checked_in',
        ]);

        // Payment for Tenant 2
        Payment::create([
            'homestay_id' => $ubud->id,
            'reservation_id' => $resUbud->id,
            'amount' => 6000000,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'payment_date' => Carbon::now()->subDays(1),
        ]);
    }
}
