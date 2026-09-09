<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['name' => 'Electrical', 'sort_order' => 1],
            ['name' => 'Mechanical', 'sort_order' => 2],
            ['name' => 'Special', 'sort_order' => 3],
        ];

        foreach ($defaults as $category) {
            ServiceCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category,
            );
        }

        foreach (Service::query()->select('category')->distinct()->pluck('category') as $name) {
            if (blank($name)) {
                continue;
            }

            ServiceCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => ucwords(str_replace('-', ' ', $name)), 'is_active' => true, 'sort_order' => 999],
            );
        }
    }
}
