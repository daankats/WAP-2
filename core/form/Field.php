<?php

namespace app\core\form;

use app\core\Model;
use app\core\form\BaseField;


/**
 * Summary of Field
 */
class Field extends BaseField{

    public string $type;
    /**
     * Summary of model
     * @var Model
     */
    public Model $model;
    /**
     * Summary of attribute
     * @var string
     */
    public string $attribute;

    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    /**
     * Summary of __construct
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute) {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }
    /**
     * @return string
     */
    public function getInputHtml(): string {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control %s">',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? 'is-invalid' : ''
        );
    }
    /**
     * @return Field
     */
    public function passwordField(): Field {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    /**
     * @return Field
     */
    public function numberField(): Field {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }
}