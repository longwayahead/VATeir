<?php
$pagetitle = "Roster of Controllers";
require_once("includes/header.php");
$t = new Training;
try {
	$controllers = $t->getControllers(['active' => 1]);
	$inactives = $t->getControllers();
//	print_r($controllers);
}catch(Exception $e) {
	echo $e->getMessage();
}
?>
<h3 class="text-center">Roster of Controllers</h3>
<br>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<h3 class="panel-title">Active Controllers</h3>
			</div>
		<div class="panel-body">
		<?php
			if(count($controllers)) {
				?>
				<table class="table table-responsive table-striped table-condensed">
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Rating</strong></td>
						<td><strong>Pilot Rating</strong></td>
						<td><strong>Profile</strong></td>
					</tr>
					<?php foreach($controllers as $controller): ?>
						
						<tr>
							<td><?php echo $controller->first_name . ' ' . $controller->last_name;?></td>
							<td><?php echo $controller->long . ' (' . $controller->short . ')';?></td>
							<td><?php echo $controller->pratingstring;?></td>
							<td><?php echo '<a class="btn btn-xs btn-warning" href="../profiles/view.php?id=' . $controller->cid . '"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>';?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php	 } else {
				echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No active controllers</div><br>';
				} ?>
				
			</div>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Inactive Controllers</h3>
			</div>
		<div class="panel-body">
		<?php
			if(count($inactives)) {
				?>
				<table class="table table-responsive table-striped table-condensed">
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Rating</strong></td>
						<td><strong>Pilot Rating</strong></td>
						<td><strong>Profile</strong></td>
					</tr>
					<?php foreach($inactives as $controller): ?>
						
						<tr>
							<td><?php echo $controller->first_name . ' ' . $controller->last_name;?></td>
							<td><?php echo $controller->long . ' (' . $controller->short . ')';?></td>
							<td><?php echo $controller->pratingstring;?></td>
							<td><?php echo '<a class="btn btn-xs btn-warning" href="../profiles/view.php?id=' . $controller->cid . '"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>';?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php	}  else {
				echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No controllers</div><br>';
				} ?>
				
			</div>
		</div>
	</div>
</div>
</div>
<?php require_once('../includes/footer.php');