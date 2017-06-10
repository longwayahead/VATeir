<?php
$pagetitle = "Admin Home";
require_once("includes/header.php");

$t = new Training;$a = new Admin;
$incoming = $a->incoming();
?>
<div class="row">
	<h3 class="text-center"><?php echo ($user->data()->id == 931070) ? "Hi Martin." : "Admin Dashboard";</h3><br>
	<div class="col-md-3">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Crons</h3>
			</div>
			<div class="panel-body">
			<?php
				$c = new Crons;
				$crons = $c->limit();
				//print_r($crons);
				if(!empty($crons)) {
			?>

				<table class="table table-striped table-responsive table-condensed">
					<tr>
						<td><strong>Date</strong></td>
						<td><strong>Details</strong></td>
					</tr>
					<?php foreach($crons as $cron): ?>
						<tr>
							<td><?php echo date("j M y", strtotime($cron->date)); ?></td>
							<td><a class="btn btn-xs btn-primary" href="cron.php?id=<?php echo $cron->id; ?>"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></td>
						</tr>

					<?php endforeach; ?>
				</table>
				<?php } else { //if empty crons ?>
					<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No crons</div><br>
			<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Site Config</h3>
			</div>
			<div class="panel-body">
				<div class="col-md-6">
					Login:
				</div>
				<div class="col-md-6">
				<?php
					echo ($user->loginOpen()) ? '<a href="config.php?login=0">Close</a>' : '<a href="config.php?login=1">Reopen</a>';
				?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Site Admins</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped table-condensed">
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Permission</strong></td>
					</tr>
					<?php
						$admins = $a->getAdmins();
						foreach($admins as $admin) {
							echo '<tr><td><a href="../controllers/profile.php?id=' . $admin->id . '">' . $admin->first_name . ' ' . $admin->last_name . '</a></td><td>' . $admin->name.'</td></tr>';
						}
					 ?>
				 </table>
			</div>
		</div>
	</div>
</div>





</div>
<?php
require_once("../includes/footer.php");
?>
