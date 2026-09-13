<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            SiteSettingSeeder::class,
            HeroSectionSeeder::class,
            AgencySeeder::class,
            AboutSectionSeeder::class,
            ClientSeeder::class,
        ]);
    }
}
