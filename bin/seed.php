<?php

declare(strict_types=1);

use App\Database\Database;
use App\Seeder\Seeder;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'db',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'blog',
        'user' => getenv('DB_USER') ?: 'blog',
        'password' => getenv('DB_PASSWORD') ?: 'blog_password',
    ],
];

$database = new Database($config);

$seeder = new Seeder(
    $database->getConnection()
);

$seeder->run();