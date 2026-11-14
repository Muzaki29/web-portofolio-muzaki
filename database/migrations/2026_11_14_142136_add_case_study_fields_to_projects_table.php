<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
            $table->boolean('featured')->default(false);
            $table->string('category', 40)->nullable();   // solution type, see config/cms.php project_categories
            $table->string('status', 24)->default('implemented'); // implemented|verified|partial|in_development|concept
            $table->string('year', 20)->nullable();
            // Translatable {"id","en"}; all optional — case study hides empty sections.
            $table->json('project_type')->nullable();
            $table->json('role')->nullable();
            $table->json('problem')->nullable();
            $table->json('solution')->nullable();
            $table->json('responsibilities')->nullable();     // one per line
            $table->json('technical_decisions')->nullable();  // one per line
            $table->json('lessons')->nullable();
            $table->json('gallery')->nullable();              // ["storage/..."]
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'featured', 'category', 'status', 'year', 'project_type', 'role', 'problem', 'solution', 'responsibilities', 'technical_decisions', 'lessons', 'gallery']);
        });
    }
};
