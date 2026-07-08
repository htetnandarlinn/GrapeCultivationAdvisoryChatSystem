<?php

namespace App\Presentation\Controllers\Expert;

use App\Domain\KnowledgeBase\Repositories\ArticleRepositoryInterface;
use App\Domain\KnowledgeBase\ValueObjects\ArticleStatus;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\View;

final class ArticleController
{
    use AuthorizesPermissions;

    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
    ) {}

    #[Permission('articles.view', 'View Articles')]
    public function index(): void
    {
        $this->authorize('articles.view');

        $authorId = (string) ($_SESSION['user']['id'] ?? 0);
        $articles = $this->articleRepository->findAll((int) $authorId);

        View::render('expert/manage-articles', [
            'activePage' => 'articles',
            'articles' => $articles,
        ]);
    }

    #[Permission('articles.create', 'Create Article')]
    public function create(): void
    {
        $this->authorize('articles.create');

        View::render('expert/article-form', [
            'activePage' => 'articles',
            'mode' => 'create',
            'formAction' => '/expert/articles/store',
            'submitLabel' => 'Publish Article',
            'article' => null,
        ]);
    }

    #[Permission('articles.create', 'Create Article')]
    public function store(): void
    {
        $this->authorize('articles.create');

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $authorId = (string) ($_SESSION['user']['id'] ?? 0);
        $status = trim($_POST['status'] ?? 'draft');

        if ($title === '' || $content === '') {
            $_SESSION['article_message'] = 'Title and content are required.';
            redirect('/expert/articles/create');
            return;
        }

        $imagePath = null;
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            if ($imagePath === null) {
                redirect('/expert/articles/create');
                return;
            }
        }

        $article = new \App\Domain\KnowledgeBase\Entities\Article(
            id: null,
            title: $title,
            content: $content,
            authorId: $authorId,
            image: $imagePath,
            status: ArticleStatus::fromValue($status),
        );

        $this->articleRepository->save($article);

        $_SESSION['article_message'] = 'Article created successfully.';
        redirect('/expert/articles');
    }

    #[Permission('articles.edit', 'Edit Article')]
    public function edit(): void
    {
        $this->authorize('articles.edit');

        $id = (int) ($_GET['id'] ?? 0);
        $article = $this->articleRepository->findById($id);

        if ($article === null) {
            $_SESSION['article_message'] = 'Article not found.';
            redirect('/expert/articles');
            return;
        }

        View::render('expert/article-form', [
            'activePage' => 'articles',
            'mode' => 'edit',
            'formAction' => '/expert/articles/update',
            'submitLabel' => 'Update Article',
            'article' => $article,
        ]);
    }

    #[Permission('articles.edit', 'Edit Article')]
    public function update(): void
    {
        $this->authorize('articles.edit');

        $id = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $status = trim($_POST['status'] ?? 'draft');

        $article = $this->articleRepository->findById($id);

        if ($article === null) {
            $_SESSION['article_message'] = 'Article not found.';
            redirect('/expert/articles');
            return;
        }

        if ($title === '' || $content === '') {
            $_SESSION['article_message'] = 'Title and content are required.';
            redirect('/expert/articles/edit?id=' . $id);
            return;
        }

        $article->setTitle($title);
        $article->setContent($content);
        $article->setStatus(ArticleStatus::fromValue($status));

        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->handleImageUpload($_FILES['image']);
            if ($imagePath === null) {
                redirect('/expert/articles/edit?id=' . $id);
                return;
            }
            $article->setImage($imagePath);
        }

        $this->articleRepository->save($article);

        $_SESSION['article_message'] = 'Article updated successfully.';
        redirect('/expert/articles');
    }

    #[Permission('articles.delete', 'Delete Article')]
    public function delete(): void
    {
        $this->authorize('articles.delete');

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['article_message'] = 'Invalid article.';
            redirect('/expert/articles');
            return;
        }

        $article = $this->articleRepository->findById($id);

        if ($article === null) {
            $_SESSION['article_message'] = 'Article not found.';
            redirect('/expert/articles');
            return;
        }

        $imagePath = $article->getImage();
        if ($imagePath) {
            $fullPath = dirname(__DIR__, 4) . '/public' . $imagePath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $this->articleRepository->delete($id);

        $_SESSION['article_message'] = 'Article deleted successfully.';
        redirect('/expert/articles');
    }

    private function handleImageUpload(array $file): ?string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($detectedType, $allowedTypes)) {
            $_SESSION['article_message'] = 'Invalid image type (detected: ' . $detectedType . '). Allowed: JPG, PNG, GIF, WebP.';
            return null;
        }

        if ($file['size'] > $maxSize) {
            $_SESSION['article_message'] = 'Image size exceeds 5MB limit.';
            return null;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('article_', true) . '.' . $extension;
        $uploadDir = dirname(__DIR__, 4) . '/public/uploads/articles/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!is_writable($uploadDir)) {
            $_SESSION['article_message'] = 'Upload directory is not writable.';
            return null;
        }

        $destPath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            $_SESSION['article_message'] = 'Failed to upload image. Check directory permissions.';
            return null;
        }

        return '/uploads/articles/' . $filename;
    }
}
