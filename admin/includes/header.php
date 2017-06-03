<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
if(!$user->hasAdmin('admin')) {
	Session::flash('danger', 'Insufficient permissions');
	Redirect::to('../');
}
$a = new Admin;
?>
<div class="row">


<div class="col-sm-3 col-md-2">

	<ul class="well nav nav-list nav-list-vivid">
	Admin
			<li><a href="<?php echo BASE_URL . 'admin/'; ?>">Home</a></li>
			<li><a href="<?php echo BASE_URL . 'admin/terms.php'; ?>">T&Cs</a></li>
			<li><a href="<?php echo BASE_URL . 'admin/allow.php'; ?>">Allow</a></li>
			<li><a href="<?php echo BASE_URL . 'admin/downloads.php'; ?>">Downloads</a></li>

	</ul>

</div>
<div class="col-sm-9 col-md-10">
