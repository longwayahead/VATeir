<?php
$pagetitle = "North Atlantic Tracks";
require_once('includes/header.php');
$d = new Download;
?>
<h3 class="text-center">Oceanic Tracks</h3>
<?php
$datetime = new DateTime();
$datetime->modify('+1 day');
?>
<div class="text-center" style="font-size:20px;">Today is day <strong><?php echo $datetime->format('z'); ?></strong> of the year.</div>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
	<?php
		$tracks = $d->oadAPI('tracks', ['cache' => 1])[0]->nats->routes;
		?>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">North Atlantic Tracks</h3>
			</div>
			<div class="panel-body">
				<table class="table table-condensed table-responsive">
					<tr>
						<td colspan="2"><strong>Track</strong></td>
						<td><strong>Direction</strong></td>
						<td><strong>TMI</strong></td>
					</tr>

					<?php
						foreach($tracks as $track) {
							?>
							<tr>
								<td><strong><?php echo $track->name; ?></strong></td>
								<td>
									<?php foreach($track->navdata as $waypoint) {
										echo $waypoint->ident . '<br>';
									}	?>
								</td>
								<td><?php echo $track->direction; ?></td>
								<td><?php echo $track->tmi; ?></td>
							</tr>
						<?php
						}
					?>
				</table>
			</div>
		</div>

	</div>
</div>



<?php

require_once('../includes/footer.php');
