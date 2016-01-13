<?php
require_once 'core/init.php';
$username = Input::get('username');
if (!isset($username)) {
    Redirect::to('index.php');
} else {
    $user = new User($username);
    if (!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
        
    }
    ?>
    <div class="profilform">
        <h2><?php echo escape($data->FirstName) . ' ' . escape($data->LastName); ?></h3>
        <h4>Egyenleg: <?php echo escape($user->getAccAmount($user->getAccByUserID($data->ID)->ID)->Amount) . ' RON'; ?></h5>
        <table class="table">
                <thead class="table-invers">
                    <tr>
                        <th>#</th>
                        <th>Irány</th>
                        <th>Ügyfél</th>
                        <th>Összeg</th>
                        <th>Leírás</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $accid = $user->getAccByUserID($user->data()->ID);
                    $tranid = $user->getTranByAccID($accid->ID);
                    $transactions = $user->getTransactions($tranid->ID);
                if(count($transactions) > 0){
                    for ($i = 0; $i < count($transactions); $i++) {
                        $lugyfel = $user->getUserByAccID($transactions[$i][2]);
                        $ugyfel = $user->getUserByID($lugyfel->User_ID);
                        echo '<tr>
                    <th scope="row">' . ($i + 1) . '</th>
                    <td>' . ($transactions[$i][5] == 0 ? '<i class="fa fa-arrow-right"></i>' : '<i class="fa fa-arrow-left"></i>') . ' </td>
                    <td>' . $ugyfel->LastName . ' ' . $ugyfel->FirstName . '</td>
                    <td>' . $transactions[$i][4] . ' RON</td>
                    <td>' . $transactions[$i][6] . '</td>
                  </tr>';
                    }
                }
                    ?>
                </tbody>
            </table>
    </div>
    <?php
}