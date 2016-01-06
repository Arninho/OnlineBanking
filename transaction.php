<?php
require_once 'core/init.php';

$user = new User();

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'amount' => array(
                'name' => 'Összeg',
                'required' => true,
                'min' => 1
            ),
            'description' => array(
                'name' => 'Leírás',
                'required' => true,
                'min' => 7
            ),
        ));
        if ($validation->passed()) {
            $transactionid = $user->getTranByUserID($user->data()->ID);
            $toaccid = $user->getAccByCode(Input::get('accountid'));
            $touserid = $user->getUserByAccID($toaccid);
            $totranid = $user->getTranByUserID($touserid);
            $senderaccid = $user->getAccByUserID($user->data()->ID);
            if($toaccid != 0){
                $user->send(array(
                    'Transaction_ID' => $transactionid,
                    'Account_ID' => $toaccid,
                    'When' => date(DATE_ATOM),
                    'Amount' => Input::get('amount'),
                    'IsIncome' => false,
                    'Description' => Input::get('description')
                ));
                $user->send(array(
                    'Transaction_ID' => $totranid,
                    'Account_ID' => $senderaccid,
                    'When' => date(DATE_ATOM),
                    'Amount' => Input::get('amount'),
                    'IsIncome' => true,
                    'Description' => Input::get('description')
                ));
                Session::flash('home', 'Tranzakció sikeresen végrehajtva !');
                Redirect::to('index.php');
            }else{
                echo 'Tranzakció sikertelen!';
            }
        } else {
            foreach ($validation->errors()as $error) {
                echo $error, '<br>';
            }
        }
    }
}

if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
} else {
        $data = $user->data();
    ?>
    <div class="transcationForm">
        <div class="row">
            <div class="col-xs-12">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="field form-group">
                                <label for="ddlMyAccounts">Honnat:</label>
                                <select id="ddlMyAccounts" class="form-control">
                                    <option value="none">-- Vállaszon --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field form-group">
                                <label for="ddlAccountTypes">Hova:</label>
                                <select id="ddlAccountTypes" class="form-control">
                                    <option value="none">-- Vállaszon --</option>
                                    <option value="accountid">Számla</option>
                                    <option value="contact">Kapcsolat</option>
                                </select>
                            </div>
                        </div>
                        <div id="accountDiv" style="display: none;">
                            <div class="col-md-6">
                                <div class="field form-group">
                                    <label for="accountid">Számlaszám:</label>
                                    <input type="text" class="form-control" name="accountid" id="accountid" value="<?php echo escape(Input::get('accountid')); ?>">
                                </div>
                            </div>
                        </div>
                        <div id="contactDiv" style="display: none;">
                            <div class="col-md-6">
                                <div class="field form-group">
                                    <label for="contact">Személy:</label>
                                    <select id='ddlContacts' class="form-control"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field form-group">
                                <label for="description">Leírás:</label>
                                <textarea class="form-control noresize" rows="3" name="description" id="description" value="<?php echo escape(Input::get('description')); ?>"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field form-group-single">
                                <label for="amount">Összeg:</label>
                                <input type="text" class="form-control" name="amount" id="amount" value="<?php echo escape(Input::get('amount')); ?>" autocomplete="off">
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
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#ddlAccountTypes').on('change', function(){
            var lAccountType = jQuery('#ddlAccountTypes').val();
            if(lAccountType === 'none'){
                jQuery('#accountDiv').hide();
                jQuery('#contactDiv').hide();
            }else if(lAccountType === 'accountid'){
                jQuery('#accountDiv').show();
                jQuery('#contactDiv').hide();
            }else if(lAccountType === 'contact'){
                jQuery('#accountDiv').hide();
                jQuery('#contactDiv').show();
            }
        });
    });
</script>
    <?php
}