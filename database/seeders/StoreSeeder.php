<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            [
                'name' => 'Boulangerie Ciney',
                'slug' => 'boulangerie-ciney',
                'city' => 'Ciney',
                'type' => 'bakery',
                'api_base_url' => null,
            ],
            [
                'name' => 'Boulangerie Dinant',
                'slug' => 'boulangerie-dinant',
                'city' => 'Dinant',
                'type' => 'bakery',
                'api_base_url' => null,
            ],
            [
                'name' => 'Boulangerie Namur',
                'slug' => 'boulangerie-namur',
                'city' => 'Namur',
                'type' => 'bakery',
                'api_base_url' => null,
            ],
            [
                'name' => 'Boulangerie Jambes',
                'slug' => 'boulangerie-jambes',
                'city' => 'Jambes',
                'type' => 'bakery',
                'api_base_url' => null,
            ],
            [
                'name' => 'Boulangerie Biron',
                'slug' => 'boulangerie-biron',
                'city' => 'Biron',
                'type' => 'bakery',
                'api_base_url' => null,
            ],
            [
                'name' => 'Italien',
                'slug' => 'italien',
                'city' => null,
                'type' => 'italian',
                'api_base_url' => null,
            ],
            [
                'name' => 'Sport',
                'slug' => 'sport',
                'city' => null,
                'type' => 'sport',
                'api_base_url' => null,
            ],
        ];

        foreach ($stores as $store) {
            Store::updateOrCreate(
                ['slug' => $store['slug']],
                $store,
            );
        }
    }
}