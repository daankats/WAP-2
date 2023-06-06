<?php 

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\core\middlewares\AdminMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\UserModel;

class AdminController extends Controller
{   
    public function __construct()
    {
        $this->setLayout('main');
        $adminMiddleware = new AdminMiddleware(['index']);
        $adminMiddleware->execute();
    }


    public function index() {
        $users = UserModel::findAllObjects();
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
        $user = UserModel::findOne(['id' => $id]);
    
        if (!$user) {
            $response->redirect('/admin');
        }
    
        if ($request->method() === 'post') {
            $data = $request->getBody();
            $user->loadData($data);
    
            if ($user->save()) {
                App::$app->session->setFlash('success', 'UserModel updated successfully');
                $response->redirect('/admin');
            }
        }
    
        $this->setLayout('main');
        return $this->render('/admin/edit', [
            'user' => $user
        ]);
    }
    
    public function delete(Request $request, Response $response)
    {
        $id = $request->getBody()['id'] ?? null;
        if (!$id) {
            throw new \Exception("ID is required");
        }
        UserModel::delete($id);
        $response->redirect('/admin');
    }
}
