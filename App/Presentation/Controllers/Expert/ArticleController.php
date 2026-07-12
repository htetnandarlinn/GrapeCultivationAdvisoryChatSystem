<?php

namespace App\Presentation\Controllers\Expert;

use App\Application\NotificationManagement\NotificationService;
use App\Domain\KnowledgeBase\Repositories\ArticleRepositoryInterface;
use App\Domain\KnowledgeBase\ValueObjects\ArticleStatus;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;

final class ArticleController
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private NotificationService $notificationService,
    ) {}

    #[Permission('articles.view', 'View Articles')]
    public function index(): void
    {
        

        $isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
        $authorId = (string) ($_SESSION['user']['id'] ?? 0);

        if ($isAdmin) {
            $articles = $this->articleRepository->findAll();
        } else {
            $articles = $this->articleRepository->findAll((int) $authorId);
        }

        View::render('expert/manage-articles', [
            'activePage' => 'articles',
            'articles' => $articles,
            'isAdmin' => $isAdmin,
        ]);
    }

    #[Permission('articles.create', 'Create Article')]
    public function create(): void
    {
        

        View::render('expert/article-form', [
            'activePage' => 'articles',
            'mode' => 'create',
            'formAction' => '/expert/articles/store',
            'submitLabel' => 'Submit Article',
            'article' => null,
        ]);
    }

    #[Permission('articles.create', 'Create Article')]
    public function store(): void
    {
        

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $authorId = (string) ($_SESSION['user']['id'] ?? 0);

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
        );

        $articleId = $this->articleRepository->save($article);

        $authorName = $_SESSION['user']['username'] ?? 'An expert';
        $this->notificationService->notifyAllAdmins(
            "$authorName submitted a new article: " . $title,
            'article_created',
            '/expert/articles/view?id=' . $articleId
        );

        $_SESSION['article_message'] = 'Article created successfully.';
        redirect('/expert/articles');
    }

    #[Permission('articles.edit', 'Edit Article')]
    public function edit(): void
    {
        

        $id = (int) ($_GET['id'] ?? 0);
        $currentUserId = (string) ($_SESSION['user']['id'] ?? 0);
        $isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
        $article = $this->articleRepository->findById($id);

        if ($article === null) {
            $_SESSION['article_message'] = 'Article not found.';
            redirect('/expert/articles');
            return;
        }

        if (!$isAdmin && $article->getAuthorId() !== $currentUserId) {
            $_SESSION['article_message'] = 'You can only edit your own articles.';
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
        

        $id = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $currentUserId = (string) ($_SESSION['user']['id'] ?? 0);
        $isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';

        $article = $this->articleRepository->findById($id);

        if ($article === null) {
            $_SESSION['article_message'] = 'Article not found.';
            redirect('/expert/articles');
            return;
        }

        if (!$isAdmin && $article->getAuthorId() !== $currentUserId) {
            $_SESSION['article_message'] = 'You can only edit your own articles.';
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


        $id = (int) ($_POST['id'] ?? 0);
        $currentUserId = (string) ($_SESSION['user']['id'] ?? 0);
        $isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';

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

        if (!$isAdmin && $article->getAuthorId() !== $currentUserId) {
            $_SESSION['article_message'] = 'You can only delete your own articles.';
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

    #[Permission('articles.view', 'View Article Detail')]
    public function view(): void
    {
        

        $id = (int) ($_GET['id'] ?? 0);
        $article = $this->articleRepository->findById($id);

        if ($article === null) {
            $_SESSION['article_message'] = 'Article not found.';
            redirect('/expert/articles');
            return;
        }

        View::render('admin/article-detail', [
            'activePage' => 'articles',
            'article' => $article,
        ]);
    }

    #[Permission('articles.edit', 'Accept Article')]
    public function accept(): void
    {
        

        $id = (int) ($_POST['id'] ?? 0);
        $article = $this->articleRepository->findById($id);

        if ($article === null) {
            $_SESSION['article_message'] = 'Article not found.';
            redirect('/expert/articles');
            return;
        }

        $article->setStatus(ArticleStatus::accepted());
        $this->articleRepository->save($article);

        $authorId = (int) $article->getAuthorId();
        if ($authorId) {
            $this->notificationService->notify(
                $authorId,
                'expert',
                'Your article "' . $article->getTitle() . '" has been accepted and published.',
                'article_accepted',
                '/expert/articles'
            );
        }

        $this->notificationService->notifyAllByRole(
            'farmer',
            'New article published: "' . $article->getTitle() . '"',
            'article_accepted',
            '/articles/view?id=' . $article->getId()
        );

        $_SESSION['article_message'] = 'Article accepted successfully.';
        redirect('/expert/articles');
    }

    #[Permission('articles.edit', 'Reject Article')]
    public function reject(): void
    {
        

        $id = (int) ($_POST['id'] ?? 0);
        $note = trim($_POST['rejection_note'] ?? '');
        $article = $this->articleRepository->findById($id);

        if ($article === null) {
            $_SESSION['article_message'] = 'Article not found.';
            redirect('/expert/articles');
            return;
        }

        $article->setStatus(ArticleStatus::rejected());
        $article->setRejectionNote($note !== '' ? $note : null);
        $this->articleRepository->save($article);

        $authorId = (int) $article->getAuthorId();
        if ($authorId) {
            $this->notificationService->notify(
                $authorId,
                'expert',
                'Your article "' . $article->getTitle() . '" was rejected.' . ($note ? ' Reason: ' . $note : ''),
                'article_rejected',
                '/expert/articles'
            );
        }

        $_SESSION['article_message'] = $note !== ''
            ? 'Article rejected. Reason: ' . $note
            : 'Article rejected.';

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
