<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin accounts are created with `php artisan admin:create`, never seeded.
        $this->call([ProjectSeeder::class, ProjectCaseStudySeeder::class, CmsSeeder::class]);
    }
}
