<?php
$pagetitle = 'NOTAMs';
require_once('includes/header.php');
	$a = new Airports;
?>
	  <h3 class="text-center">NOTAMs</h3>
	  <div class="text-danger text-center" style="font-size:16px; margin-top:8px;">Not for real world use</div>
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
							<td class="hidden-xs"><strong>From</strong></td>
							<td class="hidden-xs"><strong>Until</strong></td>
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
						echo '</td><td class="hidden-xs">';
							echo '<a data-toggle="tooltip" data-placement="top" data-original-title="' . date('j M Y H:i', strtotime($notam['start'])) . '">Start</a>';
						echo '</td><td class="hidden-xs">';
							echo '<a data-toggle="tooltip" data-placement="top" data-original-title="';
							if($notam['end'] == 'Permanent') {
								echo 'Permanent';
							} else {
								echo date('j M Y H:i', strtotime($notam['end']));
							}
							 echo '">End</a>';
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
