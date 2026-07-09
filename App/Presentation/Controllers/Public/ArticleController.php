<?php

namespace App\Presentation\Controllers\Public;

use App\Domain\KnowledgeBase\Repositories\ArticleRepositoryInterface;
use App\Presentation\Views\View;

final class ArticleController
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
    ) {}

    public function index(): void
    {
        $articles = $this->articleRepository->findPublished();

        View::render('public/articles', [
            'articles' => $articles,
        ], 'app');
    }

    public function view(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $article = $this->articleRepository->findById($id);

        if ($article === null || $article->getStatus()->getValue() !== 'accepted') {
            http_response_code(404);
            echo 'Article not found.';
            return;
        }

        View::render('public/article-detail', [
            'article' => $article,
        ], 'app');
    }
}
