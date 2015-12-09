<?php ?>
<div id="menu">

    <ul>
        <li>
            <a href="index.php?menu=1">Névmódosítás</a>
        </li>
        <li>
            <a href="index.php?menu=2">Jelszómódosítás</a>
        </li>

        <li>
            <a href="index.php?menu=3">Profil</a>
        </li>

        <li>
            <a href="index.php?menu=4">Tranzakciok</a>
        </li>

    </ul>
</div>
<div id="content">

    <?php
    if (isset($_GET['menu']))
        switch ($_GET['menu']) {
            case 1: include("update.php");
                break;
            case 2: include("changepassword.php");
                break;
            case 3: include("profile.php");
                break;
            case 4: include("transactions.php");
                break;
        } else
        include("profile.php");
    ?>

</div>