<?php
$pagetitle = "Home";
require_once("includes/header.php");
?>

<style>
	.videoWrapper {
		position: relative;
		padding-bottom: 56.25%; /* 16:9 */
		padding-top: 25px;
		height: 0;
	}
	.videoWrapper iframe {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
</style>
<?php
$greeting = ['Hi there.', 'Fáilte.', 'Hello.', 'Dia dhuit.', 'Welcome.'];

?>
<div class="col-md-8 well">
  <h2 class="text-center"><?php echo $greeting[array_rand($greeting)];?></h2>
  <?php
   $stream = json_decode(file_get_contents("https://api.twitch.tv/kraken/streams/vatsim_atc"));
   if($stream->stream != null && strpos($stream->stream->channel->status, '[VATSIM]') !== false) {
     ?>
       <div class="videoWrapper"><iframe src="https://player.twitch.tv/?channel=vatsim_atc"></iframe></div>
       <h5 class="text-center"><?php //echo $stream->stream->channel->status; ?></h5>
     <?php
   } else {

       $random = rand(0, 1);
       if($random == 1) {
       	echo '<div class="videoWrapper"><iframe width="560" height="315"  src="https://www.youtube.com/embed/gLjKKQ0-BrE" frameborder="0" allowfullscreen></iframe></div>';
       } else {
       	echo '<div class="videoWrapper"><iframe width="560" height="315" src="https://www.youtube.com/embed/r1wrc9DLgWQ" frameborder="0" allowfullscreen></iframe></div>';
     }
  }

  ?>

  <br>

  	<p>Firstly, welcome to VATeir. Nestled between British and French, and oceanic airspace, the Shannon FIR is home to Ireland's aviation presence on VATSIM. VATeir's goal is to bolster this aviation presence with realistic virtual Air Traffic Control to compliment the altogether too realistic weather and stunning scenery on the island of Ireland.</p>
		<p>For pilots, I invite you to take a look through our website, in particular through the pilots' section, whereas for prospective controllers, I invite you to log in and to submit your availability so that we can get the ball rolling on your ATC training. If you have any questions, please get in touch. Our contact details are on the <i>Staff</i> page in the <i>About</i> section, otherwise drop us a line on the forum.</p>

  <p class="text-right"><br>Is mise le meas,<br>
  <i>Martin Bergin</i><br>
  VATeir Director</p>
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
			<!-- <li class="">
				<a href="#metar" data-toggle="tab" aria-expanded="false">
					<div class="hidden-xs hidden-md">
						<span class="glyphicon glyphicon-cloud" aria-hidden="true"></span> METAR
					</div>
					<div class="visible-xs visible-md">
						<span class="glyphicon glyphicon-cloud" aria-hidden="true"></span>
					</div>
				</a>
			</li> -->
			<li class="">
				<li class="">
				<a href="#inbound" data-toggle="tab" aria-expanded="false">
					<div class="hidden-xs hidden-md">
						<span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span> Arrivals
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
						<span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Departures
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
							//cacheFile("datafiles/atc.json", "http://api.vateud.net/online/atc/ei.json");
							$atcs = json_decode(file_get_contents("http://api.vateud.net/online/atc/ei.json"));

							if($atcs) {

								echo '<tr>
										<td>Callsign</td>
										<td>Controller</td>
										<td>Frequency</td>
									</tr>';

								foreach($atcs as $atc) {
									if(!strpos($atc->callsign, "ATIS") && $atc->facility > 0) { //Don't get the ATIS

									echo '<tr>
											<td>

											<a tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="bottom" data-html="true" title="Session Details" data-content="
												<strong>Rating: </strong>' . $atc->rating . '<br>
												<strong>Online Since: </strong>' . date("d\<\s\u\p\>S\<\/\s\u\p\> M H:i", strtotime($atc->online_since)). '<br>

											" data-original-title="" title="" aria-describedby="popover148301" style="cursor:pointer">' . $atc->callsign . '</a>

											</td>
											<td>';

											if($user->find($atc->cid)) { //link to profile if vateir controller
												echo '<a target="_blank" href="' . BASE_URL . 'controllers/profile.php?id=' . $atc->cid . '">' . $atc->name . '</a>';
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
						// cacheFile("datafiles/metar.txt", "http://metar.vatsim.net/metar.php?id=EI");
						// echo '<div class="text-justified"><samp>
						// 	<p>' . getMetar("EICK") . '</p>
						// 	<p>' . getMetar("EIDW") . '</p>
						// 	<p>' . getMetar("EIKN") . '</p>
						// 	<p>' . getMetar("EINN") . '</p>
						// </samp></div>';
					?>
					</p>
				</div>
				<div class="tab-pane fade" id="inbound">
					<table class="table table-striped table-condensed" style="margin:0;">
						<?php
							//cacheFile("datafiles/in.json", "http://api.vateud.net/online/arrivals/ei.json"); -->
							$pilots = json_decode(file_get_contents("http://api.vateud.net/online/arrivals/ei.json"));

							if($pilots) {

								echo '<tr>
										<td>Callsign</td>
										<td class="hidden-xs">Name</td>
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
												<a tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="bottom" data-html="true" title="Flight Details" data-content="
												<strong>Route: </strong>' . $pilot->route . '<br>
												<strong>Aircraft: </strong>' . $pilot->aircraft . '<br>
												<strong>Altitude: </strong>' . number_format($pilot->altitude) . ' ft (' . $planAlt . ')<br>
												<strong>Speed: </strong>' . $pilot->groundspeed . ' kts<br>
												<a target=\'_blank\' href=\'' .htmlentities($pilot->gcmap).'\'><img src=\'' . htmlentities($pilot->gcmap) . '\' class=\'img-responsive\'></a>
											" data-original-title="" title="" aria-describedby="popover148301" style="cursor:pointer">' . $pilot->callsign . '</a>
											</td>
											<td class="hidden-xs">' . $pilot->name . '</td>
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
							//cacheFile("datafiles/out.json", "");
							$pilots = json_decode(file_get_contents("http://api.vateud.net/online/departures/ei.json"));

							if($pilots) {

								echo '<tr>
										<td>Callsign</td>
										<td class="hidden-xs">Name</td>
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
												<a tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="bottom" data-html="true" title="Flight Details" data-content="
												<strong>Route: </strong>' . $pilot->route . '<br>
												<strong>Aircraft: </strong>' . $pilot->aircraft . '<br>
												<strong>Altitude: </strong>' . number_format($pilot->altitude) . ' ft (' . $planAlt . ')<br>
												<strong>Speed: </strong>' . $pilot->groundspeed . ' kts<br>

												<a target=\'_blank\' href=\'' .htmlentities($pilot->gcmap).'\'><img src=\'' . htmlentities($pilot->gcmap) . '\' class=\'img-responsive\'></a>
											" data-original-title="" title="" aria-describedby="popover148301" style="cursor:pointer">' . $pilot->callsign . '</a>
											</td>
											<td class="hidden-xs">' . $pilot->name . '</td>
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
				<h3 class="panel-title">Events</h3>
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
						<p><?php echo strip_tags($event->short_description); ?></p>
						<p><div class="text-right"><a href="events/index.php#<?php echo $event->id; ?>" class="btn btn-success" role="button">Read More</a></div></p>
					</div>
				</div>
				</div>


	<?php
		} else {
			echo '<div class="text-danger text-center" style="font-size:16px;"><br>No forthcoming events</div></div><br>';
		}
	}catch (Exception $e) {
		echo $e->getMessage();
	}
	?>
	</div>
  <div class="fb-page"
    data-href="https://www.facebook.com/vateir"
    data-width="380"
    data-hide-cover="false"
    data-show-facepile="false"
    data-show-posts="false"></div>
  <!-- <div class="fb-post" data-href="https://www.facebook.com/VATeir/posts/1284026881627510" data-width="350" data-show-text="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/VATeir/posts/1284026881627510"><p>After an absence of several years VATeir&#x2014;the Irish vACC of the VATSIM network&#x2014;is glad to announce its 2016...</p>Posted by <a href="https://www.facebook.com/VATeir/">VATeir</a> on&nbsp;<a href="https://www.facebook.com/VATeir/posts/1284026881627510">Tuesday, 17 May 2016</a></blockquote></div></div> -->
</div>
<?php
require_once("includes/footer.php");

?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
