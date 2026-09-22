<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE agency_media MODIFY type ENUM('video', 'image', 'visual_identity', 'website') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE agency_media MODIFY type ENUM('video', 'image', 'website') NOT NULL");
    }
};
