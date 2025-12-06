<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Application;
use App\Models\Group;
use App\Models\GroupScore;
use App\Models\GroupItemScore;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Ambil master data group dan items
        $groups = Group::with('groupItems.items')->get();

        $jumlahData = 25;

        for ($i = 0; $i < $jumlahData; $i++) {
            
            // --- LOGIKA GENDER ---
            // 1. Pilih 1 atau 2 secara acak
            $genderCode = $faker->randomElement(['1', '2']);
            
            // 2. Tentukan parameter untuk Faker Name agar namanya sesuai gender
            // Jika '1', minta faker nama laki-laki. Jika 'P', minta perempuan'.
            $genderForFaker = ($genderCode == '1') ? 'Laki-Laki' : 'Perempuan';

            // --- A. Simpan Data Pemohon ---
            $application = Application::create([
                'application_number'      => 'APP-' . $faker->unique()->numerify('#####'),
                
                // Generate nama sesuai gender di atas
                'application_name'        => $faker->name($genderForFaker),
                
                // Simpan gender ke database
                'application_gender'      => $genderCode, 
                'application_birth_place' => $faker->city,
                'application_birth_date'  => $faker->date('Y-m-d', '-25 years'),
                'application_address'     => $faker->address,
                'application_postal_code' => $faker->postcode,
                'application_summary_score' => 0
            ]);

            $totalApplicationScore = 0;

            // --- B. Loop Perhitungan Skor (Sama seperti sebelumnya) ---
            foreach ($groups as $group) {
                $totalScoreInThisGroup = 0;

                foreach ($group->groupItems as $groupItem) {
                    if($groupItem->items->isEmpty()) continue;

                    $randomItem = $groupItem->items->random();
                    $calculatedItemScore = $randomItem->item_rate * $groupItem->group_item_rate;

                    GroupItemScore::create([
                        'application_id'    => $application->application_id,
                        'group_item_id'     => $groupItem->group_item_id,
                        'item_id'           => $randomItem->item_id,
                        'group_item_score'  => $calculatedItemScore
                    ]);

                    $totalScoreInThisGroup += $calculatedItemScore;
                }

                $finalGroupScore = $totalScoreInThisGroup * $group->group_rate;

                GroupScore::create([
                    'application_id' => $application->application_id,
                    'group_id'       => $group->group_id,
                    'group_score'    => $finalGroupScore
                ]);

                $totalApplicationScore += $finalGroupScore;
            }

            // --- C. Update Skor Akhir ---
            $application->update([
                'application_summary_score' => $totalApplicationScore
            ]);
        }
    }
}