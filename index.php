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
	.blink {
	    animation: blinker 1.5s cubic-bezier(.5, 0, 1, 1) infinite alternate;
	}

	@keyframes blinker {
	  from { opacity: 1; }
	  to { opacity: 0; }
	}
</style>
<?php
//$greeting = ['Hi there.', 'Fáilte.', 'Hello.', 'Dia dhuit.', 'Welcome.'];

?>
<div class="col-md-8 well">


  <?php
  //$stream = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=UC3cbTV3I-D6QPE4H8v6W2fw&type=video&eventType=live&key=AIzaSyAQXxoeB3xrW9ZKM3-Rgh4dViUd_5JlWos"));
// echo '<pre>';
// var_dump($stream);
// echo '</pre>';

	$twitch = new Twitch;
	$livestream = $twitch->johnStream();
	if($livestream == false || strpos($livestream->stream->channel->status, 'ATC') == false) {
		?>
		<h2 class="text-center">Fáilte</h2>
	 	<br>
		<div class="videoWrapper"><iframe width="560" height="315" src="https://www.youtube.com/embed/r1wrc9DLgWQ" frameborder="0" allowfullscreen></iframe></iframe></div>
		<?php
	} else {
		?>
		<div class="videoWrapper">
			<iframe
				width="560"
				height="310"
				src="https://player.twitch.tv/?channel=squawkmodecharlie"
				frameborder="0"
				allowfullscreen>
			</iframe>
		</div>
		<h5 class="text-center">
			<span class="label label-danger blink">Live!</span>
			<?php echo '  ' .$livestream->stream->channel->status; ?>
		</h5>
		<?php

	}


  ?>

  <br>
	<br>
  	<p>Welcome to VATeir! Nestled between British, French, and oceanic airspace, the Shannon FIR is home to Ireland's aviation presence on VATSIM. VATeir's goal is to bolster this aviation presence with realistic virtual Air Traffic Control to compliment the altogether too realistic weather and stunning scenery on the island of Ireland.</p>
		<p>For pilots, I invite you to take a look through our website, in particular through the pilots' section, whereas for prospective controllers, I invite you to log in and to submit your availability so that we can get the ball rolling on your ATC training. If you have any questions, please get in touch. Our contact details are on the <i>Staff</i> page in the <i>About</i> section, otherwise drop us a line on the forum.</p>

  <p class="text-right"><br>Is mise le meas,<br>
  <i>Martin Bergin</i><br>
  VATeir Director</p>

</div>


<div class="col-md-4 well">
	<!-- <div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title">Donations</h3>
		</div>
		<div class="panel-body text-center">
			<div style="font-size:16px">
				€25 of our goal has been raised.<br>
				Please consider donating to help us pay for our web services.<br>
			</div><br>
			<a href="donate.php" class="btn btn-default">Read more&raquo;&raquo;</a>

		</div>
	</div> -->
	<div class="panel panel-primary">
		<div class="panel-heading">
				<h3 class="panel-title">VATeir Now...</h3>
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
						$ctx = stream_context_create(array(
					    'http' => array(
					        'timeout' => 2
					        )
					    )
					);
							//cacheFile("datafiles/atc.json", "http://api.vateud.net/online/atc/ei.json");
							$atcs = json_decode(@file_get_contents("http://api.vateud.net/online/atc/ei.json", 0, $ctx));

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
				<div class="tab-pane fade" id="inbound">
					<table class="table table-striped table-condensed" style="margin:0;">
						<?php
							//cacheFile("datafiles/in.json", "http://api.vateud.net/online/arrivals/ei.json"); -->
							$pilots = json_decode(@file_get_contents("http://api.vateud.net/online/arrivals/ei.json", 0, $ctx));

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
									?>
									<tr>
											<td>
												<a tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="bottom" data-html="true" title="Flight Details" data-content="
												<strong>Route: </strong> <?php echo (isset($pilot->route)) ? $pilot->route : '';?><br>
												<strong>Aircraft: </strong> <?php echo $pilot->aircraft; ?> <br>
												<strong>Altitude: </strong> <?php echo number_format($pilot->altitude) . ' ft (' . $planAlt . ')';?><br>
												<strong>Speed: </strong><?php echo $pilot->groundspeed;?> kts<br>
												<?php echo '<a target=\'_blank\' href=\'' .htmlentities($pilot->gcmap).'\'><img src=\'' . htmlentities($pilot->gcmap) . '\' class=\'img-responsive\'></a>
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
							$pilots = json_decode(@file_get_contents("http://api.vateud.net/online/departures/ei.json", 0, $ctx));

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
?>
									<tr>
											<td>
												<a tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" data-placement="bottom" data-html="true" title="Flight Details" data-content="
												<strong>Route: </strong> <?php echo (isset($pilot->route)) ? $pilot->route : '';?><br>
												<strong>Aircraft: </strong> <?php echo $pilot->aircraft; ?> <br>
												<strong>Altitude: </strong> <?php echo number_format($pilot->altitude) . ' ft (' . $planAlt . ')';?><br>
												<strong>Speed: </strong><?php echo $pilot->groundspeed;?> kts<br>
												<?php echo '<a target=\'_blank\' href=\'' .htmlentities($pilot->gcmap).'\'><img src=\'' . htmlentities($pilot->gcmap) . '\' class=\'img-responsive\'></a>
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
				<div class="fb-post"
      data-href="https://www.facebook.com/events/1527634263934583/permalink/1527634357267907"
      data-width="380"></div>
			</div>
		</div>
	<?php
	// $ev = new Events;
	// try{
	// $event = $ev->random();
	// 	if(!empty($event)) {


	?>


				<!--<div class="thumbnail">
					<div class="text-center">
						<img class="img-responsive" data-src="holder.js/300x300" src="<?php //echo $event->banner_url;?>" alt="...">
					</div>
					<div class="caption">
						<h4><?php// echo $event->title;?><small><br><?php //echo $event->starts_date . ' ' . $event->starts_time;?>&ndash;<?php //echo $event->ends_time;?></small></h4>
						<p><?php// echo strip_tags($event->short_description); ?></p>
						<p><div class="text-right"><a href="events/index.php#<?php //echo $event->id; ?>" class="btn btn-success" role="button">Read More</a></div></p>
					</div>
				</div>
				</div>


	<?php
		//} else {
			//echo '<div class="text-danger text-center" style="font-size:16px;"><br>No forthcoming events</div></div><br>';
		//}
//	}catch (Exception $e) {
		//echo $e->getMessage();
//	}
	?>
-->
<!-- </div> -->
  <div class="fb-page"
    data-href="https://www.facebook.com/vateir"
    data-width="380"
    data-hide-cover="false"
    data-show-facepile="false"
    data-show-posts="false"></div>
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
