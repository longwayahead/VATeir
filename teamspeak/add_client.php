<?php
require_once('../includes/header.php');
require_once('init.php');
?>
  <?php
  if(!isset($_SESSION['ts']) ) {
    	Session::flash('error', 'You have to be authenticated to view that page.');
      Redirect::to('./index.php');
  }
  $vatsimData = $_SESSION['ts'];
  $check = $conn->prepare("SELECT * FROM clients where cid = :cid");
  $check->bindParam(':cid', $vatsimData->id);
  $check->execute();
  $client_list = $check->fetchAll(PDO::FETCH_ASSOC);
  if($check->rowCount() >= 5) {
    Session::flash('error', 'You cannot register more than 5 clients.');
    Redirect::to('./index.php');
  }
  if(Input::exists()) { //if form submitted!
  	$validate = new Validate();
  	$validation = $validate->check($_POST, array(
  		'uid' => array(
  			'field_name' => 'Unique ID',
  			'required' => true
  		),
      'description' => array(
        'field_name' => 'Description',
        'max' => 12
      )
    ));
    if($validation->passed()) {
      $insert = $conn->prepare("INSERT INTO clients (cid, uid, description, registered) VALUES (:cid, :uid, :description, NOW())");
      $insert->bindParam(':cid', $vatsimData->id);
      $insert->bindParam(':uid', Input::get('uid'));
      if(Input::get('description')) {
        $insert->bindParam(':description', Input::get('description'));
      } else {
        $n = null;
        $insert->bindParam(':description', $n);
      }
      $insert->execute();
      Session::flash('success', 'Client added!');
			Redirect::to('./index.php#client');
    } else {
      echo '
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <h3 class="panel-title">The following errors occured:</h3>
          </div>
          <div class="panel-body">';
            foreach($validation->errors() as $error) {
            echo $error.'<br>';
          }
          echo '</div>
        </div>
      </div>
      ';
    }
  }

  ?>
  <div class="col-md-6 col-md-offset-3 well">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <form id="thisForm" class="form-horizontal" method="post" action="" onsubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').value='Submitting...';">
    <fieldset>
      <legend>Add TS Client</legend>
      <div class="form-group">
        <label for="uid" class="col-lg-3 control-label">Unique ID</label>
        <div class="col-lg-9">
          <input class="form-control" type="text" id="uid" name="uid" required>
        </div>
      </div>
      <div class="form-group">
        <label for="description" class="col-lg-3 control-label">Description</label>
        <div class="col-lg-9">
          <input class="form-control" type="text" id="description" name="description" placeholder="Desktop PC">
          <span class="help-block">This helps you remember which client is which. For example "Desktop PC", or "Phone". Leave this blank if you like.</span>

        </div>
      </div>
    </fieldset>
    <div class="row form-group text-center">
      <div class="col-lg-10 col-md-offset-1">
      <button id="submit" type="submit" name="submit" class="btn btn-primary">Submit!</submit>
    </div>
  </form>



</div>
