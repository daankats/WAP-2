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
        parent::__construct();
        $adminMiddleware = new AdminMiddleware(['index', 'edit', 'delete']);
    }


    public function index()
    {
        $users = UserModel::findAllObjects();
        $this->view->title = 'Admin';
        $this->view->render('admin/index', [
            'users' => $users
        ], 'admin');
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

        if ($request->isPost()) {
            $data = $request->getBody();
            $user->loadData($data);

            if ($user->save()) {
                App::$app->session->setFlash('success', 'UserModel updated successfully');
                $response->redirect('/admin');
            }
        }
        $this->view->title = 'Admin';
        $this->view->render('/admin/edit', [
            'user' => $user
        ], 'admin');
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
