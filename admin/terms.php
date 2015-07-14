<?php
$pagetitle = "Add a T&C";
require_once('includes/header.php');
?>
<h3 class="text-center">Add T&Cs</h3><br>
<?php
$terms = $user->getTerms();
echo '<div class="col-md-10 col-md-offset-1">
<div class="text-right" style="margin-bottom:5px;"><a href="add_term.php" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a></div>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Terms and Conditions</h3>
			</div>

			<div class="panel-body">';
			if(!empty($terms)) {
				echo '<table class="table table-responsive table-striped">
					<tr>
						<td>
							<strong>Type</strong>
						</td>
						<td>
							<strong>Name</strong>
						</td>
						<td>
							<strong>Description</strong>
						</td>
						<td>
							<strong>Date Effected</strong>
						</td>
						<td>
							<strong>Edit</strong>
						</td>
						<td>
							<strong>Delete</strong>
						</td>
					</tr>';
					foreach($terms as $term) {
							echo '<tr>
									<td>';
										echo ($term->type == 0) ? 'Site' : 'Forum';
									echo '</td>

									<td>
										' . $term->name . '
									</td>
									<td>
										' . $term->text . '
									</td>
									<td>
										' . date("j M y", strtotime($term->date)) . '
									</td>
									<td>
										<a href="edit_term.php?id=' . $term->id . '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
									</td>
									
									<td>
										<a href="delete_term.php?id=' . $term->id . '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
									</td>
								</tr>';
						}
				} else {
					echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No terms and conditions</div><br>';
				}
			echo '</table>
			</div>
		</div>
	</div>';

