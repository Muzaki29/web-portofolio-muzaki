<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create {email} {--name=Admin}', function (string $email) {
    $password = $this->secret('Password (min 10 karakter)');
    if (! is_string($password) || strlen($password) < 10) {
        $this->error('Password minimal 10 karakter.');
        return 1;
    }
    \App\Models\User::updateOrCreate(['email' => $email], ['name' => $this->option('name'), 'password' => $password]);
    $this->info("Admin {$email} siap. Login di /loginadmin");
})->purpose('Create or reset an admin account for /loginadmin');
