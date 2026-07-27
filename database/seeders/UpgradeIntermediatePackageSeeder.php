<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpgradeIntermediatePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Package::updateOrCreate(
            ['slug' => 'upgrade-intermediate'],
            [
                'name' => 'Upgrade Intermediate',
                'price' => 150000,
                'description' => 'Upgrade dari paket Beginner ke Intermediate — membuka seluruh modul & Song TAB.',
                'benefits' => 'Upgrade fee to move from Beginner to Intermediate.',
                'image' => null,
            ]
        );
    }
}
