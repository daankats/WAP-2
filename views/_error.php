<?php

/** @var $exception \Exception */

?>

<?php if ($exception !== null) : ?>

    <h1><?php echo $exception->getCode() ?> - <?php echo $exception->getMessage() ?></h1>

<?php else : ?>

    <h1>Unknown Error</h1>

<?php endif; ?>