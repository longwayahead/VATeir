<?php
require_once('../includes/header.php');
?>
<div class="col-md-6 col-md-offset-3">
  <h3 class="text-center">Teamspeak</h3>
  <?php
  if(!$user->isLoggedIn() && !isset($_SESSION['ts']) ) {
    ?>

    <p>VATeir uses Teamspeak 3 for voice communication between its members.</p>
    <p>Before being granted access, you must register your client ID with us through this website.</p>
    <p>Manage your access tokens using the link below.</p>
    <a href="<?php echo BASE_URL; ?>login/index.php?ts" class="btn btn-primary">Manage My Access Tokens</a>
  <?php
  } else {
    $cid = (isset($_SESSION['ts'])) ? $_SESSION['ts'] : $user->data()->id;
    echo $cid;
  ?>

  <?php
  }
  ?>
</div>
