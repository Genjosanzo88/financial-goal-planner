<?php

declare(strict_types=1);

$host = getenv('DB_HOST') ?: 'db';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_DATABASE') ?: 'financial_planner';
$username = getenv('DB_USERNAME') ?: 'app';
$password = getenv('DB_PASSWORD') ?: 'app_password';

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    $host,
    $port,
    $database
);

return new PDO(
    $dsn,
    $username,
    $password,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);