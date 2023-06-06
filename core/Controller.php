<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

}
