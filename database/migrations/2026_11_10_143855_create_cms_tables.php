<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Experience, timeline and certificate entries. Field shape per section lives in config/cms.php.
        Schema::create('content_items', function (Blueprint $table) {
            $table->id();
            $table->string('section', 32)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->json('data');
            $table->timestamps();
        });

        // Profile, contact and social values (key → {"id": "...", "en": "..."} or plain string).
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        // One row per landing-page view by a non-bot visitor. IP is stored only as a salted hash.
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->date('visited_on')->index();
            $table->string('visitor_hash', 64)->index();
            $table->string('referrer_host')->nullable();
            $table->string('device', 16);
            $table->string('locale', 5)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('content_items');
    }
};
