<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_media', function (Blueprint $table) {
            $table->string('sector', 100)->nullable()->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('agency_media', function (Blueprint $table) {
            $table->dropColumn('sector');
        });
    }
};
