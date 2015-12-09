<?php
require_once 'core/init.php';
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if ($validation->passed()) {
            $user = new User();

            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if ($login) {
                echo Redirect::to('index.php');
            } else {
                echo '<p>Sikertelen</p> ';
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>
<head>
    <?php include 'header.php';?>
    <title>Bejelentkezés</title>
</head>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-lg offset-4 col-sm-6 col-sm-offset-3 col-xs-12 text-center">
            <form action="" method="post">
                <div class="field form-group">
                    <label for="username">Felhasználónév</label>
                    <input type="text" class="form-control" name="username" id="username" autocomplete="off">
                </div>

                <div class="field form-group">
                    <label for="password">Jelszó</label>
                    <input type="password" class="form-control" name="password" id="password" autocomplete="off">
                </div>

                <div class="field form-group">
                    <label for="remember">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Emlékezz rám</span>
                    </label>
                </div>
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" >
                <button type="submit" class="btn btn-warning btn-block">Bejelentkezés</button>
            </form>
        </div>
    </div>
</div>