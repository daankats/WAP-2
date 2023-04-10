<?php

use app\core\App;
use app\models\User;

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->title  ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">WAP-2</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/contact">Contact</a>
        </li>
      </ul>

      <?php if (App::$app->isGuest()): ?>
        
      <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/login">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/register">register</a>
        </li>
      </ul>

      <?php else: ?>

        <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/profile">
            Profile
           </a>
        </li>
        <?php if (App::$app->isAdmin()): ?>
            <li class="nav-item">
                <a class="nav-link" href="/admin">Admin</a>
            </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/logout"><?php 
    $displayName = '';
    if (App::$app->user) {
        $displayName = App::$app->user->displayName();
        echo $displayName;
    }
?>

        (logout)
      </a>
        </li>
      </ul>

      <?php endif; ?>
    </div>
  </div>
</nav>

   <div class="container">
    <?php if (App::$app->session->getFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo App::$app->session->getFlash('success'); 
            endif ?>
            
        </div>
    {{content}}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  </body>
</html>