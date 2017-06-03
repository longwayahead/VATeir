<?php
$pagetitle = "Authorised CIDs";
require_once("includes/header.php");
$a = new Admin;

if(Input::exists('post')) {
	$a->addAllow(['cid' => Input::get('cid')]);
}

$allows = $a->getAllowed();

?>

<h3 class="text-center">Authorised CIDs</h3><br>
<div class="col-md-6 col-md-offset-3">
	<p class="text-center">In the event that site login is closed, the following CIDs will be allowed access.</p>
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
				<h3 class="panel-title">Allowed CIDs</h3>
			</div>
			<div class="panel-body">
				<table class="table table-responsive table-striped">
					<?php
					if(!empty($allows)) {
					foreach($allows as $allow) {
						echo '<tr>
								<td>' . $allow->cid .  '</td>
								<td><a class="btn btn-xs btn-default" href="delete_allow.php?id=' . $allow->cid . '"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
							</tr>';
					}
				}
				echo '</table></div>';

require_once("../includes/footer.php");
