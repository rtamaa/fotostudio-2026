<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Silver',
                'slug' => 'paket-silver',  // ← TAMBAHKAN INI
                'description' => '20 menit sesi foto dengan 5 hasil edit terbaik.',
                'short_description' => '20 menit • 5 edited photos',
                'duration' => 20,
                'price' => 150000,
                'max_people' => 2,
                'is_active' => true,  // ← TAMBAHKAN INI
                'sort_order' => 1,
            ],
            [
                'name' => 'Paket Gold',
                'slug' => 'paket-gold',  // ← TAMBAHKAN INI
                'description' => '40 menit sesi foto dengan 10 hasil edit + 1 frame.',
                'short_description' => '40 menit • 10 edited photos + frame',
                'duration' => 40,
                'price' => 250000,
                'max_people' => 4,
                'is_active' => true,  // ← TAMBAHKAN INI
                'sort_order' => 2,
            ],
            [
                'name' => 'Paket Platinum',
                'slug' => 'paket-platinum',  // ← TAMBAHKAN INI
                'description' => '60 menit sesi foto dengan 15 hasil edit + album + 2 frame.',
                'short_description' => '60 menit • 15 edited photos + album',
                'duration' => 60,
                'price' => 350000,
                'max_people' => 6,
                'is_active' => true,  // ← TAMBAHKAN INI
                'sort_order' => 3,
            ],
        ];
        
        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}