<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grave;
use Illuminate\Support\Facades\DB;

class MapExpandedSeeder extends Seeder
{
    public function run()
    {
        // To be completely safe with dummy data, we will wipe existing ones.
        // It's a demo system and the customer wants a clean new layout.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Grave::truncate();
        DB::table('payments')->truncate();
        $blockReligions = [
            'A' => 'islam', 'B' => 'islam',
            'C' => 'kristen', 'D' => 'kristen',
            'E' => 'katolik', 'F' => 'katolik',
            'G' => 'hindu', 'H' => 'hindu',
            'I' => 'budha', 'J' => 'budha',
            'K' => 'konghucu', 'L' => 'konghucu',
            'M' => 'umum', 'N' => 'umum'
        ];
        
        $statuses = ['available', 'available', 'available', 'occupied', 'booked'];
        
        foreach ($blockReligions as $block => $religion) {
            for ($i = 1; $i <= 20; $i++) {
                Grave::create([
                    'block_name' => 'Blok ' . $block,
                    'grave_number' => $block . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'status' => $statuses[array_rand($statuses)],
                    'religion' => $religion,
                    'buried_name' => null,
                    'burial_date' => null,
                    'heir_name' => null,
                    'heir_contact' => null
                ]);
            }
        }
    }
}
