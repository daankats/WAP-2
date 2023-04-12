<?php 

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\core\middlewares\CourseMiddleware;
use app\models\CourseModel;
use app\models\EnrollmentModel;
use app\models\User;
use app\core\Request;
use app\core\Response;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new CourseMiddleware(['create', 'edit', 'delete']));
    }

    public function index()
    {
        $courses = CourseModel::findAllObjects();
        $enrollments = EnrollmentModel::findAllObjects();
        $users = User::findAllObjects();
        $user = User::findOne(['id' => App::$app->user->id]);
        $enrolled = false;
        foreach ($enrollments as $enrollment) {
            if ($enrollment->user_id == $user->id) {
                $enrolled = true;
            }
        }
        $this->layout = 'main';
        return $this->render('/courses/index', [
            'courses' => $courses,
            'enrollments' => $enrollments,
            'users' => $users,
            'user' => $user,
            'enrolled' => $enrolled,
            'app' => App::$app,
        ]);
    }

    public function create()
    {
        $course = new CourseModel();
        $user = User::findOne(['id' => App::$app->user->id]);
        if (App::isDocent() || App::isAdmin()) {
            // display the page if the user is a docent or admin
            if ($course->loadData($_POST)) {
                // Set the created_by and created_at properties
                $course->created_by = $user->id;
                $course->created_at = date('Y-m-d H:i:s');
                if ($course->validate() && $course->save()) {
                    // Redirect to the course index page upon successful creation
                    header('Location: /courses');
                    exit;
                }
                return $this->render('/courses/create', [
                    'model' => $course,
                ]);
            }
            return $this->render('/courses/create', [
                'model' => $course,
            ]);
        } elseif (App::isStudent()) {
            // redirect students to the index page if they try to access the create page
            header('Location: /courses');
            exit;
        } else {
            // display the forbidden page for guests
            return $this->render('/forbidden');
        }
    }
    
    public function update(Request $request, Response $response)
    {
        $id = $_GET['id'];
        $course = CourseModel::findOne(['id' => $id]);
    
        if ($course !== null && $request->isPost()) {
            $course->loadData($_POST);
            if ($course->validate() && $course->update()) {
                $response->redirect('/courses');
                return;
            } else {
                $exception = new \Exception("Failed to update the course.");
                return $this->render('/_error', [], $exception);
            }
        }
        return $this->render('/courses/edit', [
            'model' => $course,
        ]);
    }
    
    

    public function delete(Request $request, Response $response)
    {
        $course = CourseModel::findOne(['id' => $request->getBody()['id']]);
        if (App::isDocent() || App::isAdmin()) {
            if ($course->delete()) {
                // Redirect to the course index page upon successful deletion
                header('Location: /courses');
                exit;
            }
        }
        return $this->render('/courses/delete', [
            'model' => $course,
        ]);
    }
    
    public function enroll(Request $request, Response $response)
{
    $courseId = $request->getBody()['course_id'];
    $enrollment = new EnrollmentModel();
    $enrollment->student_id = App::$app->user->id;
    $enrollment->course_id = $courseId;

    if ($enrollment->save()) {
        // Redirect to the course index page upon successful enrollment
        $response->redirect('/courses');
    } else {
        // Handle enrollment failure
        $response->setStatusCode(500);
    }
}

}