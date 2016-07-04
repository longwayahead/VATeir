<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
$t = new Training;
?>
<style>
a {
	color:#FF9800;
}
a:hover {
	color:#e68900;
}

</style>
<div class="row">


<div class="col-sm-3 col-md-2">

	<ul class="well nav nav-list nav-list-vivid">


		Controllers
			<li><a href="<?php echo BASE_URL . 'controllers/'; ?>">Roster</a></li>
			<li><a target="_blank" href="<?php echo BASE_URL . 'atc_solo/solo.txt'; ?>">Validations</a></li>
			<li><a href="<?php echo BASE_URL . 'controllers/mentors.php'; ?>">Mentors</a></li>
			<?php //if($user->isLoggedIn()) {	?>
				<li><a href="<?php echo BASE_URL . 'controllers/downloads.php'; ?>">Downloads</a></li>
			<?php// } ?>
			<li class="divider"></li>
			<br>

	</ul>

</div>
<div class="col-sm-9 col-md-10">
