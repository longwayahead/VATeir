<?php
$pagetitle = "Flight Statistics";
require_once('includes/header.php');
?>
<h3 class="text-center">EISN Flight Statistics</h3><br>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Network Movements</h3>
      </div>
      <div class="panel-body text-center">
        <?php require_once('../statistics/movements_graph.php'); ?>

      </div>
    </div>
  </div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Live movements: last 7 days</h3>
			</div>
			<div class="panel-body">
        <?php require_once('../statistics/movements.html'); ?>

			</div>
      <div class="panel-footer text-right">Last updated: <?php echo date("H:i \I\S\T j\<\s\u\p\>S\<\/\s\u\p\> M Y", filemtime('../statistics/movements.html')); ?></div>
		</div>
	</div>
</div>



<?php
require_once('../includes/footer.php');