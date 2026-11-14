@php
    $img = fn (?string $path) => $path ? asset(str_starts_with($path, 'images/') ? preg_replace('/\.png$/', '.webp', $path) : $path) : null;
    $title = $project->t('title');
    $summary = $project->t('short') ?: $project->t('description');
    $cover = $img($project->image);
    $repo = $project->github_url;
    $live = $project->live_url;
    $stack = collect($project->tags ?? [])->pluck('label')->filter()->values();
    $features = collect($project->t('bullets') ?? [])->filter()->values();
    $responsibilities = $project->lines('responsibilities');
    $technical = $project->lines('technical_decisions');
    $gallery = collect($project->gallery ?? [])->filter()->values();
    $canonical = route('work.show', $project->slug);
    $metaDesc = \Illuminate\Support\Str::limit(strip_tags((string) $summary), 155);
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — Case Study · Muzaki Abdullah Irsyad</title>
    <meta name="description" content="{{ $metaDesc }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Muzaki Abdullah Irsyad">
    <meta property="og:title" content="{{ $title }} — Case Study">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $cover ?? asset('images/mai-social.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }} — Case Study">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ $cover ?? asset('images/mai-social.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v=mai-2">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=mai-2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=mai-2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Syne:wght@700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}?v=4.4">
    <link rel="stylesheet" href="{{ asset('css/work.css') }}?v=1.5">
    @if($cover)<link rel="preload" as="image" href="{{ $cover }}">@endif
    <script>
        (function(){ var t = 'dark'; try { t = localStorage.getItem('theme') || t; } catch (e) {} document.documentElement.setAttribute('data-theme', t); })();
    </script>
    <script type="application/ld+json">{!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => $title,
        'description' => $metaDesc,
        'url' => $canonical,
        'image' => $cover,
        'creator' => ['@type' => 'Person', 'name' => 'Muzaki Abdullah Irsyad', 'url' => url('/')],
        'keywords' => $stack->implode(', ') ?: null,
        'codeRepository' => $repo,
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
</head>
<body class="w-page">
<a class="skip-link sr-only" href="#case">Skip to content</a>

<header class="w-topbar">
    <div class="w-container">
        <a class="nav-brand" href="{{ url('/') }}" aria-label="MAI — {{ __('work.back') }}">
            <svg class="nav-brand-logo" viewBox="323 143 890 590" aria-hidden="true">
                <polygon points="333,160 611,410 863,160 1178,722 1054,722 837,318 611,540 433,370 433,722 333,722"/>
                <polygon points="832,365 870,435 688,722 593,722"/>
                <polygon points="825,550 931,550 971,625 778,625"/>
                <polygon points="1102,224 1202,153 1202,250 1102,322"/>
                <polygon points="1102,350 1202,279 1202,722 1193,722 1102,562"/>
            </svg>
        </a>
        <a class="w-link" href="{{ url('/') }}#works"><i class="fas fa-arrow-left" aria-hidden="true"></i> {{ __('work.back') }}</a>
    </div>
</header>

<main id="case">
    <section class="w-hero w-container">
        <div class="w-hero-meta">
            <span class="w-eyebrow-num">{{ __('work.cat_'.($project->category ?: 'web')) }}</span>
            <span class="w-status" data-status="{{ $project->status }}">{{ __('work.status_'.$project->status) }}</span>
            @if($project->year)<span class="w-chip">{{ $project->year }}</span>@endif
        </div>
        <h1>{{ $title }}</h1>
        @if($summary)<p class="w-hero-lead">{{ $summary }}</p>@endif
        @if($repo || $live)
        <div class="w-actions">
            @if($live)<a class="w-link" href="{{ $live }}" target="_blank" rel="noopener noreferrer">{{ __('work.live') }} <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i></a>@endif
            @if($repo)<a class="w-link" href="{{ $repo }}" target="_blank" rel="noopener noreferrer">{{ __('work.repo') }} <i class="fab fa-github" aria-hidden="true"></i></a>@endif
        </div>
        @endif
    </section>

    @if($cover)
    <figure class="w-cover w-container w-media" style="margin-inline:auto">
        <img src="{{ $cover }}" alt="{{ $title }} — {{ $project->t('project_type') ?: 'preview' }}" width="1672" height="941" fetchpriority="high" decoding="async">
    </figure>
    @endif

    <div class="w-container">
        <dl class="w-facts">
            @if($project->t('project_type'))<div><dt>{{ __('work.type') }}</dt><dd>{{ $project->t('project_type') }}</dd></div>@endif
            @if($project->t('role'))<div><dt>{{ __('work.role') }}</dt><dd>{{ $project->t('role') }}</dd></div>@endif
            <div><dt>{{ __('work.status') }}</dt><dd>{{ __('work.status_'.$project->status) }}</dd></div>
            @if($stack->isNotEmpty())<div><dt>{{ __('work.stack') }}</dt><dd>{{ $stack->implode(' · ') }}</dd></div>@endif
        </dl>

        @if($project->t('description'))
        <section class="w-block"><h2>{{ __('work.overview') }}</h2><div class="w-block-body"><p>{{ $project->t('description') }}</p></div></section>
        @endif
        @if($project->t('problem'))
        <section class="w-block"><h2>{{ __('work.problem') }}</h2><div class="w-block-body"><p>{{ $project->t('problem') }}</p></div></section>
        @endif
        @if($responsibilities)
        <section class="w-block"><h2>{{ __('work.responsibilities') }}</h2><div class="w-block-body"><ul class="w-list">@foreach($responsibilities as $line)<li>{{ $line }}</li>@endforeach</ul></div></section>
        @endif
        @if($project->t('solution'))
        <section class="w-block"><h2>{{ __('work.solution') }}</h2><div class="w-block-body"><p>{{ $project->t('solution') }}</p></div></section>
        @endif
        @if($features->isNotEmpty())
        <section class="w-block"><h2>{{ __('work.features') }}</h2><div class="w-block-body"><ul class="w-list">@foreach($features as $f)<li>{!! strip_tags($f, '<strong><em><b><i>') !!}</li>@endforeach</ul></div></section>
        @endif
        @if($gallery->isNotEmpty())
        <section class="w-block"><h2>{{ __('work.gallery') }}</h2><div class="w-block-body"><div class="w-gallery">
            @foreach($gallery as $g)<figure class="w-media"><img src="{{ $img($g) }}" alt="{{ $title }} — {{ __('work.gallery') }} {{ $loop->iteration }}" width="1600" height="1000" loading="lazy" decoding="async"></figure>@endforeach
        </div></div></section>
        @endif
        @if($technical)
        <section class="w-block"><h2>{{ __('work.technical') }}</h2><div class="w-block-body"><ul class="w-list">@foreach($technical as $line)<li>{{ $line }}</li>@endforeach</ul></div></section>
        @endif
        @if($project->t('lessons'))
        <section class="w-block"><h2>{{ __('work.lessons') }}</h2><div class="w-block-body"><p>{{ $project->t('lessons') }}</p></div></section>
        @endif

        @if($prev && $next)
        <nav class="w-pager" aria-label="{{ __('work.next') }}">
            <a href="{{ route('work.show', $prev->slug) }}"><small><i class="fas fa-arrow-left" aria-hidden="true"></i> {{ __('work.prev') }}</small><strong>{{ $prev->t('title') }}</strong></a>
            <a href="{{ route('work.show', $next->slug) }}"><small>{{ __('work.next') }} <i class="fas fa-arrow-right" aria-hidden="true"></i></small><strong>{{ $next->t('title') }}</strong></a>
        </nav>
        @endif
    </div>
</main>
</body>
</html>
