<?php

namespace app\core;

class View
{
    protected string $layout = 'main';

    public function render($view, $data = [])
    {
        extract($data);
        ob_start();
        include_once "../views/$view.php";
        $content = ob_get_clean();

        // Toevoeging: Maak $content beschikbaar als parameter aan de layout
        ob_start();
        include_once "../views/layouts/$this->layout.php";
        $layoutContent = ob_get_clean();

        // Vervang de {{content}} tag met de daadwerkelijke inhoud van de view
        $layoutContent = str_replace('{{content}}', $content, $layoutContent);

        // Echo de layout met de inhoud
        echo $layoutContent;
    }

}
