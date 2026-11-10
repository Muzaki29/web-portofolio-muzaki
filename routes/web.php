<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ChatbotController;

Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'], true)) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->where('locale', 'id|en')->name('locale.switch');

Route::get('/', function () {
    // Generate secure dynamic Math CAPTCHA
    $num1 = rand(1, 9);
    $num2 = rand(1, 9);
    Session::put('captcha_answer', $num1 + $num2);
    Session::put('captcha_generated_at', now()->timestamp); // expiry check
    $captchaQuestion = "$num1 + $num2";

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

    // Works come from the admin CMS; config + lang stay as fallback until the table is seeded.
    $worksProjects = rescue(
        fn () => \App\Models\Project::where('is_published', true)->orderBy('sort_order')->get()->map->toLanding()->all(),
        [],
        false
    );
    if (empty($worksProjects) && ! rescue(fn () => \App\Models\Project::exists(), false, false)) {
        $worksProjects = collect(config('portfolio.works_projects', []))->map(fn ($p) => [
            'gradient' => $p['gradient'] ?? null,
            'image' => $p['image'] ?? null,
            'title' => __($p['title_key']),
            'short' => __($p['short_key']),
            'tagline' => __($p['tagline_key']),
            'desc' => __($p['desc_key']),
            'bullets' => array_map(fn ($k) => __($k), $p['bullets'] ?? []),
            'tags' => $p['tags'] ?? [],
            'github_url' => $p['github_url'] ?? null,
            'live_url' => $p['live_url'] ?? null,
            'link_type' => $p['link_type'] ?? 'none',
        ])->all();
    }

    $section = fn (string $name) => rescue(fn () => \App\Models\ContentItem::landing($name)->get(), collect(), false);
    $s = fn (string $key, $default) => \App\Models\Setting::get($key, $default);

    return view('portfolio', [
        'experiences' => $section('experience'),
        'timelineItems' => $section('timeline'),
        'certificates' => $section('certificate'),
        'profile' => [
            'hero_role' => $s('hero_role', __('hero.kicker')),
            'about_title' => $s('about_title', __('about.title')),
            'about_description' => $s('about_description', __('about.description')),
            'location' => $s('location', 'Jakarta, Indonesia'),
            'contact_email' => $s('contact_email', 'muzaki@syahdulab.biz.id'),
            'github_username' => $s('github_username', 'Muzaki29'),
            'linkedin_url' => $s('linkedin_url', 'https://www.linkedin.com/in/muzaki-abdullah-irsyad-893a98220/'),
            'linkedin_label' => $s('linkedin_label', 'muzaki-abdullah-irsyad'),
            'dribbble_url' => $s('dribbble_url', 'https://dribbble.com/muzaki-abdullah-irsyad'),
            'pinterest_url' => $s('pinterest_url', 'https://id.pinterest.com/syahdulab/'),
            'pinterest_label' => $s('pinterest_label', 'SyahduLab'),
            'instagram_username' => $s('instagram_username', 'priasyahdu'),
            'threads_username' => $s('threads_username', 'tuanpriasyahdu'),
        ],
        'worksProjects' => $worksProjects,
        'featuredWorks' => $featuredWorks,
        'certifications' => $certifications,
        'captchaQuestion' => $captchaQuestion,
    ]);
})->middleware(\App\Http\Middleware\TrackVisit::class);

// Max 3 attempts per minute per IP — prevents email flood/spam
Route::post('/contact', [ContactController::class, 'sendEmail'])
    ->name('contact.send')
    ->middleware('throttle:contact');

// AI Chatbot API — strict role, RAG context, rate-limited
// Disabled unless CHATBOT_ENABLED=true (404 so the Gemini key can't be used while hidden).
Route::post('/api/chatbot', [ChatbotController::class, 'chat'])
    ->name('chatbot.chat')
    ->middleware('throttle:chatbot');


// ── Admin CMS (Inertia + React). Entry point is only reachable by URL: /loginadmin ──
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ProjectController as AdminProjects;
use App\Http\Controllers\Admin\ContentController as AdminContent;
use App\Http\Controllers\Admin\SettingsController as AdminSettings;
use App\Http\Middleware\HandleInertiaRequests;

Route::middleware(HandleInertiaRequests::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/loginadmin', [AdminAuth::class, 'show'])->name('admin.login');
        Route::post('/loginadmin', [AdminAuth::class, 'login'])->middleware('throttle:5,1');
    });

    Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboard::class)->name('dashboard');
        Route::post('/logout', [AdminAuth::class, 'logout'])->name('logout');
        Route::post('/projects/reorder', [AdminProjects::class, 'reorder'])->name('projects.reorder');
        Route::patch('/projects/{project}/toggle', [AdminProjects::class, 'toggle'])->name('projects.toggle');
        Route::resource('projects', AdminProjects::class)->except('show');

        Route::get('/settings', [AdminSettings::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [AdminSettings::class, 'update'])->name('settings.update');

        // Experience / timeline / certificate share one generic controller.
        Route::prefix('content/{section}')->name('content.')->whereIn('section', ['experience', 'timeline', 'certificate'])->group(function () {
            Route::get('/', [AdminContent::class, 'index'])->name('index');
            Route::get('/create', [AdminContent::class, 'create'])->name('create');
            Route::post('/', [AdminContent::class, 'store'])->name('store');
            Route::post('/reorder', [AdminContent::class, 'reorder'])->name('reorder');
            Route::get('/{id}/edit', [AdminContent::class, 'edit'])->name('edit')->whereNumber('id');
            Route::put('/{id}', [AdminContent::class, 'update'])->name('update')->whereNumber('id');
            Route::patch('/{id}/toggle', [AdminContent::class, 'toggle'])->name('toggle')->whereNumber('id');
            Route::delete('/{id}', [AdminContent::class, 'destroy'])->name('destroy')->whereNumber('id');
        });
    });
});
