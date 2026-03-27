<?php

declare(strict_types=1);

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
