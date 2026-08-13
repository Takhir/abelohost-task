<?php

declare(strict_types=1);

namespace App\Seeder;

use PDO;

final class Seeder
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function run(): void
    {
        $this->pdo->beginTransaction();

        try {
            $this->seedCategories();
            $this->seedArticles();

            $this->pdo->commit();

            echo "Seeding completed successfully." . PHP_EOL;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }

    private function seedCategories(): void
    {
        $categories = [
            [
                'name'        => 'PHP',
                'slug'        => 'php',
                'description' => 'Статьи о PHP, его возможностях и современных подходах к разработке.',
            ],
            [
                'name'        => 'MySQL',
                'slug'        => 'mysql',
                'description' => 'Работа с MySQL, SQL-запросами, индексами и проектированием баз данных.',
            ],
            [
                'name'        => 'Docker',
                'slug'        => 'docker',
                'description' => 'Docker, контейнеризация PHP-приложений и локальная разработка.',
            ],
            [
                'name'        => 'Backend',
                'slug'        => 'backend',
                'description' => 'Архитектура backend-приложений, API и работа с сервером.',
            ],
        ];

        $sql = <<<'SQL'
            INSERT INTO categories (name, slug, description)
            VALUES (:name, :slug, :description)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                description = VALUES(description)
        SQL;

        $statement = $this->pdo->prepare($sql);

        foreach ($categories as $category) {
            $statement->execute($category);
        }
    }

    private function seedArticles(): void
    {
        $articles = [
            [
                'image'        => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=1200&q=80',
                'title'        => 'PHP 8.5: что нового',
                'slug'         => 'php-85-chto-novogo',
                'description'  => 'Разбираем основные возможности современного PHP.',
                'content'      => 'PHP 8.5 продолжает развитие языка и предлагает разработчикам новые возможности. В этой статье разберём основные изменения.',
                'views'        => 1250,
                'published_at' => '2026-08-10 10:00:00',
                'categories'   => ['php', 'backend'],
            ],
            [
                'image'        => 'https://images.unsplash.com/photo-1555949963-ff9fe0c870eb?auto=format&fit=crop&w=1200&q=80',
                'title'        => 'PDO и безопасная работа с MySQL',
                'slug'         => 'pdo-i-bezopasnaya-rabota-s-mysql',
                'description'  => 'Как правильно работать с MySQL через PDO.',
                'content'      => 'PDO предоставляет единый интерфейс для работы с базами данных. Использование подготовленных выражений помогает избежать SQL-инъекций.',
                'views'        => 980,
                'published_at' => '2026-08-09 12:00:00',
                'categories'   => ['php', 'mysql'],
            ],
            [
                'image'        => 'https://images.unsplash.com/photo-1605745341112-85968b19335b?auto=format&fit=crop&w=1200&q=80',
                'title'        => 'Docker для PHP-разработчика',
                'slug'         => 'docker-dlya-php-razrabotchika',
                'description'  => 'Создаём окружение PHP + Nginx + MySQL.',
                'content'      => 'Docker позволяет изолировать PHP, Nginx и MySQL и сделать окружение разработки воспроизводимым.',
                'views'        => 1540,
                'published_at' => '2026-08-08 15:30:00',
                'categories'   => ['docker', 'php'],
            ],
            [
                'image'        => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80',
                'title'        => 'Как проектировать backend без фреймворка',
                'slug'         => 'backend-bez-freymvorka',
                'description'  => 'Основы архитектуры небольшого PHP-приложения.',
                'content'      => 'Даже без фреймворка приложение можно разделить на контроллеры, сервисы, репозитории и шаблоны.',
                'views'        => 720,
                'published_at' => '2026-08-07 09:00:00',
                'categories'   => ['backend', 'php'],
            ],
            [
                'image'        => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?auto=format&fit=crop&w=1200&q=80',
                'title'        => 'Индексы в MySQL',
                'slug'         => 'indeksy-v-mysql',
                'description'  => 'Зачем нужны индексы и как они ускоряют запросы.',
                'content'      => 'Индексы позволяют базе данных быстрее находить записи. Но большое количество индексов увеличивает стоимость операций записи.',
                'views'        => 1100,
                'published_at' => '2026-08-06 14:00:00',
                'categories'   => ['mysql', 'backend'],
            ],
            [
                'image'        => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1200&q=80',
                'title'        => 'Nginx и PHP-FPM',
                'slug'         => 'nginx-i-php-fpm',
                'description'  => 'Как Nginx передаёт PHP-запросы PHP-FPM.',
                'content'      => 'Nginx принимает HTTP-запрос, а PHP-файлы передаёт PHP-FPM через FastCGI.',
                'views'        => 890,
                'published_at' => '2026-08-05 11:00:00',
                'categories'   => ['docker', 'backend'],
            ],
        ];

        $articleSql = <<<'SQL'
            INSERT INTO articles (
                image,
                title,
                slug,
                description,
                content,
                views,
                published_at
            )
            VALUES (
                :image,
                :title,
                :slug,
                :description,
                :content,
                :views,
                :published_at
            )
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                description = VALUES(description),
                content = VALUES(content),
                views = VALUES(views),
                published_at = VALUES(published_at)
        SQL;

        $articleStatement = $this->pdo->prepare($articleSql);

        $categoryStatement = $this->pdo->prepare(
            'SELECT id FROM categories WHERE slug = :slug'
        );

        $relationStatement = $this->pdo->prepare(
            'INSERT IGNORE INTO article_categories (article_id, category_id)
             VALUES (:article_id, :category_id)'
        );

        foreach ($articles as $article) {
            $articleStatement->execute([
                'image'        => $article['image'],
                'title'        => $article['title'],
                'slug'         => $article['slug'],
                'description'  => $article['description'],
                'content'      => $article['content'],
                'views'        => $article['views'],
                'published_at' => $article['published_at'],
            ]);

            $articleId = (int) $this->pdo
                ->query(
                    "SELECT id FROM articles WHERE slug = " .
                    $this->pdo->quote($article['slug'])
                )
                ->fetchColumn();

            foreach ($article['categories'] as $categorySlug) {
                $categoryStatement->execute([
                    'slug' => $categorySlug,
                ]);

                $categoryId = $categoryStatement->fetchColumn();

                if (!$categoryId) {
                    continue;
                }

                $relationStatement->execute([
                    'article_id'  => $articleId,
                    'category_id' => $categoryId,
                ]);
            }
        }
    }
}