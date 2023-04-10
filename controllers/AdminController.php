<?php 

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\core\middlewares\AdminMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\User;

class AdminController extends Controller
{   
    public function __construct()
    {
        $adminMiddleware = new AdminMiddleware(['index']);
        $adminMiddleware->execute();
    }

    public function beforeAction($action)
    {
        $adminMiddleware = new AdminMiddleware(['index', 'edit', 'delete']);
        $adminMiddleware->execute();
    }

    public function index() {
        $users = User::findAllObjects();
        return $this->render('admin/index', [
            'users' => $users
        ]);
    }

    public function middlewares(): array
    {
        return [
            'admin' => [
                AdminMiddleware::class
            ]
        ];
    }

    public function edit(Request $request, Response $response)
    {
        $id = $request->get('id');
        $user = User::findOne(['id' => $id]);
    
        if (!$user) {
            $response->redirect('/admin');
        }
    
        if ($request->method() === 'post') {
            $data = $request->getBody();
            $user->loadData($data);
    
            if ($user->save()) {
                App::$app->session->setFlash('success', 'User updated successfully');
                $response->redirect('/admin');
            }
        }
    
        $this->setLayout('admin');
        return $this->render('admin/edit', [
            'user' => $user
        ]);
    }
    
    public function delete(Request $request, Response $response)
    {
        $id = $request->getBody()['id'] ?? null;
        if (!$id) {
            throw new \Exception("ID is required");
        }
        User::delete($id);
        $response->redirect('/admin');
    }
}
