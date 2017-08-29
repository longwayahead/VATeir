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
  $check = $conn->prepare("SELECT * FROM aliases where cid = :cid");
  $check->bindParam(':cid', $vatsimData->id);
  $check->execute();
  $client_list = $check->fetchAll(PDO::FETCH_ASSOC);
  if($check->rowCount() >= 5) {
    Session::flash('error', 'You cannot register more than 1 alias.');
    Redirect::to('./index.php');
  }
  if(Input::exists()) { //if form submitted!
  	$validate = new Validate();
  	$validation = $validate->check($_POST, array(
  		'first_name' => array(
  			'field_name' => 'First Name',
  			'required' => true
  		),
      'last_name' => array(
        'field_name' => 'Last Name',
        'required' => true
      )
    ));
    if($validation->passed()) {
      $insert = $conn->prepare("INSERT INTO aliases (cid, fname, lname, registered) VALUES (:cid, :first_name, :last_name, NOW())");
      $insert->bindParam(':cid', $vatsimData->id);
      $insert->bindParam(':first_name', Input::get('first_name'));
      $insert->bindParam(':last_name', Input::get('last_name'));
      $insert->execute();
      Session::flash('success', 'Alias added!');
			Redirect::to('./index.php#alias');
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
        <label for="first_name" class="col-lg-3 control-label">First Name</label>
        <div class="col-lg-9">
          <input class="form-control" type="text" id="first_name" name="first_name" required>
        </div>
      </div>
      <div class="form-group">
        <label for="last_name" class="col-lg-3 control-label">Last Name</label>
        <div class="col-lg-9">
          <input class="form-control" type="text" id="last_name" name="last_name">
           <span class="help-block">Do not add a space between first and last names.</span>
        </div>
      </div>
    </fieldset>
    <div class="row form-group text-center">
      <div class="col-lg-10 col-md-offset-1">
      <button id="submit" type="submit" name="submit" class="btn btn-primary">Submit!</submit>
    </div>
  </form>



</div>
