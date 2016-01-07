<?php
echo '<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="includes/resources/css/bootstrap.min.css" rel="stylesheet">
  <link href="includes/resources/css/main.css" rel="stylesheet">
  <link href="includes/resources/css/font-awesome.min.css" rel="stylesheet" >

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="includes/resources/js/bootstrap.min.js"></script>
</head>
<body>

</body>';
session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'MYSQL5013.myASP.NET',
        'username' => '9e3f27_obank',
        'password' => 'kodocska',
        'db' => 'db_9e3f27_obank'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);
spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('Hash', '=', $hash));

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->User_ID);
        $user->login();
    }
}