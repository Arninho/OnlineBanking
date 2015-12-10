<?php
require_once 'core/init.php';

if (Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}
include 'header.php';
$user = new User();
if ($user->isLoggedIn()) {    
    include 'menu.php';
} else {
    echo '<p><a href="login.php">Bejelentkezés</a> vagy <a href="register.php">regisztrálás</a></p>';
}