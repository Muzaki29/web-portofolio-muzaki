<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('portfolio', [
        'featuredWorks' => config('portfolio.featured_works', []),
        'certifications' => config('portfolio.certifications', []),
    ]);
});

Route::post('/contact', [ContactController::class, 'sendEmail'])->name('contact.send');
