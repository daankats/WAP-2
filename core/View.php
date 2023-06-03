<?php

namespace app\core;

class View
{
    public string $title = '';

    public function renderView($view, $params = [])
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $TemplateContent = $this->TemplateContent();
        return str_replace('{{content}}', $viewContent, $TemplateContent);
    }

    protected function TemplateContent()
    {
        $template = $this->getTemplate();
        ob_start();
        include_once App::$ROOT_DIR . "/views/templates/$template.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once App::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }

    protected function getTemplate()
    {
        $template = App::$app->template;
        if (App::$app->controller) {
            $template = App::$app->controller->template;
        }
        return $template;
    }
}
