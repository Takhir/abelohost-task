<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use Smarty\Smarty;

final class ArticleController
{
    public function __construct(
        private ArticleRepository $articles,
        private Smarty $smarty
    ) {
    }

    public function show(string $slug): void
    {
        $article = $this->articles->findBySlug($slug);

        if ($article === null) {
            http_response_code(404);
            $this->smarty->display(
                '404.tpl'
            );
            return;
        }

        $articleId = (int) $article['id'];

        $this->articles->incrementViews(
            $articleId
        );

        $article['views']++;

        $categories = $this->articles->findCategories($articleId);

        $similarArticles = $this->articles->findSimilar($articleId,3);

        $this->smarty->assign([
            'article'         => $article,
            'categories'      => $categories,
            'similarArticles' => $similarArticles,
        ]);

        $this->smarty->display(
            'article.tpl'
        );
    }
}