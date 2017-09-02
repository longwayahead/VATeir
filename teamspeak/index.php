<?php
require_once('../includes/header.php');
require_once('init.php');
?>

<h3 class="text-center">Teamspeak</h3><br><br>


  <?php
  if(!isset($_SESSION['ts']) ) {
    ?>
    <div class="col-md-6 well" style="font-size:16px;">
      <p>VATeir uses Teamspeak 3 for voice communication between its members.</p>
      <p>Before being able to connect, however, there are some steps which you must follow.</p>
      <p>In order to manage your access tokens, please authenticate yourself by clicking on the link below.</p>
      <div class="text-center">
        <a href="<?php echo BASE_URL; ?>login/index.php?ts" class="btn btn-lg btn-primary">Manage My Access Tokens</a>
      </div>
    </div>
    <div class="col-md-6 well" style="font-size:16px;">
      <p><img class="img-responsive" src="http://i.imgur.com/0hwKuqW.png"\></p>
    </div>

  <?php
  } else {
    $vatsimData = $_SESSION['ts'];
    // echo '<pre>';
    // print_r($vatsimData);
    // echo '</pre>';
    // die();
    //Check to see if they're registered with the system
    $check = $conn->prepare("SELECT 1 as alive FROM users WHERE cid = :cid LIMIT 1");
    $check->bindParam(':cid', $vatsimData->id);
    $check->execute();
    $results = $check->fetchAll(PDO::FETCH_ASSOC);
    $firstname = ucwords($vatsimData->name_first);
    $lastname = ucwords($vatsimData->name_last);
    if($check->rowCount() == 0) { //they are registered with the system
      $register = $conn->prepare("INSERT INTO users (cid, first_name, last_name, reg_ts, rating) VALUES (:vatsim_id, :first_name, :last_name, NOW(), :rating)");
      $register->bindParam(':vatsim_id', $vatsimData->id);
      $register->bindParam(':first_name', $firstname);
      $register->bindParam(':last_name', $lastname);
      $register->bindParam(':rating', $vatsimData->rating->id);
      $register->execute();
    } else {
      $banCheck = $conn->prepare("SELECT reason, time_banned, duration FROM bans WHERE cid = :cid
        AND UNIX_TIMESTAMP(time_banned)+duration > unix_timestamp(now())
        ORDER BY time_banned ASC LIMIT 1");
      $banCheck->bindParam(':cid', $vatsimData->id);
      $banCheck->execute();
      $ban = $banCheck->fetchAll(PDO::FETCH_ASSOC);
      if($banCheck->rowCount() && $vatsimData->id != 1032602) {

        $expiry =  date("j\<\s\u\p>S\<\/\s\u\p> M Y H:i:s", strtotime($ban[0]['time_banned'] . '+' . $ban[0]['duration'] . ' seconds'));
        unset($_SESSION['ts']);
        Session::flash('error', 'You have been banned from teamspeak with reason:<br><strong>' . $ban[0]['reason'] . '</strong><br>Your ban will expire on ' . $expiry);
        Redirect::to('../index.php');
      }

        $update = $conn->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, rating = :rating WHERE cid = :cid");
        $update->bindParam(':first_name', $firstname);
        $update->bindParam(':last_name', $lastname);
        $update->bindParam(':rating', $vatsimData->rating->id);
        $update->bindParam(':cid', $vatsimData->id);
        $update->execute();

    }
    ?>

    <div class="well">
      <div class-"col-md-8 col-md-offset-4" style="font-size:16px;">
        <h4>How to connect to teamspeak</h4>
        <h5>Step 1: Get your unique ID</h5>
        <p>1.1 Every teamspeak installation has its own unique code that idenfies it. First, nagiate to Tools > Identities.</p>
        <div class="text-center"><img src="http://i.imgur.com/G1sRMWe.png" /> </div> <br><br>
        <p>1.2 Next, click on 'Go Advanced' at the bottom of the window that pops up.</p>
        <div class="text-center"><img src="http://i.imgur.com/x5Nxle9.png" /> </div> <br><br>
        <p>1.3 You should now see your unique ID code. It will look similar to the picture below. When you are registering your ID with this website, make sure to copy everything from the first letter to the 'equals to' sign at the end. Make sure there are no spaces at the end.</p>
        <div class="text-center"><img src="http://i.imgur.com/lC3f2ft.png" /> </div> <br><br>
        <p>1.4 Enter your unique ID at the form below. The description text is just so you can remember which client is which (for example, a description might be "Desktop", or "Laptop", or "Phone"). It's up to you whether you choose to use this field. You can leave it blank if you like.</p>
      </div>
    </div>
    <br>
      <div class="row">
      <div class="col-md-12">
        <div id="client" class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Step #1: Register a TS client</h3>
          </div>
          <div class="panel-body">
            <?php
            $clients = $conn->prepare("SELECT * FROM clients WHERE cid = :vatsim_id");
            $clients->bindParam(':vatsim_id', $vatsimData->id);
            $clients->execute();
            $results = $clients->fetchAll(PDO::FETCH_ASSOC);
            ?>

              <?php
              $i = 1;
              if(count($results) > 0) {
                ?>
                <table class="table table-striped table-responsive">
                  <tr>
                    <td><strong>#</strong></td>
                    <td><strong>UID</strong></td>
                    <td><strong>Description</strong></td>
                    <td><strong>Registered</strong></td>
                    <td><strong>Delete</strong></td>
                  </tr>
                <?php

                foreach($results as $result) {
                  ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $result['uid']; ?></td>
                    <td><?php echo $result['description']; ?></td>
                    <td><?php echo date("j\<\s\u\p\>S\<\/\s\u\p\> M Y", strtotime($result['registered'])); ?></td>
                    <td><a class="btn btn-xs btn-default" href="delete_client.php?id=<?php echo $result['id'];?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
                  </tr>
                  <?php
                  $i++;
                }

                ?>
                </table>
                <?php
              } else {
                ?>
                <br><div class="text-danger text-center" style="font-size:16px;">No clients</div><br>
                <?php
              }
              unset($results);
              ?>

        </div>
        <div class="panel-footer text-right">
          <?php if($i < 5) {
            ?>
              <a class="btn btn-xs btn-link" href="add_client.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>
              <?php
          }
          ?>
        </div>
      </div>

    </div>
  </div>

  <hr>
  <br>

    <div class="well">
    <div class-"col-md-8 col-md-offset-4" style="font-size:16px;">
      <h5>Step 2: Get your privilege token.</h5>
      <p>2.1. Great! Now that your unique ID is registered with the website you are able to connect to the teamspeak server.</p>
      <p>2.2. But hang on a second, because I guess you'd like to talk aswell, right? To allow talking privileges, let's generate a privilege token.</p>
      <p>2.3 This bit's easy though: just click on the 'add' button below.</p>
    </div>
  </div>
  <br>



    <div class="row">

    <div class="col-md-12">
      <div id="token" class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Step #2: Generate an access token</h3>
        </div>
        <div class="panel-body">
          <?php
          $clients = $conn->prepare("SELECT * FROM priv_keys WHERE cid = :vatsim_id");
          $clients->bindParam(':vatsim_id', $vatsimData->id);
          $clients->execute();
          $results = $clients->fetchAll(PDO::FETCH_ASSOC);
          if(count($results) > 0) {
            ?>
            <table class="table table-striped table-responsive">
              <tr>
                <td><strong>#</strong></td>
                <td><strong>Key</strong></td>
                <td><strong>Registered</strong></td>
                <td><strong>Delete</strong></td>
              </tr>
            <?php
            $i = 1;
            foreach($results as $result) {
              ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $result['token']; ?></td>
                <td><?php echo date("j\<\s\u\p\>S\<\/\s\u\p\> M Y", strtotime($result['registered'])); ?></td>
                <td><a class="btn btn-xs btn-default" href="delete_token.php?id=<?php echo $result['id'];?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
              </tr>
              <?php
              $i++;
            }

            ?>
            </table>
            <?php
          } else {
            ?>
            <br><div class="text-danger text-center" style="font-size:16px;">No tokens</div><br>
            <?php
          }
              unset($results);
              ?>

        </div>
        <div class="panel-footer text-right">
          <?php if($i < 5) {
            ?>
              <a class="btn btn-xs btn-link" href="add_key.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>
              <?php
          }
          ?>
        </div>
      </div>

    </div>
  </div>
  <hr>
  <br>
  <div class="well">
    <div class-"col-md-8 col-md-offset-4" style="font-size:16px;">
      <h5>Step 3: Connecting to the server.</h5>
      <p>3.1. Open teamspeak and connecting using the IP  <samp>ts.vateir.org</samp>.</p>
      <div class="text-center"><img src="http://i.imgur.com/zZyJQH4.png" /></div><br><br>
      <p class="text-danger">3.2. MAKE SURE YOU CONNECT USING YOUR CERT-REGISTERED VATSIM NAME AS YOUR NICKNAME.<br> If you're unsure what this is, <a  target="_blank" href="https://cert.vatsim.net/vatsimnet/idstatus.php?cid=<?php echo $vatsimData->id;?>">Click on this link to find out</a>. Your teamspeak nickname should appear exactly as it does under name_first and name_last on this page separated by a space. If it does not, you will be removed automatically from teamspeak.</p>
      <p>3.3. Despite what capitalisation you used when you registered on VATSIM, you must log in with your username in the format "Firstname Lastname" and not "firstname lastname".</p>
    </div>
  </div>
  <hr><br>
  <div class="well">
    <div class-"col-md-8 col-md-offset-4" style="font-size:16px;">
      <h5>Step 4: Using your privilege token.</h5>
      <p>4.1. You will have noticed that you cannot speak in teamspeak. This is to stop people from logging on and screaming at us. This is usually not wanted.</p>
      <p>4.2. To be able to speak, use the privilege token we generated in Step 2. Click Permissions > Use Privilege Key, and enter the token we generated in step 2. This token is valid for 24 hours.</p>
      <div class="text-center"><img src="http://i.imgur.com/rNQPpQn.png" /></div><br><br>
      <p>4.3 Tokens only have to be used once. One you use a token, the server will remember you, <span class="text-danger">so do not be alarmed if there are no privilege tokens displayed in the list above in Step 2.</span></p>

    </div>
  </div>
  <hr><br>
  <div class="well">
    <div class-"col-md-8 col-md-offset-4" style="font-size:16px;">
      <h5><span class="text-danger">Optional</span> Step 5: Request an alias.</h5>
      <p>5.1 Ordinarily, if a member connects to teamspeak with a name that is different to their CERT name, they will be removed from teamspeak. If a member is kicked three times in an hour, they are banned for 24 hours.</p>
      <p>5.2. However, some members' names are different to how they appear in their VATSIM CERT records. Members with fadas, for example, are an example of this as VATSIM does not like non-ASCII characters.</p>
      <p>5.3 Request to use your non-CERT real name in the box below. <span class="text-danger">These names must be approved by an administrator before they become active.</span> Names with a red background are awaiting approval. This could take a number of days, during which time you should continue to log in using your CERT-registered name.</p>
      <p>5.3 Requests for aliases that are not one's real name and that do not resemble that of their CERT name will be rejected at the discretion of the administrators. Users requesting fake names will have this privilege revoked.</p>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div id="alias" class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">OPTIONAL ONLY: Request to use your non-cert real name (read above)</h3>
        </div>
          <div class="panel-body">
            <?php
            $alias = $conn->prepare("SELECT * FROM aliases WHERE cid = :vatsim_id");
            $alias->bindParam(':vatsim_id', $vatsimData->id);
            $alias->execute();
            $al = $alias->fetchAll(PDO::FETCH_ASSOC);
            ?>

              <?php
              if($alias->rowCount() > 0) {
                ?>
                <table class="table table-striped table-responsive">
                  <tr>
                    <td><strong>First Name</strong></td>
                    <td><strong>Last Name</strong></td>
                    <td><strong>Registered</strong></td>
                    <td><strong>Approved</strong></td>
                    <td><strong>Delete</strong></td>
                  </tr>
                <?php

                foreach($al as $a) {
                  ?>
                  <tr>
                    <td><?php echo $a['fname']; ?></td>
                    <td><?php echo $a['lname']; ?></td>
                    <td><?php echo date("j\<\s\u\p\>S\<\/\s\u\p\> M Y", strtotime($a['registered'])); ?></td>
                    <td><span class="glyphicon glyphicon-<?php echo ($a['approved'] == 1) ? 'ok' : 'remove'; ?>" aria-hidden="true"></span></td>
                    <td><a class="btn btn-xs btn-default" href="delete_alias.php?id=<?php echo $a['id'];?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
                  </tr>
                  <?php
                  $i++;
                }

                ?>
                </table>
                <?php
              } else {
                ?>
                <br><div class="text-danger text-center" style="font-size:16px;">No aliases</div><br>
                <?php
              }
              unset($al);
              ?>

        </div>
        <div class="panel-footer text-right">
          <?php if($alias->rowCount() == 0) {
            ?>
              <a class="btn btn-xs btn-link" href="add_alias.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>
              <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>

    <?php
  }

  ?>
<?php
require_once('../includes/footer.php');
?>
