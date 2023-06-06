<?php

namespace app\core;

use AllowDynamicProperties;

#[AllowDynamicProperties] class View
{
    protected string $layout = 'main';

    public function render($view, $data = [], $layout = 'main')
    {
        $this->layout = $layout;
        extract($data);
        ob_start();
        include_once "../views/$view.php";
        $content = ob_get_clean();

        ob_start();
        include_once "../views/layouts/$this->layout.php";
        $layoutContent = ob_get_clean();

        $layoutContent = str_replace('{{content}}', $content, $layoutContent);

        echo $layoutContent;
    }
}

