<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ContactController;

Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'], true)) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->where('locale', 'id|en')->name('locale.switch');

Route::get('/', function () {
    $worksConfig = config('portfolio.featured_works', []);
    $featuredWorks = collect($worksConfig)->map(function ($w) {
        $tags = isset($w['tags_key']) ? __($w['tags_key']) : '';
        return [
            'title' => __($w['title_key']),
            'description' => __($w['description_key']),
            'tags' => array_map('trim', explode(',', $tags)),
            'icon' => $w['icon'] ?? 'fas fa-code',
            'live_url' => $w['live_url'] ?? null,
            'github_url' => $w['github_url'] ?? null,
        ];
    })->all();

    $certsConfig = config('portfolio.certifications', []);
    $certifications = collect($certsConfig)->map(function ($cat) {
        return [
            'category' => __($cat['category_key']),
            'items' => collect($cat['items'] ?? [])->map(fn ($item) => [
                'name' => __($item['name_key']),
                'url' => $item['url'] ?? null,
            ])->all(),
        ];
    })->all();

    return view('portfolio', [
        'featuredWorks' => $featuredWorks,
        'certifications' => $certifications,
    ]);
});

Route::post('/contact', [ContactController::class, 'sendEmail'])->name('contact.send');
