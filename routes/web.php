<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatbotController;

Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'], true)) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->where('locale', 'id|en')->name('locale.switch');

Route::get('/', HomeController::class)->middleware(\App\Http\Middleware\TrackVisit::class);

Route::get('/work/{slug}', [\App\Http\Controllers\WorkController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('work.show')
    ->middleware(\App\Http\Middleware\TrackVisit::class);

// Sitemap: homepage + every published case study.
Route::get('/sitemap.xml', function () {
    $projects = rescue(fn () => \App\Models\Project::where('is_published', true)->whereNotNull('slug')->orderBy('sort_order')->get(['slug', 'updated_at']), collect(), false);
    $urls = collect([['loc' => url('/'), 'lastmod' => null]])
        ->merge($projects->map(fn ($p) => ['loc' => route('work.show', $p->slug), 'lastmod' => $p->updated_at?->toAtomString()]));
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."
".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $u) {
        $xml .= '<url><loc>'.e($u['loc']).'</loc>'.($u['lastmod'] ? '<lastmod>'.$u['lastmod'].'</lastmod>' : '').'</url>';
    }

    return response($xml.'</urlset>', 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');

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
use App\Http\Controllers\Admin\MailController as AdminMail;
use App\Http\Middleware\HandleInertiaRequests;

Route::middleware(HandleInertiaRequests::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/loginadmin', [AdminAuth::class, 'show'])->name('admin.login');
        Route::post('/loginadmin', [AdminAuth::class, 'login'])->middleware('throttle:5,1');
        Route::get('/auth/google/redirect', [AdminAuth::class, 'googleRedirect'])->name('admin.google')->middleware('throttle:10,1');
        Route::get('/auth/google/callback', [AdminAuth::class, 'googleCallback'])->middleware('throttle:10,1');
    });

    Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboard::class)->name('dashboard');
        Route::post('/logout', [AdminAuth::class, 'logout'])->name('logout');
        Route::post('/projects/reorder', [AdminProjects::class, 'reorder'])->name('projects.reorder');
        Route::patch('/projects/{project}/toggle', [AdminProjects::class, 'toggle'])->name('projects.toggle');
        Route::resource('projects', AdminProjects::class)->except('show');

        Route::get('/mail', [AdminMail::class, 'index'])->name('mail.index');
        Route::post('/mail/refresh', [AdminMail::class, 'refresh'])->name('mail.refresh');
        Route::get('/mail/{mailbox}/messages/{uid}', [AdminMail::class, 'show'])->whereNumber('uid')->middleware('throttle:60,1')->name('mail.show');
        Route::post('/mail/{mailbox}/messages/{uid}/flags', [AdminMail::class, 'flags'])->whereNumber('uid')->middleware('throttle:60,1')->name('mail.flags');
        Route::post('/mail/{mailbox}/messages/{uid}/move', [AdminMail::class, 'move'])->whereNumber('uid')->middleware('throttle:30,1')->name('mail.move');
        Route::get('/mail/{mailbox}/messages/{uid}/attachments/{attachment}', [AdminMail::class, 'attachment'])->whereNumber('uid')->middleware('throttle:30,1')->name('mail.attachment');
        Route::post('/mail/{mailbox}/send', [AdminMail::class, 'send'])->middleware('throttle:10,1')->name('mail.send');

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
