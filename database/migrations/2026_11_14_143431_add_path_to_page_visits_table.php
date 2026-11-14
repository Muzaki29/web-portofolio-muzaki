<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            // "/" for the landing page, "/work/{slug}" for case studies. Older rows are landing views.
            $table->string('path', 191)->default('/')->index();
        });
    }

    public function down(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            $table->dropIndex(['path']);
            $table->dropColumn('path');
        });
    }
};
