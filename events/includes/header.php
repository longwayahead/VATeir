<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
$ev = new Events;
?>
<style>
a {
	color:#4CAF50;
}
a:hover {
	color:#449d48;
}

</style>
<div class="row">


<div class="col-sm-3 col-md-2">
	
	<ul class="well nav nav-list nav-list-vivid">

			<li class="active"><a href="<?php echo BASE_URL . 'events/'; ?>">Current Events</a></li>
			<li><a href="<?php echo BASE_URL . 'events/past.php'; ?>">Past Events</a></li>			
			<li class="divider"></li>
			<br>

	</ul>

</div>
<div class="col-sm-9 col-md-10">
