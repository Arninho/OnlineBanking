<?php ?>
<div class="menu">
    <?php
$user = new User();
if ($user->isLoggedIn()) {  
    ?>
<div class="welcome">
    <p class="welcome"><i class="fa fa-flag"></i>Hello <a href="#"><?php echo escape($user->data()->UserName) ?></a>!</p>
</div>
        <div class="nav">
            <ul>
                <li>
                    <a href="index.php?menu=0"><i class="fa fa-user"></i>Otthon</a>
                </li>
                <li>
                    <a href="index.php?menu=1"><i class="fa fa-user"></i>Névmódosítás</a>
                </li>
                <li>
                    <a href="index.php?menu=2"><i class="fa fa-user"></i>Jelszómódosítás</a>
                </li>

                <li>
                    <a href="index.php?menu=3"><i class="fa fa-user"></i>Profil</a>
                </li>

                <li>
                    <a href="index.php?menu=4"><i class="fa fa-paper-plane"></i>Tranzakciok</a>
                </li>
            </ul>
        </div>  
    <div class="logOut">
        <i class="fa fa-sign-out"></i><a href="logout.php">Kijelentkezés</a>
    </div>
</div>
<?php } ?>
<div class="content">
    <?php
    if (isset($_GET['menu'])){
        switch ($_GET['menu']) {
            case 1: include("update.php");
                break;
            case 2: include("changepassword.php");
                break;
            case 3: include("profile.php");
                break;
            case 4: include("transaction.php");
                break;
            default: include("profile.php");
                break;
        }
    }else{
        include("profile.php");
    }
        
    ?>
</div>