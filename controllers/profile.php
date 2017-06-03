<?php
$pagetitle = 'Controller Profile';
require_once('includes/header.php');
try {
	$student = $t->getStudent(Input::get('id'));
	if($user->isActive($student->cid)) {
		$hours = $user->atcHours($student->cid);
	}

	$t = new Training;

	$avatar = $user->getAvatarURL(['email' => $student->email]);
	$bookings = $t->userBookings($student->cid);

}catch(Exception $e) {
	echo $e->getMessage();
}

?>

<div class="col-md-12">
	<h3 class="text-center"><?php echo $student->first_name . ' ' . $student->last_name; ?></h3><br>
	<div class="row">

		<div class="col-md-8">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h3 class="panel-title">Profile</h3>
				</div>
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-responsive table-striped">
							<tr>
								<td colspan="2"  class="text-center">
									<img class="img-circle" src="<?php echo $avatar; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<strong>CID:</strong>
								</td>
								<td>
									<?php echo $student->cid; ?>
								</td>
							</tr>
							<tr>
								<td>
									<strong>Rating:</strong>
								</td>
								<td>
									<?php echo '<div class="hidden-xs" style="display:inline-block;">' . $student->long . ' (</div>' . $student->short . '<div class="hidden-xs" style="display:inline-block;">)</div>';?>
								</td>
							</tr>
						  <tr>
								<td>
									<strong>Pilot Rating:</strong>

								</td>
								<td>
									<?php echo $student->pratingstring; ?>
								</td>
							</tr>
							<tr>
								<td>
									<strong>Joined:</strong>
								</td>
								<td>
									<?php echo date("j\<\s\u\p\>S\<\/\s\u\p\> M Y", strtotime($student->regdate_vatsim)); ?>

								</td>
							</tr>
						<tr>
							<td>
								<strong>Activity Status:</strong>
							</td>
							<td>
								<a data-toggle="tooltip" data-placement="top" data-original-title="<?php echo ($student->rating > 0) ? 'Active' : 'Inactive'; ?>">VATSIM</a>
								<a data-toggle="tooltip" data-placement="top" data-original-title="<?php echo ($student->alive != 0) ? 'Active' : 'Inactive'; ?>">VATeir</a>
							</td>
						</tr>
						</table>
					</div>

				</div>
			</div>
		</div>


		<div class="col-md-4">
			<?php if($user->isLoggedIn() && $user->hasAdmin("admin")) {
				?>
				<div class="panel panel-warning">
					<div class="panel-heading">
						<h3 class="panel-title">Admin Functions</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-3">
							Admin:
						</div>
						<div class="col-md-9">

							<form action="../admin/change_permissions.php" method="post">
								<select <?php echo ($student->adminPerm) ? '' : 'disabled' ;?> class="col-md-6 text-left form-control tick" onchange="this.form.submit()" name="data[<?php echo $student->cid;?>]">
								<option value="0" selected="">N/A</option>
								<option value="2" selected="">Operations</option>
								<option value="1" selected="">Admin</option>

										<?php //echo '<option value="' . $perm->id . '"';
										// if($perm->id == $data->grou) {
										// 	echo 'selected="" ';
										// }
										// echo '>';
										// echo ($data->grou >= 10 && $data->grou <= 15) ? $perm->name : 'None' ;
										// echo '</option>';
										?>
								</select>
							</form>
						</div>
					</div>
				</div>
				<?php
			}
			?>
				<div class="panel panel-warning">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo $student->first_name;?>'s Bookings</h3>
					</div>
					<div class="panel-body">
						<?php
						if(!empty($bookings)) {
							echo '<table class="table table-striped table-responsive table-condensed">
								<tr>
									<td><strong>Position</strong></td>
									<td><strong>From (UTC)</strong></td>
									<td><strong>Until (UTC)</strong></td>
								</tr>';
							foreach($bookings as $booking) {

										echo '<tr><td>';
											echo $booking->callsign;
										echo '</td>';
										echo '<td>';
											echo date('j F H:i', strtotime($booking->time_start));
										echo '</td><td>';
											echo date('j F H:i', strtotime($booking->time_end));
										echo '</td></tr>';

							}
							echo '</table>';
						} else {
							echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No Forthcoming Bookings</div><br>';
						}
						?>
					</div>
				</div>


			</div>
			<div class="col-md-8">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h3 class="panel-title">Logged Hours</h3>
				</div>
				<div class="panel-body">
					<table class="table table-striped table-condensed table-responsive">
						<?php
						if(!empty($hours)) {
							foreach($hours as $rating => $time) {
								if($time != 0 && $rating != '@attributes') {
									switch($rating) {
										case($rating == 'atctime'):
											$rating = 'ATC Time';
										break;
										case($rating == 'pilottime') :
											$rating = 'Pilot Time';
										break;
									}

									$newTime = convertTime($time);
									echo '<tr>
											<td><strong>' . $rating . '</strong></td>
											<td>' . $newTime . '</td>
										</tr>';
								}
							}
						} else {
							echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">Controller Inactive</div><br>';
						}
						?>
					</table>
				</div>
				<div class="panel-footer text-right">
					<a target="_blank" href="http://stats.vatsim.net/search_id.php?id=<?php echo $student->cid; ?>">More Stats</a>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>

<?php
require_once('../includes/footer.php');
