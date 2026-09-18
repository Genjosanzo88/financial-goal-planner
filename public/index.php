<?php

declare(strict_types=1);

use App\Presentation\Http\JsonResponse;

require dirname(__DIR__) . '/vendor/autoload.php';

header('X-Content-Type-Options: nosniff');

header('Referrer-Policy: no-referrer');

header('X-Frame-Options: DENY');

header(
    "Content-Security-Policy: "
    . "default-src 'self'; "
    . "script-src 'self'; "
    . "style-src 'self'; "
    . "img-src 'self'; "
    . "connect-src 'self'; "
    . "base-uri 'none'; "
    . "form-action 'self'; "
    . "frame-ancestors 'none'"
);

$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

// Página principal.
if ($method === 'GET' && $path === '/') {
    header('Content-Type: text/html; charset=utf-8');

    readfile(__DIR__ . '/index.html');

    return;
}

// Crear un plan.
if ($method === 'POST' && $path === '/api/plans') {
    try {
        $body = file_get_contents('php://input');

        $data = json_decode(
            $body,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (!is_array($data)) {
            throw new JsonException(
                'JSON body must be an object.'
            );
        }

        $controllers = require dirname(__DIR__)
            . '/config/bootstrap.php';

        $controllers['create']($data)->send();
    } catch (JsonException) {
        (new JsonResponse(
            ['error' => 'Invalid JSON body.'],
            400
        ))->send();
    }

    return;
}

// Consultar los últimos planes.
if ($method === 'GET' && $path === '/api/plans') {
    $controllers = require dirname(__DIR__)
        . '/config/bootstrap.php';

    $controllers['list']()->send();

    return;
}

(new JsonResponse(
    ['error' => 'Not found.'],
    404
))->send();