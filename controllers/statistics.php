<?php
$pagetitle = "Roster of Mentors";
require_once("includes/header.php");
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<h3 class="text-center"><?php echo date("F");?>'s Top Controllers</h3><br>

		<?php
		include('../statistics/facility.html');
		echo '<div class="text-right">Last updated: ' . date("H:i \I\S\T j\<\s\u\p\>S\<\/\s\u\p\> M Y", filemtime('../statistics/facility.html')) . '</div>';
		echo '<br>';
		?>
		<h3 class="text-center"><?php echo date("F");?>'s Controlling hours</h3><br>
		<?php require_once('../statistics/total.html'); ?>

	</div>
</div>


<?php
require_once("../includes/footer.php");
