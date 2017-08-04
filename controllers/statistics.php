<?php
$pagetitle = "Roster of Mentors";
require_once("includes/header.php");
?>
<h3 class="text-center">Statistics</h3>
<br>
<div class="row">
	<div class="col-md-10 col-md-offset-1">

		<?php require_once('../statistics/total.html'); ?>

	</div>
</div>


<?php
require_once("../includes/footer.php");
