<?php

declare(strict_types=1);

use App\Presentation\Http\JsonResponse;

require dirname(__DIR__) . '/vendor/autoload.php';

$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

if ($method === 'POST' && $path === '/api/plans') {
    try {
        $body = file_get_contents('php://input');

        $data = json_decode(
            $body,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $controller = require dirname(__DIR__)
            . '/config/bootstrap.php';

        $controller($data)->send();
    } catch (JsonException) {
        (new JsonResponse(
            ['error' => 'Invalid JSON body.'],
            400
        ))->send();
    }

    return;
}

(new JsonResponse(
    ['error' => 'Not found.'],
    404
))->send();