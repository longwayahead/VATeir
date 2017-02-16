<?php
$pagetitle = "Staff List";
require_once("includes/header.php");

cacheFile("../datafiles/staff.json", "http://api.vateud.net/staff_members/vacc/IRL.json");
$staff = json_decode(file_get_contents("../datafiles/staff.json"));
// print_r($staff);
?>

<h3 class="text-center">Staff</h3>
<br>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">List of Staff Members</h3>
			</div>
		<div class="panel-body">
		<?php
			if(count($staff)) {
				
				?>
				<table class="table table-responsive table-striped table-condensed">
					<tr>
						<td><strong>Name</strong></td>
						<td class="hidden-xs"><strong>CID</strong></td>
						<td class="hidden-xs"><strong>Callsign</strong></td>
						<td><strong>Position</strong></td>
						<td><strong>Contact</strong></td>
					</tr>
					<?php foreach($staff as $s): ?>
						
							<tr>
								<td><?php echo (isset($s->member)) ? $s->member->firstname . ' ' . $s->member->lastname : ''; ?></td>
								<td class="hidden-xs"><?php echo (isset($s->cid)) ? $s->cid : ''; ?></td>
								<td class="hidden-xs"><?php echo $s->callsign;?></td>
								<td><?php echo $s->position;?></td>
								<td><?php echo '<a class="btn btn-xs btn-primary" href="mailto:' . $s->email . '"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a>';?></td>
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
<?php
try {
	$t = new Training;
	$controllers = $t->getMentors();
	//print_r($controllers);
}catch(Exception $e) {
	echo $e->getMessage();
}
//print_r($controllers);
?>
<h3 class="text-center">Training Department</h3>
<br>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Mentor List</h3>
			</div>
		<div class="panel-body">
		<?php
			if(count($controllers)) {
				//print_r($controllers);
				?>
				<table class="table table-responsive table-striped table-condensed">
					<tr>
						<td><strong>Name</strong></td>
						<td class="hidden-xs"><strong>Rating</strong></td>
						<td class="hidden-xs"><strong>Pilot Rating</strong></td>
						<td><strong>Role</strong></td>
						<td><strong>Profile</strong></td>
					</tr>
					<?php foreach($controllers as $controller): ?>
						
						<tr>
							<td><?php echo $controller->first_name . ' ' . $controller->last_name;?></td>
							<td class="hidden-xs"><?php echo $controller->long . ' (' . $controller->short . ')';?></td>
							<td class="hidden-xs"><?php echo $controller->pratingstring;?></td>
							<td><?php echo $controller->name;?></td>
							<td><?php echo '<a class="btn btn-xs btn-primary" href="../controllers/profile.php?id=' . $controller->cid . '"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>';?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php	} else {
				echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No mentors</div><br>';
				} ?>
				
			</div>
		</div>
	</div>
</div>

</div>
<?php require_once('../includes/footer.php');