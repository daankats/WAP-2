<?php

namespace app\core;

class View
{
    protected static string $defaultLayout = 'main';

    public static function render($view, $data = [], $useLayout = true): false|string
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (file_exists($viewFile)) {
            // Extract the data array into variables
            extract($data);

            // Start output buffering
            ob_start();

            // Include the view file
            require_once $viewFile;

            // Get the rendered view content
            $content = ob_get_clean();

            // Return the rendered view with or without the layout
            if ($useLayout) {
                return self::renderLayout(static::$defaultLayout, ['content' => $content]);
            } else {
                return $content;
            }
        }

        // Return an empty string if the view file does not exist
        return '';
    }

    public static function renderLayout($layout, $data = []): false|string
    {
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';

        if (file_exists($layoutFile)) {
            // Extract the data array into variables
            extract($data);

            // Start output buffering
            ob_start();

            // Include the layout file
            require_once $layoutFile;

            // Get the rendered layout content
            $content = ob_get_clean();

            // Return the rendered layout
            return $content;
        }

        // Return an empty string if the layout file does not exist
        return '';
    }

    public static function setDefaultLayout($layout)
    {
        static::$defaultLayout = $layout;
    }
}
