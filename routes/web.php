<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('portfolio');
});

Route::post('/contact', [ContactController::class, 'sendEmail'])->name('contact.send');
