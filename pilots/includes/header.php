<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
?>
<style>
a {
	color:#9c27b0;
}
a:hover {
	color:#8c239e;
}

</style>
<div class="row">


<div class="col-sm-3 col-md-2">

	<ul class="well nav nav-list nav-list-vivid">

			<li><a href="<?php echo BASE_URL . 'pilots/'; ?>">Home</a></li>
			<li><a href="<?php echo BASE_URL . 'pilots/weather.php'; ?>">Weather</a></li>
			<li><a href="<?php echo BASE_URL . 'pilots/statistics.php'; ?>">Statistics</a></li>
			<li><a href="<?php echo BASE_URL . 'pilots/notams.php'; ?>">NOTAMs</a></li>
			<li><a target="_blank" href="https://cert.vatsim.net/fp/file.php">File Flight Plan</a></li>
			<li><a href="<?php echo BASE_URL . 'pilots/fuel.php'; ?>">Fuel Planning</a></li>
			<li><a href="<?php echo BASE_URL . 'pilots/tracks.php'; ?>">Oceanic Tracks</a></li>
			<li><a href="<?php echo BASE_URL . 'teamspeak'; ?>">Teamspeak</a></li>
			<li class="divider"></li>
			<br>

	</ul>

</div>
<div class="col-sm-9 col-md-10">
