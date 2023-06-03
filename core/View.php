<?php

namespace app\core;

class View
{
    public string $title = '';

<<<<<<< HEAD
<<<<<<< HEAD
    
    public function renderView($view, $params = []) {
=======
    public function renderView($view, $params = [])
    {
>>>>>>> parent of 8861080 (no message)
=======
    public function renderView($view, $params = [])
    {
>>>>>>> f3833bb6c855df2aac1677c84f01df6f994a58f6
        $viewContent = $this->renderOnlyView($view, $params);
        $TemplateContent = $this->TemplateContent();
        return str_replace('{{content}}', $viewContent, $TemplateContent);
    }
<<<<<<< HEAD
<<<<<<< HEAD
    
    public function renderContent($viewContent) {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', "<div class='col p-5'>$viewContent</div>", $layoutContent);
    }
    

    protected function layoutContent() {
        $layout = App::$app->layout;
        if (App::$app->controller) {
            $layout = App::$app->controller->layout;
        }
=======

    protected function TemplateContent()
    {
        $template = $this->getTemplate();
>>>>>>> parent of 8861080 (no message)
=======

    protected function TemplateContent()
    {
        $template = $this->getTemplate();
>>>>>>> f3833bb6c855df2aac1677c84f01df6f994a58f6
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
