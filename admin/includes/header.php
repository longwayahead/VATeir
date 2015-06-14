<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
if(!$user->hasPermission('admin')) {
	Session::flash('danger', 'Insufficient permissions');
	Redirect::to('../');
}
?>
<div class="row">


<div class="col-sm-3 col-md-2">
	
	<ul class="well nav nav-list nav-list-vivid">
	Admin
			<li><a href="<?php echo BASE_URL . 'admin/'; ?>">Home</a></li>

	</ul>

</div>
<div class="col-sm-9 col-md-10">
