<?php 

namespace app\core\form;

use app\core\Model;
use app\core\form\Field;
use app\core\form\TextareaField;


class Form {
    
        public static function begin($action, $method) {
            echo sprintf('<form action="%s" method="%s">', $action, $method);
            return new Form();
        }
    
        public static function end() {
            echo '</form>';
        }
    
        public function field(Model $model, $attribute) {
            return new Field($model, $attribute);
        }

        public function TextareaField(Model $model, $attribute) {
            return new TextareaField($model, $attribute);
        }
    }

?>