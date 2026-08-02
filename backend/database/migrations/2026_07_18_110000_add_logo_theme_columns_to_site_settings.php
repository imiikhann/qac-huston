<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('logo_light')->nullable()->after('logo');
            $table->string('logo_dark')->nullable()->after('logo_light');
            $table->string('logo_max_width', 20)->nullable()->after('logo_dark');
            $table->string('logo_max_height', 20)->nullable()->after('logo_max_width');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['logo_light', 'logo_dark', 'logo_max_width', 'logo_max_height']);
        });
    }
};
