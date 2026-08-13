<?php

declare(strict_types=1);

namespace App\Repository;

use InvalidArgumentException;
use PDO;

final class ArticleRepository
{
    private const SORTS = [
        'date' => 'a.published_at',
        'views' => 'a.views',
    ];

    public function __construct(
        private PDO $db
    ) {
    }

    public function findLatestByCategory(
        int $categoryId,
        int $limit = 3
    ): array {
        $stmt = $this->db->prepare(
            '
            SELECT
                a.id,
                a.image,
                a.title,
                a.slug,
                a.description,
                a.views,
                a.published_at
            FROM articles a
            JOIN article_categories ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
            ORDER BY
                a.published_at DESC,
                a.id DESC
            LIMIT :limit
            '
        );

        $stmt->bindValue(
            ':category_id',
            $categoryId,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findByCategory(
        int $categoryId,
        string $sort,
        int $page,
        int $perPage
    ): array {
        if (!isset(self::SORTS[$sort])) {
            throw new InvalidArgumentException(
                'Invalid sort'
            );
        }

        $orderBy = self::SORTS[$sort];

        $offset = ($page - 1) * $perPage;

        $sql = "
            SELECT
                a.id,
                a.image,
                a.title,
                a.slug,
                a.description,
                a.views,
                a.published_at
            FROM articles a
            JOIN article_categories ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id
            ORDER BY
                {$orderBy} DESC,
                a.id DESC
            LIMIT :limit
            OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(
            ':category_id',
            $categoryId,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':limit',
            $perPage,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countByCategory(
        int $categoryId
    ): int {
        $stmt = $this->db->prepare(
            '
            SELECT COUNT(*)
            FROM article_categories
            WHERE category_id = :category_id
            '
        );

        $stmt->execute([
            'category_id' => $categoryId,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function findBySlug(
        string $slug
    ): ?array {
        $stmt = $this->db->prepare(
            '
            SELECT
                id,
                image,
                title,
                slug,
                description,
                content,
                views,
                published_at,
                created_at,
                updated_at
            FROM articles
            WHERE slug = :slug
            LIMIT 1
            '
        );

        $stmt->execute([
            'slug' => $slug,
        ]);

        $article = $stmt->fetch();

        return $article ?: null;
    }

    public function incrementViews(
        int $articleId
    ): void {
        $stmt = $this->db->prepare(
            '
            UPDATE articles
            SET views = views + 1
            WHERE id = :id
            '
        );

        $stmt->execute([
            'id' => $articleId,
        ]);
    }

    public function findCategories(
        int $articleId
    ): array {
        $stmt = $this->db->prepare(
            '
            SELECT
                c.id,
                c.name,
                c.slug
            FROM categories c
            JOIN article_categories ac ON ac.category_id = c.id
            WHERE ac.article_id = :article_id
            ORDER BY c.name ASC
            '
        );

        $stmt->execute([
            'article_id' => $articleId,
        ]);

        return $stmt->fetchAll();
    }

    public function findSimilar(
        int $articleId,
        int $limit = 3
    ): array {
        $stmt = $this->db->prepare(
            '
        SELECT
            a.id,
            a.image,
            a.title,
            a.slug,
            a.description,
            a.views,
            a.published_at,
            COUNT(DISTINCT candidate_ac.category_id) AS common_categories
        FROM articles a
        JOIN article_categories candidate_ac ON candidate_ac.article_id = a.id
        JOIN article_categories current_ac ON current_ac.category_id = candidate_ac.category_id
        WHERE current_ac.article_id = :current_article_id
          AND a.id <> :excluded_article_id
        GROUP BY
            a.id,
            a.image,
            a.title,
            a.slug,
            a.description,
            a.views,
            a.published_at
        ORDER BY
            common_categories DESC,
            a.published_at DESC,
            a.id DESC
        LIMIT :limit
        '
        );

        $stmt->bindValue(
            ':current_article_id',
            $articleId,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':excluded_article_id',
            $articleId,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }
}