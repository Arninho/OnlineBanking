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
      <th></th>
      <th>Irány</th>
      <th>Ügyfél</th>
      <th>Összeg</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td> <?php echo $data->UserName; ?></td>
      <td>Otto</td>
      <td> <?php echo $user->getTansactions($trans); ?></td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td> <?php echo escape($data->UserName); ?></td>
      <td>Thornton</td>
      <td>@fat</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td> <?php echo escape($data->UserName); ?></td>
      <td>the Bird</td>
      <td>@twitter</td>
      <td>@mdo</td>
    </tr>
  </tbody>
</table>
    </div>
    <?php
}