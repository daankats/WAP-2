<?php 

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\core\middlewares\EnrollmentMiddleware;
use app\models\CourseModel;
use app\models\EnrollmentModel;
use app\models\User;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new EnrollmentMiddleware(['enroll']));
    }

    public function enroll()
    {
        $enrollment = new EnrollmentModel();
        $user = User::findOne(['id' => App::$app->user->id]);
        if ($user->role == 'student') {
            if ($enrollment->loadData($_POST)) {
                if ($enrollment->validate() && $enrollment->save()) {
                    // Redirect to the course index page upon successful enrollment
                    header('Location: /courses');
                    exit;
                }
            }
        }
        // If something went wrong, or if the user is not a student, show an error message
        $this->layout = 'main';
        return $this->render('/enrollments/error', [
            'message' => 'There was an error with your enrollment request.'
        ]);
    }
}
