<?php
require_once 'core/init.php';

if(Input::exists()){
    if(Token::check(Input::get('token'))){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'name' => 'Felhasználó név',
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'Users'
            ),
            'password' => array(
                'name' => 'Jelszó',
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'name' => 'Jelszó újra',
                'required' => true,
                'matches' => 'password'
            ),
            'firstname' => array(
                'name' => 'Keresztnév',
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'lastname' => array(
                'name' => 'Vezetéknév',
                'required' => true,
                'min' => 2,
                'max' => 50,
            ),
            'email' => array(
                'name' => 'Email cím',
                'required' => true,
                'min' => 2,
                'max' => 50,
                'unique' => 'Users'
            ),
        ));

        if($validation->passed()){
            $user = new User();
            
            $salt = Hash::salt(32);
            try{
                $user->create(array(
                    'UserName' => Input::get('username'),
                    'Password' => Hash::make(Input::get('password'), $salt),
                    'Salt' => $salt,
                    'FirstName' => Input::get('firstname'),
                    'LastName' => Input::get('lastname'),
                    'Email' => Input::get('email'),
                    'Category_ID' => 2
                ));
                
                Session::flash('home','Sikeresen regisztrált, bejelentkezhet!');
                header('Location: index.php');
                
            } catch (Exception $ex) {
                die($ex->getMessage());
            }
        }
        else{
            print_r($validation->errors());
        }
    }
}
?>

<form actio="" method="post">
    <div clas="field">
        <label for="username">Felhasználó név</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
    </div>
    
    <div class="field">
        <label for="password">Jelszó</label>
        <input type="password" name="password" id="password">
    </div>
    
    <div class="field">
        <label for="password_again">Jelszó újra</label>
        <input type="password" name="password_again" id="password_again">
    </div>
    
    <div class="field">
        <label for="lastname">Vezetéknév</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo escape(Input::get('lastname')); ?>">
    </div>
    
    <div class="field">
        <label for="firstname">Keresztnév</label>
        <input type="text" name="firstname" id="firstname" value="<?php echo escape(Input::get('firstname')); ?>">
    </div>
    
    <div class="field">
        <label for="email">Email cím</label>
        <input type="text" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>">
    </div>
    
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Register">
</form>
