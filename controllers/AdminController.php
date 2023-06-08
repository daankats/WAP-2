<?php

namespace app\controllers;

use app\core\App;
use app\core\Auth;
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
        $this->registerMiddleware(new AdminMiddleware(['create', 'edit', 'update', 'delete']));

    }


    public function index()
    {
        $users = UserModel::findAllObjects();
        $this->view->title = 'Admin';
        $this->view->render('admin/index', [
            'users' => $users
        ], 'admin');
    }

    public function edit(Request $request, Response $response)
    {
        $id = $request->getQueryParams()['id'];
        $user = UserModel::findOne(['id' => $id]);

        if (!Auth::isAdmin()) {
            $response->redirect('/admin');
        }

        if ($user === null) {
            $exception = new \Exception("Course not found.");
            $this->view->render('/_error', [], $exception);
            return;
        }

        $this->view->title = 'Admin';
        $this->view->render('/admin/edit', [
            'model' => $user,
        ], 'auth');
    }

    /**
     * @throws \Exception
     */
    public function updateUser(Request $request, Response $response)
    {
        $id = $request->getQueryParams()['id'] ?? null;
        if ($id === null) {
            echo "id is null";
            return;
        }

        $user = UserModel::findOne(['id' => $id]);

        if ($user === null) {
            $exception = new \Exception("User not found.");
            $this->view->render('/_error', ['exception' => $exception]);
            return;
        }

        $user->scenario = 'update';
        $user->loadData($request->getBody());
        if ($user->validate()) {
            echo "Validation passed";
        } else {
            var_dump($user->errors);
        }

        if ($user->validate() && $user->update()) {
            $response->redirect('/admin');

        } else {
            $exception = new \Exception("Failed to update the user.");
            $this->view->render('/_error', ['exception' => $exception]);

        }
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
