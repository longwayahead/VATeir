<?php
$pagetitle = "Controller Statistics";
require_once("includes/header.php");
?>

		<h3 class="text-center">VATeir ATC Statistics</h3><br>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
		<script src="https://cdn.rawgit.com/eligrey/FileSaver.js/master/FileSaver.min.js"></script>
		<script src="https://cdn.rawgit.com/eligrey/canvas-toBlob.js/master/canvas-toBlob.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

		        <?php require_once('../statistics/atcmovements_line_7.php'); ?>
						<br>
		<h4><?php echo date("F");?>'s Top Controllers</h4><br>
		<?php
		include('../statistics/facility.html');
		echo '<div class="text-right">Last updated: ' . date("H:i \I\S\T j\<\s\u\p\>S\<\/\s\u\p\> M Y", filemtime('../statistics/facility.html')) . '</div>';
		echo '<br>';
		?>
		<h4><?php echo date("F");?>'s Controlling hours</h4><br>
		<?php require_once('../statistics/total.html');
		echo '<div class="text-right">Last updated: ' . date("H:i \I\S\T j\<\s\u\p\>S\<\/\s\u\p\> M Y", filemtime('../statistics/total.html')) . '</div>';
		echo '<br>';
		 ?>
<script>
		 $(document).ready(function() {
		     $('#table').DataTable( {
		         "scrollX": true
		     } );
		 } );
</script>

<?php
require_once("../includes/footer.php");
