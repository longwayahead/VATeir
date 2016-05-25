<?php
$pagetitle = "Schedule a Session";
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
	  			'time_less' => 'until',
	  			'time_same' => 'until'
	  			),
	  		'until' => array(
	  			'field_name' => 'Time Until',
	  			'required' => true
	  			)
	  		));
	  	if($validation->passed()) {
	  		$from = new DateTime(Input::get('from'));
	  		$start = Input::get('date') . ' ' . $from->format("H:i:s");
	  		$until = new DateTime(Input::get('until'));
	  		$finish = Input::get('date') . ' ' . $until->format("H:i:s");
	  		if(Input::get('comment')) {
	  			$comment = Input::get('comment');
	  			$commentEmail = 'A comment was left with the above booking:<br><br><i>' . $comment . '</i><br><br>';
	  		} else {
	  			$comment = null;
	  		}
			  $s->add(array(
		        'student'   => Input::get('student'),
		        'mentor'  => $user->data()->id,
		        'position_id'  => Input::get('position'),
		        'report_type'  => Input::get('type'),
		        'start'  => $start,
		        'finish'  => $finish,
		        'comment' => $comment,
		      ));
		      $max_id = $s->max();
		      $sess = $s->get(['id' => $max_id])[0];
		      $a->edit([
		      		'deleted' => 1,
		      		'session_id' => $max_id,
		      	],
		      		[['id', '=', Input::get('id')]]
		      	);
		      	$name = $sess->sfname;
		      	$email = $sess->email;
		      	$mentorname = $sess->mfname . ' ' . $sess->mlname;
		      	$from = 'training';
		      	$subject = 'Session Booking: ' . $sess->callsign . ' on ' . date("j F, Y", strtotime(Input::get('date'))) . '';
		      	$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
<head>

<meta name="viewport" content="width=device-width" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Session Booked</title>


</head>

<body bgcolor="#FFFFFF" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; margin: 0; padding: 0;">


<table class="head-wrap" bgcolor="#999999" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
	<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
		<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
		<td class="header container" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">

				<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">
				<table bgcolor="#999999" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
					<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
						<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><img style="max-width: 200px; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" src="http://www.vateir.org/img/logo.png" /></td>
					</tr>
				</table>
				</div>

		</td>
		<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
	</tr>
</table>



<table class="body-wrap" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
	<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
		<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
		<td class="container" bgcolor="#FFFFFF" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">

			<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">
			<table style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
				<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
					<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
						<h3 style="font-family: \'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 27px; margin: 0 0 15px; padding: 0;">Hi ' . $name . ',</h3>
						<p class="lead" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 17px; line-height: 1.6; margin: 0 0 10px; padding: 0;">This is an automatically generated email from the VATeir Training Department.</p>
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">' . $mentorname . ' has scheduled a mentoring session with you on ' . $sess->position_name . '.</p>
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
							<table style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
								<strong style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Session Details</strong>
								<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										Date:
									</td>
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										' . date("j F, Y", strtotime(Input::get('date'))) . '
									</td>
								</tr>
								<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										Time:
									</td>
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										' . date("H:i", strtotime($start)) . '&ndash;' . date("H:i", strtotime($finish)) . ' (IST)
									</td>
								</tr>
								<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										Student:
									</td>
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										'. $sess->sfname .' ' . $sess->slname . '
									</td>
								</tr>
								<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										Mentor:
									</td>
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										'. $sess->mfname .' ' . $sess->mlname . '
									</td>
								</tr>
								<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										Programme:
									</td>
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										' . $sess->program_name . '
									</td>
								</tr>
								<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										Position:
									</td>
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										' . $sess->position_name . ' (' . $sess->callsign . ')
									</td>
								</tr>
								<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										Type:
									</td>
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
										' . $sess->session_name . '
									</td>
								</tr>
							</table>

						</p>

						<p class="callout" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; background-color: #ECF8FF; margin: 0 0 15px; padding: 15px;">
							' . $commentEmail . '
							View session: <a href="http://www.vateir.org/training/sessions.php#s' . $sess->session_id . '" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; font-weight: bold; margin: 0; padding: 0;">Click it! &raquo;</a>
						</p>
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">If your mentor has prescribed \'homework\' for you to do, please make sure to have it completed before your session.</p>
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">Good luck!</p>
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;"><i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">VATeir Training Department</i></p>
					</td>
				</tr>
			</table>
			</div>

		</td>
	</tr>
</table>


<table class="footer-wrap" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; clear: both !important; margin: 0; padding: 0;">
	<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
		<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
		<td class="container" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">


				<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">
				<table style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
				<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
					<td align="center" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
							<a href="http://www.vateir.org/privacy.php" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">Privacy</a>

						</p>
					</td>
				</tr>
			</table>
				</div>

		</td>
		<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
	</tr>
</table>


<style type="text/css">
img { max-width: 100% !important; }
body { -webkit-font-smoothing: antialiased !important; -webkit-text-size-adjust: none !important; width: 100% !important; height: 100% !important; }
</style>
</body>
</html>';
				require_once('../../email/send.php');
		     	Session::flash('success', 'Session added; student has been emailed.');
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
//print_r($availability);

if(!$user->hasPermission("$availability->permissions")) {
	Session::flash('error', 'Cannot mentor at that level');
	Redirect::to('./');
}
if($user->data()->id == $availability->cid) {
	Session::flash('error', 'You cannot mentor yourself!');
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
				<form class="form-horizontal" action="" method="post" onsubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').value='Submitting...';">
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
									$types = $r->getTypes(0, ['program' => $availability->program]); //by the program the student is set as
									if(count($types)) {
										$programs = array();
										foreach($types as $type){
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
					<span class="help-block text-center">Times are in 24h IST.</span>
					<div class="form-group">
						<label for="comment" class="col-lg-2 control-label">Comment</label>
						<div class="col-lg-10">
							<textarea class="form-control" rows="3" id="comment" name="comment" placeholder="To do before session"></textarea>
						</div>
					</div>
				    <div class="form-group">
				      <div class="col-lg-4 col-lg-offset-4">
				      <input type="hidden" name="id" value="<?php echo Input::get('id');?>">
				      <input type="hidden" name="date" value="<?php echo $availability->date; ?>">
				        <button type="submit" id="submit" class="btn btn-primary">Schedule</button>
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
      stepping: '15',
      defaultDate: '<?php echo $f;?>',
      minDate: '<?php echo $f;?>',
      maxDate: '<?php echo $u;?>'
    });
});
$(function () {
    $('#datetimepicker2').datetimepicker({
      format: 'HH:mm',
      stepping: '15',
      defaultDate: '<?php echo $u;?>',
      minDate: '<?php echo $f;?>',
      maxDate: '<?php echo $u;?>'
    });
});
</script>
