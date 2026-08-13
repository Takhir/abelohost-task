<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Smarty\Smarty;

final class CategoryController
{
    private const PER_PAGE = 6;

    public function __construct(
        private CategoryRepository $categories,
        private ArticleRepository $articles,
        private Smarty $smarty
    ) {
    }

    public function show(string $slug): void
    {
        $category = $this->categories->findBySlug($slug);

        if ($category === null) {
            http_response_code(404);
            $this->smarty->display('404.tpl');
            return;
        }

        $sort = $_GET['sort'] ?? 'date';

        if (!in_array($sort, ['date', 'views'], true)) {
            $sort = 'date';
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));

        $total = $this->articles->countByCategory((int) $category['id']);

        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $articles = $this->articles->findByCategory(
                (int) $category['id'],
                $sort,
                $page,
                self::PER_PAGE
            );

        $this->smarty->assign([
            'category'   => $category,
            'articles'   => $articles,
            'sort'       => $sort,
            'page'       => $page,
            'totalPages' => $totalPages,
        ]);

        $this->smarty->display(
            'category.tpl'
        );
    }
}