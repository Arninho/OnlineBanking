<?php
require_once 'core/init.php';

if (Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User();
if ($user->isLoggedIn()) {
    include 'header.php';
    include 'menu.php';
    ?>
    <p>Hello <a href="#"><?php echo escape($user->data()->UserName) ?></a>!</p>

    <ul>
        <li><a href="logout.php">Kijelentkezés</a></li>
    </ul>

    <?php
} else {
    include 'header.php';
    echo '<p><a href="login.php">Bejelentkezés</a> vagy <a href="register.php">regisztrálás</a></p>';
}