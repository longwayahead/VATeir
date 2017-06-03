<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
if(!isset($_SESSION['user'])) {
	Redirect::to(BASE_URL . 'login/');
}
if(!$user->hasPermission('operations')) {
	Session::flash('error', 'Insufficient permissions.');
	Redirect::to('../index.php');

}
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

			<li><a href="<?php echo BASE_URL . 'operations/'; ?>">Home</a></li>

			<li class="divider"></li>
			<br>

	</ul>

</div>
<div class="col-sm-9 col-md-10">
