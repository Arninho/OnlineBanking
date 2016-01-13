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
        <h1>Felhasználónév : <?php echo escape($data->UserName); ?></h1>
        <h3> Teljes Név : <?php echo escape($data->FirstName) . ' ' . escape($data->LastName); ?></h3>
       <table class="table">
  <thead class="table-invers">
    <tr>
      <th>#</th>
      <th>Név</th>
      <th>Irány</th>
      <th>Ügyfél</th>
      <th>Összeg</th>
    </tr>
  </thead>
  <tbody>
      <?php
      $accid = $user->getAccByUserID($user->data()->ID);
      $tranid = $user->getTranByAccID($accid->ID);
        $transactions = $user->getTransactions($tranid->ID);
        $ugyfel=$user->getUserByAccID($transactions->Account_ID);
        for($i = 0; $i < count($transactions);$i++){
            echo '<tr>
                    <th scope="row">'.($i+1).'</th>
                    <td>'. $data->UserName .'</td>
                    <td>'. ($transactions[$i][5] == 0 ? '<i class="fa fa-arrow-right"></i>' : '<i class="fa fa-arrow-left"></i>') .' </td>
                    <td>'. $ugyfel[$i][1] .'</td>
                    <td>'. $transactions[$i][4]. '</td>
                  </tr>';
        }
    ?>
  </tbody>
</table>
    </div>
    <?php
}