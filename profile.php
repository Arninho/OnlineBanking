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
<div class="profilform">
        <h1>Felhasználónév : <?php echo escape($data->UserName); ?></h1>
        <h3> Teljes Név : <?php echo escape($data->FirstName) . ' ' . escape($data->LastName); ?></h3>
    </div>  
    <?php
}