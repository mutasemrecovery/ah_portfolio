<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name_en' => 'Client 1',  'name_ar' => 'عميل 1',  'sort_order' => 1],
            ['name_en' => 'Client 2',  'name_ar' => 'عميل 2',  'sort_order' => 2],
            ['name_en' => 'Client 3',  'name_ar' => 'عميل 3',  'sort_order' => 3],
            ['name_en' => 'Client 4',  'name_ar' => 'عميل 4',  'sort_order' => 4],
            ['name_en' => 'Client 5',  'name_ar' => 'عميل 5',  'sort_order' => 5],
            ['name_en' => 'Client 6',  'name_ar' => 'عميل 6',  'sort_order' => 6],
            ['name_en' => 'Client 7',  'name_ar' => 'عميل 7',  'sort_order' => 7],
            ['name_en' => 'Client 8',  'name_ar' => 'عميل 8',  'sort_order' => 8],
            ['name_en' => 'Client 9',  'name_ar' => 'عميل 9',  'sort_order' => 9],
            ['name_en' => 'Client 10', 'name_ar' => 'عميل 10', 'sort_order' => 10],
            ['name_en' => 'Client 11', 'name_ar' => 'عميل 11', 'sort_order' => 11],
            ['name_en' => 'Client 12', 'name_ar' => 'عميل 12', 'sort_order' => 12],
        ];

        foreach ($clients as $c) {
            Client::updateOrCreate(
                ['name_en' => $c['name_en']],
                array_merge($c, ['logo' => null, 'website_url' => null, 'is_active' => true])
            );
        }
    }
}
