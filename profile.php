<?php
require_once 'core/init.php';
$username = Input::get('username');
if (!isset($username)) {
    Redirect::to('index.php');
} else {
    $user = new User($username);
    if (!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }
    ?>
    <h3><?php echo escape($data->UserName); ?></h3>
    <p> Full name:<?php echo escape($data->FirstName) . ' ' . escape($data->LastName); ?></p>
    <?php
}