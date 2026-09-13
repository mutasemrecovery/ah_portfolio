<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title_en', 200);
            $table->string('title_ar', 200);
            $table->string('subtitle_en', 300)->nullable();
            $table->string('subtitle_ar', 300)->nullable();
            $table->string('cta_text_en', 100)->nullable();
            $table->string('cta_text_ar', 100)->nullable();
            $table->string('cta_link', 200)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_sections');
    }
};
