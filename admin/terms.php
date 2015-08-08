<?php
$pagetitle = "T&Cs";
require_once('includes/header.php');
?>
<h3 class="text-center">Terms and Conditions</h3><br>
<?php
$terms = $user->getTerms();
echo '<div class="col-md-10 col-md-offset-1">

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
						<td style="white-space:nowrap;">
							<strong>Date</strong>
						</td>
						<td>
							<strong></strong>
						</td>
						<td>
							<strong></strong>
						</td>
					</tr>';
					foreach($terms as $term) {
							echo '<tr>
									<td>';
										echo ($term->type == 0) ? 'Website' : 'Forum';
									echo '</td>

									<td>
										' . $term->name . '
									</td>
									<td>
										' . $term->text . '
									</td>
									<td style="white-space:nowrap;">
										' . date("j M y", strtotime($term->date)) . '
									</td>
									<td>
										<a href="edit_term.php?id=' . $term->id . '" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
									</td>
									
									<td>
										<a href="delete_term.php?id=' . $term->id . '" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
									</td>
								</tr>';
						}
				} else {
					echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No terms and conditions</div><br>';
				}
			echo '</table>
			</div>
			<div class="panel-footer text-right">
				<a href="'. BASE_URL . 'admin/add_term.php" data-toggle="tooltip" data-placement="left" title="Add Term">Add</a>
			</div>
		</div>
	</div>';

