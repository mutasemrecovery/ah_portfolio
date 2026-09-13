<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agency_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
            $table->enum('type', ['video', 'image', 'website']);
            $table->string('file_path', 500)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->string('title_en', 300)->nullable();
            $table->string('title_ar', 300)->nullable();
            $table->string('url', 500)->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_media');
    }
};
