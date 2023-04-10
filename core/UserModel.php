<?php 

namespace app\core;

use app\core\db\DbModel;

abstract class UserModel extends DbModel
{
    /**
     * Returns the display name of the user.
     * @return string
     */
    abstract public function displayName(): string;
}