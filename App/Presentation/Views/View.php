<?php

namespace App\Presentation\Views;

class View
{
    private static array $layoutMap = [
        'admin'  => 'dashboard',
        'farmer' => 'dashboard',
        'expert' => 'dashboard',
    ];

    public static function render(string $view, array $data = [], ?string $layout = null): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($data)) {
            extract($data);
        }

        $basePath = dirname(__DIR__, 3);

        $viewFile = $basePath . "/App/Presentation/Views/{$view}.php";

        if (!file_exists($viewFile)) {
            die(
                "<strong>View not found!</strong><br>" .
                "Looking for:<br>" .
                htmlspecialchars($viewFile)
            );
        }

        if ($layout === null) {
            $userRole = $_SESSION['user_role'] ?? '';
            $layout = self::$layoutMap[$userRole] ?? 'app';
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = $basePath . "/App/Presentation/Views/layouts/{$layout}.php";

        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
}
