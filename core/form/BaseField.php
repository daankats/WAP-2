<?php

namespace app\core\form;
use app\core\Model;

abstract class BaseField {

    public Model $model;
    public string $attribute;


    public function __construct(Model $model, string $attribute) {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract function getInputHtml(): string;

    public function __toString(): string {
        return sprintf('
            <div class="form-group">
                <label>%s</label>
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
        ',
            $this->getLabel(),
            $this->getInputHtml(),
            $this->getError()
        );
    }

    private function getLabel(): string {
        $labels = $this->model->labels();
        return isset($labels[$this->attribute]) ? $labels[$this->attribute] : '';
    }
    
    private function getError(): string {
        $error = $this->model->getFirstError($this->attribute);
        return $error ? '<span class="error-message">' . $error . '</span>' : '';
    }
    

}