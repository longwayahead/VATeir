<?php
$pagetitle = "Visiting controllers";
require_once("includes/header.php");
$t = new Training;

if(Input::exists('post')) {
	$t->addVisitingCID(['cid' => Input::get('cid')]);
}

$visitingCIDs = $t->getVisitingCIDs();

?>

<h3 class="text-center">Accepted Visiting Controllers</h3><br>
<div class="col-md-6 col-md-offset-3">
	<p class="text-center">In order for a visiting controller to register an account with the website, their CID must be added to this list first.</p>
<form action="" method="post">

    <div class="input-group" style="margin-bottom:5px">
      <input type="text" class="form-control" name="cid" autocomplete="off" placeholder="CID" style="padding-left:10px; padding-right:10px;">
      <span class="input-group-btn">
        <input class="btn btn-primary" type="submit" value="Add">

      </span>
    </div><!-- /input-group -->
    </form>
	<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Visiting Controllers</h3>
			</div>
			<div class="panel-body">
        <?php
        if(!empty($visitingCIDs)) {
        ?>
  				<table class="table table-responsive table-striped">
  					<?php

  					foreach($visitingCIDs as $cid) {
  						echo '<tr>
  								<td>' . $cid->cid .  '</td>
  								<td><a class="btn btn-xs btn-default" href="delete_visiting.php?id=' . $cid->cid . '"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
  							</tr>';
  					} ?>
          </table>
          <?php
				} else {
          echo ' <div class="text-danger text-center" style="font-size:16px;"><br>No CIDs</div><br>';
        }
				echo '</div>';

require_once("../includes/footer.php");
