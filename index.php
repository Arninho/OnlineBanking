<?php
require_once 'core/init.php';
echo '<title>Kezdőlap</title>';

if (Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}
include 'header.php';
$user = new User();
if ($user->isLoggedIn()) {    
    include 'menu.php';
} else {
    ?>
<div class="index-welcome">
    <div class ="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-lg offset-4 col-sm-6 col-sm-offset-3 col-xs-12 text-center">
                <h3>Isten hozta a zaBank oldalán!</h3>
            <p><a href="login.php">Bejelentkezés</a> vagy <a href="register.php">regisztrálás</a></p>
            </div>
        </div>
    </div>
</div>
<?php
}