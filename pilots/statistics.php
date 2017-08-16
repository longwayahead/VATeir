<?php
$pagetitle = "Flight Statistics";
require_once('includes/header.php');
?>
<h3 class="text-center">EISN Flight Statistics</h3><br>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="https://cdn.rawgit.com/eligrey/FileSaver.js/master/FileSaver.min.js"></script>
<script src="https://cdn.rawgit.com/eligrey/canvas-toBlob.js/master/canvas-toBlob.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

        <?php require_once('../statistics/movements_line_7.php'); ?>
        <?php require_once('../statistics/movements_pie_7.php'); ?>


<!-- <div class="row">
	<div class="col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Live movements: last 7 days</h3>
			</div>
			<div class="panel-body">
        <?php //require_once('../statistics/movements.html'); ?>

			</div>
      <div class="panel-footer text-right">Last updated: <?php echo date("H:i \I\S\T j\<\s\u\p\>S\<\/\s\u\p\> M Y", filemtime('../statistics/movements.html')); ?></div>
		</div>
	</div>
</div> -->



<?php
require_once('../includes/footer.php');
