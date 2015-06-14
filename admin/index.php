<?php
$pagetitle = "Admin Home";
require_once("includes/header.php");
$a = new Admin;
$t = new Training;
$incoming = $a->incoming();
?>

<div class="row">
	<h3 class="text-center">Admin Dashboard</h3><br>
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Pending Incoming Students</h3>
			</div>
			<div class="panel-body" style="padding:0px;">
			<?php
			if($incoming) {
				?>
				<table class="table table-responsive table-striped table-condensed">
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Type</strong></td>
						<td><strong>CID</strong></td>
						<td><strong>Rating</strong></td>
						<td><strong>Pilot Rating</strong></td>
						<td><strong>Registered</strong></td>
						<td><strong>Options</strong></td>
					</tr>
					<?php
					
						foreach($incoming as $controller):
							$pilot = $t->pilotRating($controller->pilot_rating);
							?>
							<tr>
								<td><?php echo $controller->first_name . ' ' . $controller->last_name;?></td>
								<td><?php echo $controller->status;?></td>
								<td><?php echo $controller->cid;?></td>
								<td><?php echo $controller->short;?></td>
								<td><?php echo $pilot; ?></td>
								<td><?php echo date('j-M-Y', strtotime($controller->regdate_vatsim)); ?></td>
							
								<td><a href="incoming.php?id=<?php echo $controller->cid;?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></td>
							</tr>
							<?php
						endforeach;
					
					?>
				</table>
				<?php
				} else {
						echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No pending requests</div><br>';
					}
					?>
			</div>
		</div>
	</div>
</div>





</div>
<?php
require_once("../includes/footer.php");
?>