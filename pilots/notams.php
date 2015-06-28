<?php
$pagetitle = 'NOTAMs';
require_once('includes/header.php');
	$a = new Airports;
?>
	  <h3 class="text-center">NOTAMs</h3>
	  <br>
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">All NOTAMs</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped table-responsive table-condensed">
				<?php
				$notams = $a->notams();
				$icao = array();
				if(count($notams)) {
					echo '<tr>
							<td><strong>ICAO</strong></td>
							<td><strong>Message</strong></td>
							<td><strong>From</strong></td>
							<td><strong>Until</strong></td>
						</tr>';
					foreach($notams as $notam) {
						echo '<tr><td>';
							if(!in_array($notam['icao'], $icao)) {
								$icao[] = $notam['icao'];
								echo $notam['icao'];
							}
						echo '</td>';
						echo '<td>';
							echo $notam['message'];
						echo '</td><td>';
							echo date('d-M-y H:i', strtotime($notam['start']));
						echo '</td><td>';
							echo date('d-M-y H:i', strtotime($notam['end']));
						echo '</td></tr>';
					}
				}
				
				?>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
require_once('../includes/footer.php');
?>