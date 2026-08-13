<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'PHP Blog',
        'env' => getenv('APP_ENV') ?: 'development',
    ],

    'db' => [
        'host' => getenv('DB_HOST') ?: 'db',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'blog',
        'user' => getenv('DB_USER') ?: 'blog',
        'password' => getenv('DB_PASSWORD') ?: 'blog_password',
    ],

    'smarty' => [
        'template_dir' => __DIR__ . '/../templates',
        'compile_dir' => __DIR__ . '/../var/cache',
        'cache_dir' => __DIR__ . '/../var/cache',
    ],
];