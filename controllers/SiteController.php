<?php

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\core\Request;
use app\models\ContactModel;


class SiteController extends Controller
{
    public function home()
    {
        $params = [
            'name' => "John Doe"
        ];
        return $this->render('home', $params);
    }

    public function contact(Request $request)
    {
        $form = new ContactModel();
        if ($request->isPost()) {
            $form->loadData($request->getBody());
            if ($form->validate() && $form->send()) {
                App::$app->session->setFlash('success', 'Thanks for contacting us.');
                App::$app->response->redirect('/contact');
                exit;
            }
        }
        return $this->render('contact', [
            'model' => $form
        ]);
    }

    public function handleContact(Request $request)
    {
        $body = $request->getBody();
        return 'Handling submitted data';
    }

    
}
 

