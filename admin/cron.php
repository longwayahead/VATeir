<?php
$pagetitle = "View Cron";
require_once("includes/header.php");
if(!$user->hasPermission('admin')) {
	Session::flash('error', 'Invalid permissions.');
	Redirect::to('../index.php');
}


$c = new Crons;
$cron = $c->get(Input::get('id'));
$data = json_decode($cron->data, true);
//print_r($data);

?>

<div class="row">
	<h3 class="text-center">Cron Job&mdash;<?php echo date("j F Y", strtotime($cron->date)); ?></h3><br>
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Updated Users</h3>
			</div>
			<div class="panel-body">
			<?php if(isset($data['updated'])) { ?>
				<table class="table table-striped table-responsive table-condensed">
					<!-- <tr>
						<td><strong>Date</strong></td>
						<td><strong>Details</strong></td>
					</tr> -->
					<?php foreach($data['updated'] as $cid => $update): ?>
						<tr>
							<td><a target="_blank" href="../controllers/profile.php?id=<?php echo $cid; ?>"><?php echo $cid; ?></td>
							<td>
								<?php 
									foreach($update as $p => $u) {
										echo '<strong>' . $p . ':</strong>' . ' ' . $u . '<br>';
									}
								?>
							</td>
						</tr>

					<?php endforeach; ?>
				</table>
			<?php } else { ?>
						<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No users</div><br>
			<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Registered Users</h3>
			</div>
			<div class="panel-body">
			<?php if(isset($data['registered'])) { ?>
				<table class="table table-striped table-responsive table-condensed">
					<tr>
						<td><strong>CID</strong></td>
					</tr>
					<?php foreach($data['registered'] as $id): ?>
						<tr>
							<td><a target="_blank" href="../controllers/profile.php?id=<?php echo $id; ?>"><?php echo $id; ?></td>
						</tr>

					<?php endforeach; ?>
				</table>
			<?php } else { ?>
						<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No users</div><br>
			<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Set as inactive</h3>
			</div>
			<div class="panel-body">
			<?php if(isset($data['setasinactive'])) { ?>
				<table class="table table-striped table-responsive table-condensed">
					<tr>
						<td><strong>CID</strong></td>
					</tr>
					<?php foreach($data['setasinactive'] as $id): ?>
						<tr>
							<td><a target="_blank" href="../controllers/profile.php?id=<?php echo $id; ?>"><?php echo $id; ?></td>
						</tr>

					<?php endforeach; ?>
				</table>
			<?php } else { ?>
						<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No users</div><br>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h3 class="panel-title">Update Failed</h3>
			</div>
			<div class="panel-body">
			<?php if(isset($data['updatefail'])) { ?>
				<table class="table table-striped table-responsive table-condensed">
					<?php foreach($data['updatefail'] as $cid => $msg): ?>
						<tr>
							<td><a target="_blank" href="../controllers/profile.php?id=<?php echo $cid; ?>"><?php echo $cid; ?></td>
							<td>
								<?php echo $msg; ?>
							</td>
						</tr>

					<?php endforeach; ?>
				</table>
			<?php } else { ?>
						<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No users</div><br>
			<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h3 class="panel-title">Registration Failed</h3>
			</div>
			<div class="panel-body">
			<?php if(isset($data['regfail'])) { ?>
				<table class="table table-striped table-responsive table-condensed">
					<?php foreach($data['regfail'] as $cid => $msg): ?>
						<tr>
							<td><a target="_blank" href="https://cert.vatsim.net/cert/vatsimnet/idstatus.php?cid=<?php echo $cid; ?>"><?php echo $cid; ?></td>
							<td>
								<?php echo $msg; ?>
							</td>
						</tr>

					<?php endforeach; ?>
				</table>
			<?php } else { ?>
						<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No users</div><br>
			<?php } ?>
			</div>
		</div>
	</div>
</div>





</div>
<?php
require_once("../includes/footer.php");
?>