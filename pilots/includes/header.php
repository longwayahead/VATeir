<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
$ev = new Events;
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

			<li class="active"><a href="<?php echo BASE_URL . 'pilots/'; ?>">Home</a></li>		
			<li class="active"><a href="<?php echo BASE_URL . 'pilots/notams.php'; ?>">All NOTAMs</a></li>		
			<li class="divider"></li>
			<br>

	</ul>

</div>
<div class="col-sm-9 col-md-10">