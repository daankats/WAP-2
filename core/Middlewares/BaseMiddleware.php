<?php 

namespace app\core\Middlewares;

use app\core\Request;
use app\core\Response;

abstract class BaseMiddleware
{
    abstract public function handle(Request $request, Response $response, callable $next);
}