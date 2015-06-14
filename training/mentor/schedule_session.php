<?php
require_once("../includes/header.php");
$a = new Availability;
if(Input::exists('post')) {

 	 try{
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
	  		'from' => array(
	  			'field_name' => 'Time From',
	  			'required' => true,
	  			'time_less' => 'until'
	  			),
	  		'until' => array(
	  			'field_name' => 'Time Until',
	  			'required' => true
	  			)
	  		));
	  	if($validation->passed()) {
	  		$from = new DateTime(Input::get('from'));
	  		$start = $from->format("Y-m-d H-i-s");
	  		$until = new DateTime(Input::get('until'));
	  		$finish = $until->format("Y-m-d H-i-s");
		  $s->add(array(
	        'student'   => Input::get('student'),
	        'mentor'  => $user->data()->id,
	        'position_id'  => Input::get('position'),
	        'report_type'  => Input::get('type'),
	        'start'  => $start,
	        'finish'  => $finish
	      ));
	      $a->edit([
	      		'deleted' => 1
	      	],
	      		[['id', '=', Input::get('availability_id')]]
	      	);
	     	Session::flash('success', 'Session added.');
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
$availability = $a->get([
		'id' => Input::get('id')
	])[0];
if(!$user->hasPermission('mentor') || !$user->hasPermission($availability->permissions)) {
			Session::flash('error', 'Cannot mentor at that level');
			Redirect::to('./');
		}
?>
<h3 class="text-center">Schedule a session</h3><br>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Schedule</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" action="" method="post">
				  <fieldset>
				    <div class="form-group">
				      <label for="name" class="col-lg-2 control-label">Student Name</label>
				      <div class="col-lg-10">
				        <input class="form-control" id="name" disabled="" value="<?php echo $availability->first_name . ' ' . $availability->last_name;?>" type="text">
				      	<input type="hidden" name="student" value="<?php echo $availability->cid;?>">
				      </div>
				    </div>
				    <div class="form-group">
				      <label for="date" class="col-lg-2 control-label">Date</label>
				      <div class="col-lg-10">
				        <input type="date" class="form-control" id="date" disabled="" value="<?php echo date("Y-m-d", strtotime($availability->date));?>" type="text">
				      </div>
				    </div>
				    <div class="form-group">
				      <label for="type" class="col-lg-2 control-label">Report Type</label>
				      <div class="col-lg-7">
				        <select name="type" id="type" class="form-control tick" required>
							<option value="">Select Type</option>
							<?php
								try {
									$types = $r->getTypes(0, ['program' => $availability->program]);
									if(count($types)) {
										$programs = array();
										foreach($types as $type){
											if(!in_array($type->pid, $programs)) {
												$programs[] = $type->pid;
													echo '<option class="select-dash" disabled="disabled">----</option>';
												}
											echo '<option value="' . $type->report_type_id . '">' . $type->ident . ': ' . $type->session_type_name . '</option>';
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
							$positions = $r->getPositions($availability->program);
							foreach($positions as $position) {
								echo '<option value="' . $position->position_id . '"';
									if($position->position_id == Input::get("position")) {
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
					<br>			    
				    <div class="form-group">
				      <div class="col-lg-4 col-lg-offset-4">
				      <input type="hidden" name="availability_id" value="<?php echo Input::get('id');?>">
				        <button type="submit" class="btn btn-primary">Submit</button>
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
$from = new DateTime($availability->date . 'T' .$availability->time_from);
$until = new DateTime($availability->date . 'T' .$availability->time_until);
echo $f = $from->format("Y-m-d H:i:s");
echo $u = $until->format("Y-m-d H:i:s");
?>
<script>
$(function () {
    $('#datetimepicker1').datetimepicker({
      format: 'HH:mm',
      stepping: '60',
      defaultDate: '<?php echo $f;?>',
      minDate: '<?php echo $f;?>',
      maxDate: '<?php echo $u;?>'
    });
});
$(function () {
    $('#datetimepicker2').datetimepicker({
      format: 'HH:mm',
      stepping: '60',
      defaultDate: '<?php echo $u;?>',
      minDate: '<?php echo $f;?>',
      maxDate: '<?php echo $u;?>'
    });
});
</script>