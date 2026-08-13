<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Smarty\Smarty;

final class HomeController
{
    public function __construct(
        private CategoryRepository $categories,
        private ArticleRepository $articles,
        private Smarty $smarty
    ) {
    }

    public function index(): void
    {
        $categories = $this->categories->findCategoriesWithArticles();

        foreach ($categories as &$category) {
            $category['articles'] = $this->articles->findLatestByCategory((int) $category['id'], 3);
        }

        unset($category);

        $this->smarty->assign([
            'categories' => $categories,
        ]);

        $this->smarty->display('home.tpl');
    }
}