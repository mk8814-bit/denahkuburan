<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Password for all users
        $password = \Illuminate\Support\Facades\Hash::make('12345');

        // Super Admin
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => $password,
            'role' => 'super_admin',
        ]);

        // Admin
        $adminNames = ['Rizky Pratama', 'Siti Aminah', 'Budi Santoso', 'Maya Indah', 'Agus Salim', 'Dewi Lestari', 'Eko Saputra', 'Ani Rahayu', 'Dedi Kurniawan', 'Lely Fitri'];
        foreach ($adminNames as $key => $name) {
            $i = $key + 1;
            \App\Models\User::create([
                'name' => $name,
                'email' => "admin$i@gmail.com",
                'password' => $password,
                'role' => 'admin',
            ]);
        }

        // Karyawan
        $karyawanNames = ['Agil Budiman', 'Yudi Permana', 'Siska Amelia', 'Rendy Wijaya', 'Ika Nuraini', 'Taufik Hidayat', 'Vina Panduwinata', 'Hendra Gunawan', 'Nita Kurnia', 'Fajar Sidik'];
        foreach ($karyawanNames as $key => $name) {
            $i = $key + 1;
            \App\Models\User::create([
                'name' => $name,
                'email' => "karyawan$i@gmail.com",
                'password' => $password,
                'role' => 'karyawan',
            ]);
        }

        // Customer (menggunakan nama Keluarga)
        $familyNames = [
            'Keluarga Reza', 
            'Keluarga Ahmad', 
            'Keluarga Simanjuntak', 
            'Keluarga Wijaya', 
            'Keluarga Hutapea', 
            'Keluarga Syarifuddin', 
            'Keluarga Tanuwidjaja', 
            'Keluarga Sasongko', 
            'Keluarga Iskandar', 
            'Keluarga Purnomo'
        ];
        
        foreach ($familyNames as $key => $name) {
            $i = $key + 1;
            \App\Models\User::create([
                'name' => $name,
                'email' => "customer$i@gmail.com",
                'password' => $password,
                'role' => 'customer',
            ]);
        }

        // Block-Religion mapping
        $blockReligions = [
            'Blok A' => 'islam',
            'Blok B' => 'islam',
            'Blok C' => 'protestan',
            'Blok D' => 'protestan',
            'Blok E' => 'katolik',
            'Blok F' => 'katolik',
            'Blok G' => 'hindu',
            'Blok H' => 'hindu',
            'Blok I' => 'budha',
            'Blok J' => 'budha',
            'Blok K' => 'konghucu',
            'Blok L' => 'konghucu',
            'Blok M' => 'umum',
            'Blok N' => 'umum',
        ];

        // Sample almarhum names per religion
        $occupiedNames = [
            'islam' => ['Ahmad Fauzi', 'Siti Nurhaliza', 'Muhammad Ridwan', 'Fatimah Zahra', 'Umar Hadi', 'Khadijah Sari', 'Abdul Rahman', 'Aisyah Putri'],
            'protestan' => ['Johan Siahaan', 'Maria Lumban', 'Petrus Manalu', 'Ruth Simanungkalit', 'Daniel Hutapea', 'Sarah Panggabean'],
            'katolik' => ['Albertus Agung', 'Theresia Kartini', 'Yohanes Surya', 'Bernadette Lim', 'Fransiskus Xaverius', 'Clara Wijaya'],
            'hindu' => ['I Wayan Sudira', 'Ni Made Ayu', 'I Ketut Dharma', 'Ni Luh Putu Sari', 'I Nyoman Gede', 'Ni Kadek Rani'],
            'budha' => ['Tan Ah Kow', 'Lim Mei Ling', 'Wong Siu Fung', 'Chen Wei Lin', 'Ong Beng Huat', 'Lie Siu Lan'],
            'konghucu' => ['Kwee Tjin Hok', 'Oei Hui Lan', 'Tan Eng Hoa', 'Liem Bok Seng', 'Kho Ping Hoo', 'Nio Joe Lan'],
            'umum' => ['Hasan Basri', 'Surya Dharma', 'Budi Utomo', 'Dewi Sartika', 'Kartini Soemantri', 'Diponegoro Putra'],
        ];

        // Heir names for occupied/booked graves
        $heirNames = [
            'Keluarga Reza', 'Keluarga Ahmad', 'Keluarga Simanjuntak', 'Keluarga Wijaya',
            'Keluarga Hutapea', 'Keluarga Syarifuddin', 'Keluarga Tanuwidjaja', 'Keluarga Sasongko',
            'Keluarga Iskandar', 'Keluarga Purnomo',
        ];

        $globalHeirIdx = 0;

        foreach ($blockReligions as $blockName => $religion) {
            $gravesPerBlock = 20;
            $prefix = str_replace('Blok ', '', $blockName);
            $occupiedIdx = 0;
            $namesForReligion = $occupiedNames[$religion] ?? [];

            for ($i = 1; $i <= $gravesPerBlock; $i++) {
                $graveNumber = $prefix . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

                // Distribute statuses: ~40% occupied, ~15% booked, ~45% available
                $rand = rand(1, 100);
                if ($rand <= 40) {
                    $status = 'occupied';
                } elseif ($rand <= 55) {
                    $status = 'booked';
                } else {
                    $status = 'available';
                }

                $buriedName = null;
                $burialDate = null;
                $heirName = null;
                $heirContact = null;

                if ($status === 'occupied') {
                    $buriedName = $namesForReligion[$occupiedIdx % count($namesForReligion)] ?? 'Almarhum';
                    $occupiedIdx++;
                    $burialDate = now()->subDays(rand(30, 3650))->format('Y-m-d');
                    $heirName = $heirNames[$globalHeirIdx % count($heirNames)];
                    $heirContact = '08' . rand(100000000, 999999999);
                    $globalHeirIdx++;
                } elseif ($status === 'booked') {
                    $heirName = $heirNames[$globalHeirIdx % count($heirNames)];
                    $heirContact = '08' . rand(100000000, 999999999);
                    $globalHeirIdx++;
                }

                \App\Models\Grave::create([
                    'block_name' => $blockName,
                    'grave_number' => $graveNumber,
                    'buried_name' => $buriedName,
                    'burial_date' => $burialDate,
                    'heir_name' => $heirName,
                    'heir_contact' => $heirContact,
                    'status' => $status,
                    'religion' => $religion,
                ]);
            }
        }

        // Sample Settings
        \App\Models\Setting::create([
            'key' => 'cemetery_name',
            'value' => 'TPU Harapan Mulya',
            'description' => 'Nama Pemakaman',
        ]);

        \App\Models\Setting::create([
            'key' => 'contact_number',
            'value' => '0812-3456-7890',
            'description' => 'Nomor Kontak Admin',
        ]);

        // Sample Maintenance
        \App\Models\Maintenance::create([
            'block_name' => 'Blok C',
            'description' => 'Pembersihan dan pemotongan rumput area blok C',
            'scheduled_date' => now()->addDays(3)->format('Y-m-d'),
            'status' => 'sedang_dikerjakan',
            'notes' => 'Estimasi selesai 2 hari',
        ]);

        \App\Models\Maintenance::create([
            'block_name' => 'Blok G',
            'description' => 'Perbaikan drainase dan jalan setapak',
            'scheduled_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'dijadwalkan',
            'notes' => 'Menunggu material',
        ]);
    }
}
