<?php
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
} else {
    $data = $user->data();
    ?>
    <div class="transcationForm">
        <div class="row">
            <div class="col-xs-12">
                <table class="col-xs-12">
                    <tr>
                        <td>
                            Név
                        </td> 
                        <td>
                            Számlaszám
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field form-group">
                                <label for="description">Kapcsolat neve:</label>
                                <input type='text' class="form-control" name="name" id="name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field form-group-single">
                                <label for="amount">Számlaszám:</label>
                                <input type="text" class="form-control" name="accountcode" id="accountcode" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                            <button type="submit" class="btn btn-warning btn-block small-btn">Küldés</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>