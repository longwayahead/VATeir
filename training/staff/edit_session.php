<?php
$pagetitle = "Edit a Session";
require_once("../includes/header.php");
if(!$user->hasPermission('tdstaff')) {
	Session::flash('error', 'Invalid permissions.');
	Redirect::to('../index.php');
}
if(Input::exists('post')) {
	 	 try{
			 $from = new DateTime(Input::get('from'));
			 $start = Input::get('date') . ' ' . $from->format("H:i:s");
			 $until = new DateTime(Input::get('until'));
			 $finish = Input::get('date') . ' ' . $until->format("H:i:s");
		  	$validate = new Validate;
		  	$validation = $validate->check($_POST, array(
		  		'type' => array(
		  			'field_name' => 'Report Type',
		  			'required' => true
		  			),
		  		'position' => array(
		  			'field_name' => 'Position',
		  			'required' => true
		  			),
          'mentor' => array(
            'field_name' => 'Mentor Name',
            'required' => true
          ),
		  		'from' => array(
		  			'field_name' => 'Time From',
		  			'required' => true,
		  			'time_less' => 'until',
		  			'time_same' => 'until'
		  			),
		  		'until' => array(
		  			'field_name' => 'Time Until',
		  			'required' => true
		  			)
		  		));
		  	if($validation->passed()) {

				  	// echo Input::get('position') . '<br>';
				  	// echo Input::get('type') . '<br>';
				  	// echo $start . '<br>';
				  	// echo $finish . '<br>';
				  	// echo Input::get('id') . '<br>';
			  		if(Input::get('comment')) {
			  			$comment = Input::get('comment');
			  		} else {
			  			$comment = null;
			  		}
					  $s->edit(array(
                'mentor' => Input::get('mentor'),
				        'position_id'  => Input::get('position'),
				        'report_type'  => Input::get('type'),
				        'start'  => $start,
				        'finish'  => $finish,
				        'comment' => $comment,
				      ), [['id', '=', Input::get('id')]]);

			     	Session::flash('success', 'Session edited.');
			    	Redirect::to('./');


	    } else {
			echo '<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-danger">
				  <div class="panel-heading">
				    <h3 class="panel-title">The following errors occured:</h3>
				  </div>
				  <div class="panel-body">';
				    foreach($validation->errors() as $error) {
						echo $error.'<br>';
					}
				  echo '</div>
				</div>
			</div></div>
			';

		}
  } catch(Exception $e) {
    echo $e->getMessage();
  }
}
$session = $s->get([
		'id' => Input::get('id'),
    'future' => 1,
    'deleted' => 0
	])[0];
// print_r($session) . '<br><br>';
// echo $session->program_permissions . '<br><br>';
// if($user->hasPermission($session->program_permissions)) {
// 	echo 'huzzah';
// } else {
// 	echo 'no';
// }
if(!$user->hasPermission('tdstaff')) {
	Session::flash('error', 'Invalid permissions');
	Redirect::to('./');
}
?>
<h3 class="text-center">Edit a session</h3><br>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Edit</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" action="" method="post" onsubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').value='Editing...';">
				  <fieldset>
				    <div class="form-group">
				      <label for="name" class="col-lg-2 control-label">Student Name</label>
				      <div class="col-lg-7">
				        <input class="form-control" id="name" disabled="" value="<?php echo $session->sfname . ' ' . $session->slname;?>" type="text">
				      </div>
				    </div>
				    <div class="form-group">
				      <label for="mentor" class="col-lg-2 control-label">Mentor Name</label>
				      <div class="col-lg-7">
				        <select name="mentor" id="mentor" class="form-control tick" required>
                  <?php
                    $mentors = $t->getMentors();
                    foreach($mentors as $mentor) {
                      echo '<option value="' . $mentor->cid . '"';
                      if((!Input::exists() && $mentor->cid == $session->mentor) || (Input::exists() && $mentor->cid == Input::get('mentor'))) {
                        echo 'selected';
                      }
                      echo '>' . $mentor->first_name . ' ' .$mentor->last_name . '&nbsp;(' . $mentor->name .')</option>';
                    }

                  ?>
                </select>
				      </div>
				    </div>
				    <div class="form-group">
				      <label for="date" class="col-lg-2 control-label">Date</label>
				      <div class="col-lg-7">
				        <input type="date" class="form-control" id="date" disabled="" value="<?php echo date("Y-m-d", strtotime($session->start));?>" type="text">
				      </div>
				    </div>
				    <div class="form-group">
				      <label for="type" class="col-lg-2 control-label">Report Type</label>
				      <div class="col-lg-7">
				        <select name="type" id="type" class="form-control tick" required>
							<?php
								try {
									$types = $r->getTypes(0, ['program' => $session->program_id]);
									if(count($types)) {
										$programs = array();
										foreach($types as $type){
											echo '<option value="' . $type->report_type_id . '"';
												if((!Input::exists() && $type->report_type_id == $session->report_type_id) || (Input::exists() && $type->report_type_id == Input::get('type'))) {
													echo ' selected ';
												}
											echo '>' . $type->ident . ': ' . $type->session_type_name . '</option>';
										}
									}
								} catch(Exception $e) {
									echo '<option>' . $e->getMessage . '</option>';
								}
							?>
						</select>
				      </div>
				    </div>
				    <div class="form-group">
				<label for="select" class="col-lg-2 control-label">Position</label>
				<div class="col-lg-7">
					<select name="position" class="form-control tick" id="select" required>
						<option value="">Select Position</option>
						<?php
							$positions = $r->getPositions($session->program_id);
							foreach($positions as $position) {
								echo '<option value="' . $position->position_id . '"';
									if((!Input::exists() && $position->position_id == $session->position_id) || (Input::exists() && Input::get('position') == $position->position_id)) {
										echo ' selected';
									}
								echo '>' . $position->callsign .'</option>';
							}
						?>
					</select>
				</div>
			</div>
					<div class="form-group">
						<label for="from" class="col-lg-2 control-label">Time From</label>
						<div class="row">
							<div class="col-md-3">
								<div class='input-group date' id='datetimepicker1'>
									<input type="text" name="from" class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-time"></span>
									</span>
								</div>
								</div>
								<div class="col-md-1">
								to
								</div>
								<div class="col-md-3">
									<div class='input-group date' id='datetimepicker2'>
									<input type="text" name="until" class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-time"></span>
									</span>
								</div>
							</div>
						</div>
					</div>
					<span class="help-block text-center">Times are in 24h IST.</span>
					<div class="form-group">
						<label for="comment" class="col-lg-2 control-label">Comment</label>
						<div class="col-lg-10">
							<textarea class="form-control" rows="3" id="comment" name="comment"><?php echo $session->comment; ?></textarea>
						</div>
					</div>
				    <div class="form-group">
				      <div class="col-lg-6 col-lg-offset-3">
				      <input type="hidden" name="id" value="<?php echo Input::get('id');?>">
				      <input type="hidden" name="date" value="<?php echo date("Y-m-d", strtotime($session->start));?>">

				        <button type="submit" name="edit" class="btn btn-primary">Edit Session</button>

				      </div>
				    </div>
				  </fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
</div>


<?php
require_once("../../includes/footer.php");
$a= new Availability;
$av = $a->get(['session_id' => $session->session_id])[0];
$min= $av->date . ' ' . $av->time_from;
$max = $av->date . ' ' . $av->time_until;

if(!Input::exists()) {
	$f = $session->start;
	$u = $session->finish;

} else {
	$f = $start;
	$u = $finish;
}
?>
<script>
$('#cancel').click(function(e){
		event.preventDefault();
    var c = confirm('Are you sure you would like to cancel this session?');
		if (c == true) {
			$('#cancel').addClass('disabled');
			$('#cancel').click(false);
			window.location = $(this).attr('href');
		}

});
</script>

<script>
$(function () {
    $('#datetimepicker1').datetimepicker({
      format: 'HH:mm',
      stepping: '15',
			useCurrent: false,
      defaultDate: '<?php echo $f;?>',
      minDate: '<?php echo $min?>',
      maxDate: '<?php echo $max;?>'
    });
});
$(function () {
    $('#datetimepicker2').datetimepicker({
      format: 'HH:mm',
      stepping: '15',
			useCurrent: false,
      defaultDate: '<?php echo $u;?>',
      minDate: '<?php echo $min;?>',
      maxDate: '<?php echo $max;?>'
    });
});
</script>
