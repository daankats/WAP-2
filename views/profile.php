<?php
/** @var $this app\core\View */
$this->title = 'Profile';
?>

<h1>profile</h1>

<form action="" method="post">
    <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" class="form-control">
    </div>
    <div class="form-group">
        <label for="body">Body</label>
        <textarea name="body" id="body" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>