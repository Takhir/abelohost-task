<?php

declare(strict_types=1);

use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Database\Database;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Smarty\Smarty;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

$database = new Database($config);

$pdo = $database->getConnection();

$smarty = new Smarty();

$smarty->setTemplateDir($config['smarty']['template_dir']);

$smarty->setCompileDir($config['smarty']['compile_dir']);

$smarty->setCacheDir($config['smarty']['cache_dir']);

$smarty->assign('appName', $config['app']['name']);

$articleRepository = new ArticleRepository($pdo);

$categoryRepository = new CategoryRepository($pdo);

$homeController = new HomeController($categoryRepository, $articleRepository, $smarty);

$categoryController = new CategoryController($categoryRepository, $articleRepository, $smarty);

$articleController = new ArticleController($articleRepository, $smarty);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$path = trim($path, '/');

if ($path === '') {
    $homeController->index();
    exit;
}

$segments = explode('/', $path);

switch ($segments[0]) {
    case 'category':
        if (!isset($segments[1])) {
            http_response_code(404);
            $smarty->display('404.tpl');
            exit;
        }
        $categoryController->show(
            $segments[1]
        );
        break;
    case 'article':
        if (!isset($segments[1])) {
            http_response_code(404);
            $smarty->display('404.tpl');
            exit;
        }
        $articleController->show(
            $segments[1]
        );
        break;
    default:
        http_response_code(404);
        $smarty->display('404.tpl');
        break;
}