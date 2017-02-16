<?php
$pagetitle = "North Atlantic Tracks";
require_once('includes/header.php');
$d = new Download;
$tracks = $d->blackswan();
// echo '<pre>';
// print_r($tracks);
// echo '</pre>';
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
	//	$tracks = $d->oadAPI('tracks', ['cache' => 1])[0]->nats->routes;
		?>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">North Atlantic Tracks</h3>
			</div>
			<div class="panel-body">
				<?php if(!empty($tracks)) { ?>
				<table class="table table-condensed table-responsive">
					<tr>
						<td colspan="2"><div class="text-center"><strong>Track</strong></div></td>
						<td><strong>FL / Direction</strong></td>
						<td><strong>TMI</strong></td>
						<!-- <td><strong>Valid From</strong></td>
						<td><strong>Valid Until</strong></td> -->
					</tr>

					<?php
						foreach($tracks as $track) {
							?>
							<tr>
								<td><strong><?php echo $track['@attributes']['id']; ?></strong></td>
								<td>
									<?php foreach($track['waypoints']['waypoint'] as $waypoint) {
										echo $waypoint['@attributes']['id'] . '<br>';
									}	?>
								</td>
								<td>
									<?php foreach($track['levels']['level'] as $level) {
										echo $level['@attributes']['fl'] . ' / ' . $level['@attributes']['direction'] . '<br>';
									}	?>
								</td>
								<td><?php echo $track['@attributes']['tmi']; ?></td>
								<!-- <td><?php
									//$from = new DateTime($track['@attributes']['validfrom']);
									//echo $from->format('jS F \<\b\r\>\<\s\t\r\o\n\g\>g.ia\<\s\t\r\o\n\g\>'); ?>
								</td>
								<td><?php
									//$until = new DateTime($track['@attributes']['validuntil']);
									//echo $until->format('jS F \<\b\r\>\<\s\t\r\o\n\g\>g.ia\<\s\t\r\o\n\g\>'); ?>
								</td> -->
							</tr>
						<?php
						}
					?>
				</table>
				<?php } else { ?>
						<br>
						<div class="row">
							<div class="col-md-6 col-md-offset-3">
								<div class="text-danger text-center" style="font-size:16px;">Could not get data</div><br>
							</div>
						</div>
				<?php } ?>
			</div>
		</div>

	</div>
</div>



<?php

require_once('../includes/footer.php');
