<?php
require_once 'core/init.php';

$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'firstname' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'lastname' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'password_current' => array(
                'required' => true,
                'min' => 6
            ),
            'password_new' => array(
                'required' => true,
                'min' => 6
            ),
            'password_new_again' => array(
                'required' => true,
                'min' => 6,
                'matches' => 'password_new'
            ),
        ));
        if ($validation->passed()) {
            try {
                $user->update(array(
                    'FirstName' => Input::get('firstname')
                ));
                Session::flash('home', 'Név megváltoztatval !');

                $user->update(array(
                    'LastName' => Input::get('lastname')
                ));
                Session::flash('home', 'Név megváltoztatval !');
                if (Hash::make(Input::get('password_current'), $user->data()->Salt) !== $user->data()->Password) {
                    
                } else {
                    $salt = Hash::salt(32);
                    $user->update(array(
                        'Password' => Hash::make(Input::get('password_new'), $salt),
                        'Salt' => $salt
                    ));
                    Session::flash('home', 'Jelszó megváltóztatva !');
                }
                Redirect::to('index.php');
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validation->errors()as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>
<div class='updateform'>
    <div class="row">
        <div class="col-xs-12">
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="field form-group">
                            <div>
                                <label for="name">Keresztnév:</label>
                                <input type="text" class="form-control" name="firstname" value="<?php echo escape($user->data()->FirstName); ?>">
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="field form-group">
                            <div>
                                <label for="name">Vezetéknév:</label>
                                <input type="text" class="form-control" name="lastname" value="<?php echo escape($user->data()->LastName); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="field form-group">
                            <label for="password_current">Jelenlegi jelszó:</label>
                            <input type="password" class="form-control" name="password_current" id="password_current">

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="field form-group">
                            <label for="password_new">Új jelszó:</label>
                            <input type="password" class="form-control" name="password_new" id="password_new">

                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="field form-group">
                            <label for="password_new_again">Új jelszó mégegyszer:</label>
                            <input type="password" class="form-control" name="password_new_again" id="password_new_again">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-warning btn-block small-btn" >Küldés</button>
                        <input type="hidden"  name="token" value="<?php echo Token::generate(); ?>">

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>