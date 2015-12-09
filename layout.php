<?php
?>
<body>
<div class="cointainer-fluid">
    <div class="row">
        <div class="col-xs-12 text-center site-head"> 
        <h1><i class="fa fa-university"> zaBank - Mivel online minden gyorsabb!</i></h1>
        </div>
    </div>
</div>
 <?php
 $user=new User();
 if($user->isLoggedIn()){
     include 'menu.php';   
 }
 ?>
</body>