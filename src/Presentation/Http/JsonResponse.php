<?php

declare(strict_types=1);

namespace App\Presentation\Http;

final class JsonResponse
{
    public function __construct(
        private readonly array $data,
        private readonly int $statusCode = 200
    ) {
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $this->data,
            JSON_THROW_ON_ERROR
        );
    }
}