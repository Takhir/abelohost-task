<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class CategoryRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare(
            '
            SELECT
                id,
                name,
                slug,
                description
            FROM categories
            WHERE slug = :slug
            LIMIT 1
            '
        );

        $stmt->execute([
            'slug' => $slug,
        ]);

        $category = $stmt->fetch();

        return $category ?: null;
    }

    public function findCategoriesWithArticles(): array
    {
        $stmt = $this->db->query(
            '
            SELECT
                c.id,
                c.name,
                c.slug,
                c.description
            FROM categories c
            WHERE EXISTS (
                SELECT 1
                FROM article_categories ac
                WHERE ac.category_id = c.id
            )
            ORDER BY c.name ASC
            '
        );

        return $stmt->fetchAll();
    }
}