<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE memberships MODIFY status ENUM('pending','active','expired','cancelled') NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE memberships MODIFY status ENUM('active','expired','cancelled') NOT NULL DEFAULT 'active'");
    }
};
