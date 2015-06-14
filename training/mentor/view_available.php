<?php
$pagetitle = 'All Available Students';
require_once("../includes/header.php");
echo '<h3 class="text-center">All Availablities</h3><br>';
try {
	$a = new Availability;
	
	if(Input::get('cid')) {
		$availables = $a->get(['student' => Input::get('cid')]);
	} else {
		$availables = $a->get(['deleted' => 0]);
	}
	?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo (Input::get('cid')) ? 'Student\'s Availability' : 'All available students';?></h3>
			</div>
			<div class="panel-body" style="padding:0px;">
					<?php
      try {
        if(!empty($availables)) {
           ?>
           <table class="table table-condensed table-striped">
			<tr>
				<td>
					<strong>Name</strong>
				</td>
				<td>
					<strong>Programme</strong>
				</td>
				<td>
					<strong>Date</strong>
				</td>
				<td>
					<strong>Time From</strong>
				</td>
				<td>
					<strong>Time Until</strong>
				</td>
				<td>
					<strong>Book</strong>
				</td>
            </tr>
            <?php foreach($availables as $availability): ?>
              <tr>
              	<td><?php echo '<a href="view_student.php?cid=' . $availability->cid . '">' . $availability->first_name . ' ' . $availability->last_name . '</a>';?></td>
                <td><?php echo $availability->program_name;?></td>
                <td><?php echo date("j-M-y", strtotime($availability->date));?></td>
                <td><?php echo date("H:i", strtotime($availability->time_from));?></td>
                <td><?php echo date("H:i", strtotime($availability->time_until));?></td>
             	<td><?php echo '<a class="btn btn-xs btn-default" href="schedule_session.php?id=' . $availability->availability_id . '"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>' ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
          <?php
        } else {
          echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No availability</div><br>';
        }
      } catch(Exception $e) {
        echo $e->getMessage();
      }
      ?>
			</div>
		</div>
	</div>
</div>
<?php
} catch(Exception $e) {
	echo $e->getMessage();
}

echo '</div>';
require_once('../../includes/footer.php');