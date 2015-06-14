<?php
require_once("../includes/header.php");
if(Input::exists('post')) {
	foreach(Input::get('data') as $id => $p) {	
		if(($p >= 10 && $p <= 15) && ($id != $user->data()->id) && ($p >= 10) && ($p <= 15)) {
			
			$user->update(
				['grou' => $p],
				[['id', '=', $id]]
			);
		}
	}
	Session::flash('success', 'Permissions Updated');
	Redirect::to('./mentors.php');
}
?>
<h3 class="text-center">Mentor List</h3><br>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Training Department Mentors</h3>
			</div>
			<form action="" method="post">
				<div class="panel-body">
				<?php
				$mentors = $t->getMentors();
				$perms = $t->getPermissions();
				//print_r($mentors);
				if(count($mentors)) {
					?>
					<table class="table table-condensed table-striped table-responsive">
						
						<tr>
							<td><strong>Name</strong></td>
							<td><strong>Rating</strong></td>
							<td><strong>Permissions</strong></td>
						</tr>
						<?php
						foreach($mentors as $mentor):
							?>
							<tr>
								<td><?php echo $mentor->first_name . ' ' . $mentor->last_name;?></td>
								<td><?php echo $mentor->long . ' (' . $mentor->short . ')';?></td>
								<td>
									
									<select class="col-md-6 text-left form-control tick"name="data[<?php echo $mentor->cid;?>]">
										<?php foreach($perms as $perm):	?>
											
											<?php echo '<option value="' . $perm->id . '"';
											if($perm->id == $mentor->grou) {
												echo 'selected="" ';
											}
											echo '>' . $perm->name . '</option>';
											?>
											
										<?php endforeach;?>
									</select>
							
								</td>
							</tr>
						<?php endforeach; ?>
						
					</table>

				</div>
				<div class="panel-footer">
					<div class="text-right">
						<button type="reset" class="btn btn-default">Reset</button>
						<button type="submit" name="update" class="btn btn-primary">Update</button>
					</div>
					<?php
				} else {
					echo '<div class="text-danger text-center" style="font-size:16px">No mentors</div>';
				}
				?>
				</div>
			</form>
		</div>
	</div>
</div>