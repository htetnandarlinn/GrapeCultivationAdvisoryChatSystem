<?php

namespace App\Presentation\Views;

class View
{
    public static function render(string $view, array $data = []): void
    {
        // Make data available in view safely
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }

        // Base project path
        $basePath = dirname(__DIR__, 3);

        // View & layout paths
        $viewFile = $basePath . "/App/Presentation/Views/{$view}.php";
        $layout   = $basePath . "/App/Presentation/Views/layouts/app.php";
        // $layout   = $basePath . "/App/Presentation/Views/layouts/dashboard.php";

        // Check view file
        if (!file_exists($viewFile)) {
            die(
                "<strong>View not found!</strong><br>" .
                "Looking for:<br>" .
                htmlspecialchars($viewFile)
            );
        }

        // Capture view output
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Load layout if exists
        if (file_exists($layout)) {
            require $layout;
        } else {
            // Fallback: render view without layout
            echo $content;
        }
    }
}