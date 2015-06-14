<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
?>

<div class="row">
<div class="col-sm-3 col-md-2">
	<ul class="well nav nav-list nav-list-vivid">
			<li class="active"><a href="<?php echo BASE_URL . 'about/'; ?>">About</a></li>
			<li class="active"><a href="<?php echo BASE_URL . 'about/staff.php'; ?>">Staff List</a></li>
			<li class="divider"></li>
			<br>
	</ul>

</div>
<div class="col-sm-9 col-md-10">
