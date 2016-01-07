<?php
require_once 'core/init.php';

$user = new User();

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'ddlMyAccounts' =>array(
                'name' => "Saját számla",
                'required' => true
            ),
            'ddlAccountTypes' =>array(
                'name' => "Tipus",
                'required' => true
            ),
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
            if(Input::get('amount') <= $user->getAccAmount(Input::get('ddlMyAccounts'))){
            $transactionid = $user->getTranByAccID(Input::get('ddlMyAccounts'));
            if(Input::get('ddlAccountTypes') == "accountid"){
            $toaccid = $user->getAccByCode(Input::get('accountid'));
            $totranid = $user->getTranByAccID($toaccid);
            }
            else if(Input::get('ddlAccountTypes') == "contact"){
                $toaccid = $user->getAccByUserID(Input::get('ddlContacts'));
                $totranid = $user->getTranByAccID(Input::get('ddlContacts'));
            }
            $senderaccid = $user->getAccByUserID(Input::get('ddlMyAccounts'));
            if($toaccid != 0){
                $user->send(array(
                    'Transaction_ID' => $transactionid->ID,
                    'Account_ID' => $toaccid->ID,
                    'When' => date(DATE_ATOM),
                    'Amount' => Input::get('amount'),
                    'IsIncome' => 0,
                    'Description' => Input::get('description')
                ));
                $user->send(array(
                    'Transaction_ID' => $totranid->ID,
                    'Account_ID' => $senderaccid->ID,
                    'When' => date(DATE_ATOM),
                    'Amount' => Input::get('amount'),
                    'IsIncome' => 1,
                    'Description' => Input::get('description')
                ));
                Session::flash('home', 'Tranzakció sikeresen végrehajtva !');
                Redirect::to('index.php');
            }else{
                echo 'Tranzakció sikertelen! Ellenőrizze a megadott információkat!';
            }
            }
            else{
                echo 'Nincs elég pénz a számláján!!!';
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
                                <label for="ddlMyAccounts">Saját számla:</label>
                                <select id="ddlMyAccounts" name="ddlMyAccounts" class="form-control">
                                    <option <?php echo Input::get('ddlMyAccounts') == "" ? 'selected' : ''; ?> value="">-- Vállaszon --</option>
                                    <?php
                                    $accounts = $user->getAccounts($user->data()->ID);
                                    for($i = 0; $i < count($accounts);$i++){
                                        $selected = Input::get('ddlMyAccounts') == $accounts[$i][0] ? 'selected' : '';
                                        echo "<option ". $selected . " value=".$accounts[$i][0].">".$accounts[$i][1]."</option>";
                                    }
                                     ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field form-group">
                                <label for="ddlAccountTypes">Tipus:</label>
                                <select id="ddlAccountTypes" name="ddlAccountTypes" class="form-control">
                                    <option <?php echo Input::get('ddlAccountTypes') == "" ? 'selected' : ''; ?> value="">-- Vállaszon --</option>
                                    <option <?php echo Input::get('ddlAccountTypes') == "accountid" ? 'selected' : ''; ?> value="accountid">Számla</option>
                                    <option <?php echo Input::get('ddlAccountTypes') == "contact" ? 'selected' : ''; ?> value="contact">Kapcsolat</option>
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
                                    <select id='ddlContacts' name="ddlContacts" class="form-control"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field form-group">
                                <label for="description">Leírás:</label>
                                <textarea class="form-control noresize" rows="3" name="description" id="description"></textarea>
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
        jQuery('textarea#description').val('<?php echo escape(Input::get('description')); ?>');
        var lAccountType = jQuery('#ddlAccountTypes').val();
            if(lAccountType === ''){
                jQuery('#accountDiv').hide();
                jQuery('#contactDiv').hide();
            }else if(lAccountType === 'accountid'){
                jQuery('#accountDiv').show();
                jQuery('#contactDiv').hide();
            }else if(lAccountType === 'contact'){
                jQuery('#accountDiv').hide();
                jQuery('#contactDiv').show();
            }
        jQuery('#ddlAccountTypes').on('change', function(){
            var lAccountType = jQuery('#ddlAccountTypes').val();
            if(lAccountType === ''){
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