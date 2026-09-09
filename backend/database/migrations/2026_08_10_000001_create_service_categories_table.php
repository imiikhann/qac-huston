<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        $defaults = [
            ['name' => 'Electrical', 'slug' => 'electrical', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Mechanical', 'slug' => 'mechanical', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Special', 'slug' => 'special', 'is_active' => true, 'sort_order' => 3],
        ];

        foreach ($defaults as $category) {
            DB::table('service_categories')->insertOrIgnore($category);
        }

        if (Schema::hasTable('services') && Schema::hasColumn('services', 'category')) {
            $existing = DB::table('services')->distinct()->pluck('category');

            foreach ($existing as $name) {
                if (blank($name)) {
                    continue;
                }

                DB::table('service_categories')->insertOrIgnore([
                    'name' => ucwords(str_replace('-', ' ', $name)),
                    'slug' => Str::slug($name),
                    'is_active' => true,
                    'sort_order' => 999,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
