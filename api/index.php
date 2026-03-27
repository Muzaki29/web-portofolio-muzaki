<?php

declare(strict_types=1);

if (getenv('VERCEL')) {
    $cacheDir = '/tmp/bootstrap/cache';

    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0777, true);
    }

    if (!getenv('APP_PACKAGES_CACHE')) {
        putenv('APP_PACKAGES_CACHE=' . $cacheDir . '/packages.php');
    }
    if (!getenv('APP_SERVICES_CACHE')) {
        putenv('APP_SERVICES_CACHE=' . $cacheDir . '/services.php');
    }
    if (!getenv('APP_CONFIG_CACHE')) {
        putenv('APP_CONFIG_CACHE=' . $cacheDir . '/config.php');
    }
    if (!getenv('APP_ROUTES_CACHE')) {
        putenv('APP_ROUTES_CACHE=' . $cacheDir . '/routes.php');
    }
    if (!getenv('APP_EVENTS_CACHE')) {
        putenv('APP_EVENTS_CACHE=' . $cacheDir . '/events.php');
    }
}

try {
    require __DIR__ . '/../public/index.php';
} catch (Throwable $e) {
    http_response_code(500);

    $debugEnabled = filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN);

    if ($debugEnabled) {
        header('Content-Type: text/plain; charset=utf-8');
        echo "Vercel Laravel bootstrap error\n";
        echo "Message: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
        echo $e->getTraceAsString();
        exit;
    }

    echo 'Internal Server Error';
}
