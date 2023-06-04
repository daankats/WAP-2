<?php

namespace app\core;

class View
{
    public static function render($view, $data = [])
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

            // Return the rendered view
            return $content;
        }

        // Return an empty string if the view file does not exist
        return '';
    }
}
