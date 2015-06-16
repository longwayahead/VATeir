<?php
$pagetitle = "Home";
require_once("includes/header.php");
$greeting = ['Hi there.', 'Fáilte.', 'Hello.', 'Dia dhuit.', 'Welcome.', 'Conas atá cúrsaí?', 'Deas tú a fheiscint.'];

?>
<div class="col-md-8 well">
<h2 class="text-center"><?php echo $greeting[array_rand($greeting)];?></h2><br>
<?php
$random = rand(0, 1);
if($random == 1) {
	echo '<iframe width="560" height="315"  src="https://www.youtube.com/embed/gLjKKQ0-BrE" frameborder="0" allowfullscreen></iframe>';
} else {
	echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/r1wrc9DLgWQ" frameborder="0" allowfullscreen></iframe>';
}
?>
<br>
<br>
<p>Tá an-fáilte romhat go suíomh lárionad fíorúil urlámhas na hÉireann.</p>

<p>Ar an gcéad dul síos agus más ball den ghrúpa VATéir thú, sínigh isteach sa suíomh chun úsáid a bhaint as na h-áiseanna atá ar fáil duit mar rialatheoir.</p>

<p>É sin ráite, más pílóta amháin thú, tabhar sreac-fhéachaint ar na h-áiseanna uile atá ar fáil duit ar an suíomh agus ag eitilt in Eirinn atá tú.</p>

<p>Tá súil againn go mbaineann tú úsáid as na h-áiseanna atá cuirthe ar fáil duit.</p>

<p>Le gach dea-ghuí, an fhoireann VATéir</p>






</div>
<div class="col-md-4 well">
	<div class="panel panel-primary">
		<div class="panel-heading">
				<h3 class="panel-title">VATeir Live</h3>
			</div>
			<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#atc" data-toggle="tab" aria-expanded="true">
					<div class="hidden-xs hidden-md">
						<span class="glyphicon glyphicon-plane" aria-hidden="true"></span> ATC
					</div>
					<div class="visible-xs visible-md">
						<span class="glyphicon glyphicon-plane" aria-hidden="true"></span>
					</div>
				</a>
			</li>
			<li class="">
				<a href="#metar" data-toggle="tab" aria-expanded="false">
					<div class="hidden-xs hidden-md">
						<span class="glyphicon glyphicon-cloud" aria-hidden="true"></span> METAR
					</div>
					<div class="visible-xs visible-md">
						<span class="glyphicon glyphicon-cloud" aria-hidden="true"></span>
					</div>
				</a>
			</li>
			<li class="">
				<li class="">
				<a href="#inbound" data-toggle="tab" aria-expanded="false">
					<div class="hidden-xs hidden-md">
						<span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> Arr
					</div>
					<div class="visible-xs visible-md">
						<span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span>
					</div>
				</a>
			</li>
			<li class="">
				<li class="">
				<a href="#outbound" data-toggle="tab" aria-expanded="false">
					<div class="hidden-xs hidden-md">
						<span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Dep
					</div>
					<div class="visible-xs visible-md">
						<span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span>
					</div>
				</a>
			</li>
		</ul>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade active in" id="atc">
					<table class="table table-striped table-condensed" style="margin:0;">
						<?php
							cacheFile("datafiles/atc.json", "http://api.vateud.net/online/atc/ei.json");
							$atcs = json_decode(file_get_contents("datafiles/atc.json"));
							
							if($atcs) {
							
								echo '<tr>
										<td>Callsign</td>
										<td>Controller</td>
										<td>Frequency</td>
									</tr>';
								
								foreach($atcs as $atc) {
									if(!strpos($atc->callsign, "ATIS")) { //Don't get the ATIS
									echo '<tr>
											<td>
										
											<a tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="left" data-html="true" title="Session Details" data-content="
												<strong>CID: </strong>' . $atc->cid . '<br>
												<strong>Rating: </strong>' . $atc->rating . '<br>
												<strong>Online Since: </strong>' . implode(" ", explode("T", $atc->online_since)) . '<br>
																						
											" data-original-title="" title="" aria-describedby="popover148301" style="cursor:pointer">' . $atc->callsign . '</a>

											</td>
											<td>';

											if($user->find($atc->cid)) { //link to profile if vateir controller
												echo '<a href="#">' . $atc->name . '</a>';
											} else {
												echo $atc->name;
											}


											echo '</td>
											<td>' . $atc->frequency . '</td>
										</tr>';
									}
								}
							} else {
								echo '<div class="text-danger text-center" style="font-size:16px;"><br>No Irish ATC online right now</div><br>';
							}
							unset($atcs);
						?>
					</table>
				</div>
				<div class="tab-pane fade" id="metar">
					<p>
					<?php
						//cacheFile("datafiles/metar.txt", "http://metar.vatsim.net/metar.php?id=EI");
						echo '<div class="text-justified"><samp>
							<p>' . getMetar("EICK") . '</p>
							<p>' . getMetar("EIDW") . '</p>
							<p>' . getMetar("EIKN") . '</p>
							<p>' . getMetar("EINN") . '</p>
						</samp></div>';
					?>
					</p>
				</div>
				<div class="tab-pane fade" id="inbound">
					<table class="table table-striped table-condensed" style="margin:0;">
						<?php
							cacheFile("datafiles/in.json", "http://api.vateud.net/online/arrivals/ei.json");
							$pilots = json_decode(file_get_contents("datafiles/in.json"));
							
							if($pilots) {
							
								echo '<tr>
										<td>Callsign</td>
										<td>Name</td>
										<td>From</td>
										<td>To</td>
									</tr>';
								
								foreach($pilots as $pilot) {
									//echo '<a href="' . htmlentities($pilot->gcmap) . '">adf</a>';
									if(strpos($pilot->planned_altitude, "FL") !== false) {
										$planAlt = $pilot->planned_altitude;
									} else {
										$planAlt = 'FL' . substr($pilot->planned_altitude, 0, 3);
									}
									echo '<tr>
											<td>
												<a tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="left" data-html="true" title="Flight Details" data-content="
												<strong>Route: </strong>' . $pilot->route . '<br>
												<strong>Aircraft: </strong>' . $pilot->aircraft . '<br>
												<strong>Altitude: </strong>' . number_format($pilot->altitude) . ' ft (' . $planAlt . ')<br>
												<strong>Speed: </strong>' . $pilot->groundspeed . ' kts<br>
												<a target=\'_blank\' href=\'' .htmlentities($pilot->gcmap).'\'><img src=\'' . htmlentities($pilot->gcmap) . '\' class=\'img-responsive\'></a>	
											" data-original-title="" title="" aria-describedby="popover148301" style="cursor:pointer">' . $pilot->callsign . '</a>
											</td>
											<td>' . $pilot->name . '</td>
											<td>' . $pilot->origin . '</td>
											<td>' . $pilot->destination . '</td>
										</tr>';
								}
							} else {
								echo '<div class="text-danger text-center" style="font-size:16px;"><br>No Inbound Traffic to EISN</div><br>';
							}
							unset($pilots);
						?>
					</table>
				</div>
				<div class="tab-pane fade" id="outbound">
					<table class="table table-striped table-condensed" style="margin:0;">
						<?php
							cacheFile("datafiles/out.json", "http://api.vateud.net/online/departures/ei.json");
							$pilots = json_decode(file_get_contents("datafiles/out.json"));
							
							if($pilots) {
							
								echo '<tr>
										<td>Callsign</td>
										<td>Name</td>
										<td>From</td>
										<td>To</td>
									</tr>';
								
								foreach($pilots as $pilot) {
									if(strpos($pilot->planned_altitude, "FL") !== false) {
										$planAlt = $pilot->planned_altitude;
									} else {
										$planAlt = 'FL' . substr($pilot->planned_altitude, 0, 3);
									}
									//<strong>Map: </strong><a target=\'_blank\' href=\''.htmlentities($pilot->gcmap).'\'>Click</a><br>
									echo '<tr>
											<td>
												<a tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="left" data-html="true" title="Flight Details" data-content="
												<strong>Route: </strong>' . $pilot->route . '<br>
												<strong>Aircraft: </strong>' . $pilot->aircraft . '<br>
												<strong>Altitude: </strong>' . number_format($pilot->altitude) . ' ft (' . $planAlt . ')<br>
												<strong>Speed: </strong>' . $pilot->groundspeed . ' kts<br>
												
												<a target=\'_blank\' href=\'' .htmlentities($pilot->gcmap).'\'><img src=\'' . htmlentities($pilot->gcmap) . '\' class=\'img-responsive\'></a>		
											" data-original-title="" title="" aria-describedby="popover148301" style="cursor:pointer">' . $pilot->callsign . '</a>
											</td>
											<td>' . $pilot->name . '</td>
											<td>' . $pilot->origin . '</td>
											<td>' . $pilot->destination . '</td>
										</tr>';
								}
							} else {
								echo '<div class="text-danger text-center" style="font-size:16px;"><br>No Outbound Traffic from EISN</div><br>';
							}
							unset($pilots);
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title">Upcoming Events</h3>
			</div>
			<div class="panel-body">
	<?php
	$ev = new Events;
	try{
	$event = $ev->random();
		if(!empty($event)) {


	?>
		
			
				<div class="thumbnail">
					<div class="text-center">
						<img class="img-responsive" data-src="holder.js/300x300" src="<?php echo $event->banner_url;?>" alt="...">
					</div>
					<div class="caption">
						<h4><?php echo $event->title;?><small><br><?php echo $event->starts_date . ' ' . $event->starts_time;?>&ndash;<?php echo $event->ends_time;?></small></h4>
						<p><?php echo $event->short_description; ?></p>
						<p><div class="text-right"><a href="#" class="btn btn-success" role="button">Read More</a></div></p>
					</div>
				</div>
			
		
	<?php
		} else {
			echo '<div class="text-danger text-center" style="font-size:16px;"><br>No forthcoming events</div><br>';
		}
	}catch (Exception $e) {
		echo $e->getMessage();
	}
	?>
	</div>
</div>
<?php
require_once("includes/footer.php");

?>