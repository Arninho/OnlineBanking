<?php
require_once 'core/init.php';

if(Input::exists()){
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'username' => array(
            'name' => 'Felhasználó név',
            'required' => true,
            'min' => 2,
            'max' => 20,
            'unique' => 'users'
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
        'name' => array(
            'name' => 'Név',
            'required' => true,
            'min' => 2,
            'max' => 50
        ),
    ));
    
    if($validation->passed()){
        echo 'passed';
    }
    else{
        print_r($validation->errors());
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
        <label for="name">Név</label>
        <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>">
    </div>
    
    <input type="submit" value="Register">
</form>
