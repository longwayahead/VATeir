<?php
$pagetitle = "Incoming Student";
require_once("includes/header.php");
$a = new Admin;
$t = new Training;

if(Input::exists('post')) {
	try {

		if(Input::get('reject')) {
			$delete = $user->delete( 
					[
						['id', '=', Input::get('id')],
						['vateir_status', '<>', 1],
						['vateir_status', '<>', 2]
					]
				);
			if($delete) {
				Session::flash('success', 'Application approved');
				Redirect::to('./');
			}
		}elseif(Input::get('approve')) {
			if(Input::get('vateir_status') == 3) {
				$test = $user->update(
					[
						'controllers.vateir_status' => '2',
						'controllers.grou' => '10'
					],
					[
						['controllers.id', '=', Input::get('id')]
					]
				);
				if(!$t->findStudent(Input::get('id'))) {
					if($controller->rating > 7) {
						$rating = $user->getRealRating(Input::get('id'));
					} else {
						$rating = $controller->rating;
					}
					$program = $t->program($rating);
					$studentMake = $t->createStudent(array(
						'cid'		=> Input::get('id'),
						'program'	=> 	$program
					));
				}
				Session::flash('success', 'Application approved');
				Redirect::to('./');
			}
		}
	
} catch(Exception $e) {
	echo $e->getMessage();
}
} else {

	$controller = $a->incoming(Input::get('id'))[0];
	if($controller) {
	?>

	<div class="row">
		<h3 class="text-center">Incoming Student Request</h3><br>
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">View Details</h3>
				</div>
				<div class="panel-body" style="padding:0px;">

					<table class="table table-responsive table-striped table-condensed">
						<tr>
							<td><strong>Name</strong></td>
							<td><strong>Type</strong></td>
							<td><strong>CID</strong></td>
							<td><strong>Rating</strong></td>
							<td><strong>Pilot Rating</strong></td>
							<td><strong>Registered</strong></td>
							<td><strong>Mail</strong></td>
						</tr>
						<?php
						$pilot = $t->pilotRating($controller->pilot_rating);
						?>
						<tr>
							<td><?php echo $controller->first_name . ' ' . $controller->last_name;?></td>
							<td><?php echo $controller->status;?></td>
							<td><?php echo $controller->cid;?></td>
							<td><?php echo $controller->long . ' (' . $controller->short . ')';?></td>
							<td><?php echo $pilot; ?></td>
							<td><?php echo date('j-M-Y', strtotime($controller->regdate_vatsim)); ?></td>
							<td><a href="mailto:<?php echo $controller->email; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a></td>
						</tr>
					</table>
					<form action="" method="post">
							<textarea class="col-md-12" placeholder="Type a reason if rejecting..."></textarea>
				</div>
				<div class="panel-footer">
					<div class="text-right">
							<input type="hidden" name="vateir_status" value="<?php echo $controller->vateir_status;?>">
							<input type="hidden" name="id" value="<?php echo Input::get('id'); ?>">
							<input type="submit" name="reject" class="btn btn-danger" value="Reject Application"></input>
							<input type="submit" name="approve" class="btn btn-success" <?php echo ($controller->vateir_status == 4) ? 'disabled="" value="Approval by CERT"' : 'value="Accept Application"';?>></input>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	} else {
		Session::flash('danger', 'No incoming controller found.');
		Redirect::to('./');
	}
}

require_once("../includes/footer.php");
?>