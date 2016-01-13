<?php
require_once 'core/init.php';

$user = new User();



if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
} else {
    $data = $user->data();
    
    if (Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'name' =>array(
                            'name' => "Kapcsolat neve",
                            'required' => true,
                            'min' => 3
                        ),
                        'accountcode' =>array(
                            'name' => "Számlaszám",
                            'required' => true,
                            'min' => 4
                        ),
                    ));
                    if ($validation->passed()) {
                        $contactlistid = $user->getContactListID($data->ID);
                            $user->addContact(array(
                            'Contacts_ID' => $contactlistid->ID,
                            'Acc_Code' => Input::get('accountcode'),
                            'Name' => Input::get('name'),
                            'Is_Active' => 1
                        ));
                            echo 'Kapcsolat sikeresen hozzáadva !';
                    } else {
                        foreach ($validation->errors()as $error) {
                            echo $error, '<br>';
                        }
                    }
                     if(Input::get('idfordelete') > 0){
                        $user->delete(Input::get('idfordelete'));
                    }
                }
                
        }
    ?>
    <div class="transcationForm">
        <div class="row">
            <div class="col-xs-12">
                <table class="table">
                <thead class="table-invers">
                    <tr>
                        <th>#</th>
                        <th>Név</th>
                        <th>Számlaszám</th>
                        <th>Akció</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $contacts = $user->getContacts($data->ID);
                        
                        if (count($contacts) > 0) {
                            for ($i = 0; $i < count($contacts); $i++) {
                                echo '<tr id="con'. $contacts[$i][0] .'">
                                <th scope="row">' . ($i + 1) . '</th>
                                <td>' . $contacts[$i][3] . ' </td>
                                <td>' . $contacts[$i][2] . '</td>
                                <td>' . '<a class="pointer trash" data-id="' . $contacts[$i][0] .'"><i class="fa fa-trash"></i></a> </td>
                                </tr>';
                            }
                        }
                        ?>
                </tbody>
            </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <form action="">
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-warning btn-block small-btn" data-toggle="modal" data-target="#myModal">Hozzá add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Kapcsolat hozzáadása</h4>
                </div>
                <form action='' method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field form-group">
                                <label for="name">Kapcsolat neve:</label>
                                <input type='text' class="form-control" name="name" id="name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field form-group-single">
                                <label for="accountcode">Számlaszám:</label>
                                <input type="text" class="form-control" name="accountcode" id="accountcode" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input id="token" type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <button type="submit" class="btn btn-warning" id='btnSave'>Mentés</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Bezár</button>
                </div>
                </form>

            </div>
        </div>
    </div>
</div>
    <?php
}
?>

<script type="text/javascript">
   $(function(){
    $(document).on('click','.trash',function(){
        var idfordelete = $(this).attr('data-id');
        $.ajax({
            type:'POST',
            url:'newcontact.php',
            data:{'idfordelete': idfordelete, token: jQuery('#token').val() },
            success: function(data){
                jQuery('#con' + idfordelete).remove();
                window.location = window.location.href;
             }
            });
        });
});
</script>