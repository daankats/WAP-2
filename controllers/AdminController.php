<?php

namespace app\controllers;

use app\core\App;
use app\core\Auth;
use app\core\Controller;
use app\core\Middlewares\AdminMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\UserModel;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->registerMiddleware(new AdminMiddleware());
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
            $exception = new \Exception("Gebruiker niet gevonden.");
            $this->view->render('/_error', [], $exception);
            return;
        }

        $this->view->title = 'Admin';
        $this->view->render('/admin/edit', [
            'model' => $user,
        ], 'auth');
    }


    public function updateUser(Request $request, Response $response)
    {
        $id = $request->getQueryParams()['id'] ?? null;
        if ($id === null) {
            echo "id is null";
            return;
        }

        $user = UserModel::findOne(['id' => $id]);

        if ($user === null) {
            $exception = new \Exception("Gebruiker niet gevonden.");
            $this->view->render('/_error', ['exception' => $exception]);
            return;
        }

        $user->scenario = 'update';
        $user->loadData($request->getBody());
        if ($user->validate()) {
            echo "Validatie geslaagd";
        } else {
            var_dump($user->errors);
        }

        if ($user->validate() && $user->update()) {
            App::$app->session->setFlash('success', 'Gebruiker succesvol bijgewerkt.');
            $response->redirect('/admin');
        } else {
            App::$app->session->setFlash('error', 'Kon de gebruiker niet bijwerken.');
            $exception = new \Exception("Kon de gebruiker niet bijwerken.");
            $this->view->render('/_error', ['exception' => $exception]);
        }
    }

    public function delete(Request $request, Response $response)
    {
        $id = $request->getBody()['id'] ?? null;
        if (!$id) {
            throw new \Exception("ID is verplicht");
        }

        $userModel = new UserModel();
        $user = $userModel->findOne(['id' => $id]);
        if ($user && $user->delete()) {
            App::$app->session->setFlash('success', 'Gebruiker succesvol verwijderd.');
        } else {
            App::$app->session->setFlash('error', 'Kon de gebruiker niet verwijderen.');
        }

        $response->redirect('/admin');
    }
}
