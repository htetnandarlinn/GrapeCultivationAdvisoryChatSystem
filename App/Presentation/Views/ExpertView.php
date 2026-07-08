<?php

namespace App\Presentation\Views;

class ExpertView
{
    public static function render(string $view, array $data = []): void
    {
        // Base path
        $basePath = dirname(__DIR__, 3);

        // View file path
        $viewFile = $basePath . "/App/Presentation/Views/{$view}.php";

        // Layout path
        $layoutFile = $basePath . "/App/Presentation/Views/layouts/expertApp.php";

        // Check if view exists
        if (!file_exists($viewFile)) {
            die(
                "<strong>View not found!</strong><br>" .
                "Looking for:<br>" .
                htmlspecialchars($viewFile)
            );
        }

        // Make variables available in view
        extract($data);

        // Capture view output
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // If layout exists, inject content
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
}