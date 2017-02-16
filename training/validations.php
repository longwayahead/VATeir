<?php
$pagetitle = 'My Validations';
require_once('./includes/header.php');

echo '<h3 class="text-center">My Validations</h3><br>';
$validations = $t->fetchAllValidations(1, '`v`.`approved`', $user->data()->id);
echo '<div class="col-md-10 col-md-offset-1">';
echo '<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Validation List</h3>
			</div>
			<div class="panel-body">';
			
	if($validations) {
	echo '<table class="table table-condensed table-striped">
				<tr>
					<td><strong>Position</strong></td>
					<td><strong>Issued By</strong></td>
					<td><strong>From</strong></td>
					<td><strong>Expires</strong></td>
				';

					foreach($validations as $validation) {
						echo '<tr>
							<td>' . $validation->callsign . '</td>
							<td>' . $validation->mentor_fname . ' ' . $validation->mentor_lname . '</td>
							<td>' . date("j-M-Y", strtotime($validation->valid_from)) . '</td>
							<td>' . date("j-M-Y", strtotime($validation->valid_until)) . '</td>
						</tr>';
					}
					echo '</table>';
			

		
	} else {
		echo '<br><div class="row">
								<div class="col-md-6 col-md-offset-3">
							<div class="text-danger text-center" style="font-size:16px;">No Validations</div><br>
			
							</div></div>';
	}
	echo '
				
			</div>
		</div>
</div>
	<div class="col-md-6">';
		

echo '</div>';

echo '</div>';

require_once('../includes/footer.php');
?>