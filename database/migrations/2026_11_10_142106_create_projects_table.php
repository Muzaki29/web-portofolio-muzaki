<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->string('image')->nullable();
            $table->string('gradient')->nullable();
            // Translatable fields: {"id": "...", "en": "..."}; bullets: {"id": [...], "en": [...]}
            $table->json('title');
            $table->json('short')->nullable();
            $table->json('tagline')->nullable();
            $table->json('description')->nullable();
            $table->json('bullets')->nullable();
            $table->json('tags')->nullable(); // [{"icon": "...", "label": "..."}]
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->string('link_type')->default('none'); // github | live | none
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
