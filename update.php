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
            )
        ));
        if ($validation->passed()) {
            try {
                $user->update(array(
                    'FirstName' => Input::get('firstname')
                ));
                Session::flash('home', 'Név megváltoztatval !');
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
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'lastname' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        ));
        if ($validation->passed()) {
            try {
                $user->update(array(
                    'LastName' => Input::get('lastname')
                ));
                Session::flash('home', 'Név megváltoztatval !');
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

<form action="" method="post">
    <div class="field">
        <div>
            <label for="name">Keresztnév</label>
            <input type="text" name="firstname" value="<?php echo escape($user->data()->FirstName); ?>">
        </div>
        
        <div>
            <label for="name">Vezetéknév</label>
            <input type="text" name="lastname" value="<?php echo escape($user->data()->LastName); ?>">
        </div>
        
        <input type="submit" value="Update">
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    </div>
</form>