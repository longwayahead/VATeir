<?php
require_once('../includes/header.php');


if(Input::exists()) { //if form submitted!
	// echo Input::get('position');
	// die();
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'position' => array(
			'field_name' => 'Position',
			'required' => true
		),
		'date' => array(
			'field_name' => 'Session Date',
			'required' => true
		),
		'text' => array(
			'field_name' => 'Report Text',
			'required' => true
		),
		's' => array(
			'field_name' => 'Session ID',
			'required' => true
		)
	));

	if($validation->passed()) {
		try {
		
			$r->addReport(array(
				'student_cid'		=> Input::get('cid'),
				'mentor_cid'		=> $user->data()->id,
				'report_type_id'	=> Input::get('report_type'),
				'position_id'		=> Input::get('position'),
				'submitted_date'	=> date("Y-m-d"),
				'session_date'		=> Input::get('date'),
				'text'				=> Input::get('text')
			));

			$reportID = $r->getLatestID("reports");

			if(Input::get('slider')) {
				foreach(Input::get('slider') as $slideID => $slideVal) {
					if($slideVal > 0) {
						$r->addSliderAnswers(array(
							'report_id'	=> $reportID,
							'slider_id' => $slideID,
							'value'		=> $slideVal
						));
					}
				}				
			}

			$s = new Sessions;
			$sess = $s->edit(['report_id' => $reportID], [['id', '=', Input::get('s')]]);

			$r->addCard(array(
				'cid'		=> Input::get("cid"),
				'card_type'	=> 0, //For session. 1 = note.
				'link_id'	=> $reportID,
				'submitted'	=> date('Y-m-d H:i:s')
			));
			
			Session::flash('success', 'Report Added');
			Redirect::to('./view_student.php?cid=' . Input::get('cid') . '#r' . $reportID);

				
		} catch (Exception $e) {
			die($e->getMessage());
		}
	} else {
		echo '
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
		</div>
		';
			
	}
 }// else {

	try {
		$s = new Sessions;
		$session = $s->get(['id' => Input::get('s')])[0];
		$type = $r->getTypes(0, ['type' => $session->report_type])[0];
		$positions = $r->getPositionsByProgram($type->program_id); //get all positions for this program level
		$sliders = $r->getSliders(1, $type->program_id);
		
		if(!count($type)) {
			Session::flash('error', 'No report type found for that ID');
			Redirect::to('./view_student.php?cid=' . $session->student);
		}

		if(!$user->hasPermission('mentor') || !$user->hasPermission($student->program_permissions)) {
			Session::flash('error', 'Insufficient permissions');
			Redirect::to('./mentor/');
		}
	} catch (Exception $e) { 
		echo $e->getMessage();
	}
	?>
<div class="col-md-10 col-md-offset-1 well">
	<form class="form-horizontal" method="post" action="add_report.php">
        <fieldset>
          <legend>Add Report</legend>
          	<div class="form-group">
				<label for="studentname" class="col-lg-3 control-label">Student Name</label>
				<div class="col-lg-4">
					<input class="form-control" type="text" id="studentname" placeholder="<?php echo $session->sfname . ' ' . $session->slname;?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label for="programname" class="col-lg-3 control-label">Report Type</label>
				<div class="col-lg-4">
					<input class="form-control" type="text" id="programname" placeholder="<?php echo $session->program_name;?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label for="sessionType" class="col-lg-3 control-label">Session Type</label>
				<div class="col-lg-4">
					<input class="form-control" type="text" id="sessionType" placeholder="<?php echo $session->session_name;?>" readonly>
				</div>
			</div>
          	<div class="form-group">
				<label for="select" class="col-lg-3 control-label">Position</label>
				<div class="col-lg-4">
					<select name="position" class="form-control tick" id="select" required>
					<option>Select</option>
						<?php
							foreach($positions as $position) {
								echo '<option value="' . $position->position_id . '"';
									if(($position->position_id == Input::get("position")) || ($position->position_id == $session->position_id)) {
										echo ' selected';
									}
								echo '>' . $position->callsign .'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="date" class="col-lg-3 control-label">Session Date</label>
				<div class="col-lg-4">
					<input name="date" type="date" class="form-control" id="date" value="<?php echo(Input::get('date')) ? Input::get('date') : date("Y-m-d", strtotime($session->start));  ?>" required readonly></input>
				</div>
			</div>
			<br>
			<?php
			if($sliders) {
				foreach($sliders as $slider) {
					echo '<div class="form-group">
							<label for="slider' . $slider->id . '" class="col-lg-3 control-label">' . $slider->text . '</label>
							';
					if($slider->type == 0) { //is a slider
						echo '
						<div class="col-md-6">
							<div class="slider slider-material-blue" id="slider' . $slider->id . '"></div>
								<input type="hidden" id="slider' . $slider->id . '-value" name="slider[' . $slider->id . ']"></input>
							</div>
							<div class="col-lg-1">
								<div id="slider' . $slider->id . '-value-text" style="display: inline"></div>
							</div>
						

						';
					} elseif($slider->type == 1) { //is a radio button
						echo '<div class="col-md-6">
								
								<div class="radio">
									
										<label>
											<input type="radio" name=slider[' . $slider->id . '] value="0"';
											if(!Input::exists() || Input::get('slider')[$slider->id] == 0) {
												echo ' checked';
											}
											echo '>
											N/A
										</label>
								
										&nbsp;&nbsp;&nbsp;&nbsp;
									
										<label>
											<input type="radio" name=slider[' . $slider->id . '] value="1"';
											if(Input::exists() && Input::get('slider')[$slider->id] == 1) {
												echo ' checked';
											}
											echo '>
											Poor
										</label>
									
										&nbsp;&nbsp;&nbsp;&nbsp;
									
										<label>
											<input type="radio" name=slider[' . $slider->id . '] value="2"';
											if(Input::exists() && Input::get('slider')[$slider->id] == 2) {
												echo ' checked';
											}
											echo '>
											Grand
										</label>
									
								</div>
							</div>

								';
					}
					echo '
						</div>
						';
				}
			} else {
				echo '<div class="form-group">
				<div class="col-md-6 col-md-offset-3"><p class="forum-control-static text-danger" stye="font-size:16px;">No Breakdown Options</p></div>
				</div>';
			}
			?>
			<br>
			<div class="form-group">
				<label for="text" class="col-lg-3 control-label">Report Text</label>
				<div class="col-lg-8">
					<?php
						require_once(URL . 'scribe/box.php');
					?>
					<div class="scribe" class="form-control"><?php echo Input::get('text'); ?></div>
					<input type="hidden" name="text" class="scribe-html" value="<?php echo Input::get('text'); ?>">
					<!-- <textarea name="report" class="form-control" rows="3" id="textArea" required><?php //echo Input::get('report');?></textarea>
					<span class="help-block">This field supports <a target="_blank" href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Markdown</a>!.</span> -->
				</div>
			</div>
			<div class="form-group text-center">
				<div class="col-lg-10 col-md-offset-1">
				<input type="hidden" name="cid" value="<?php echo $session->student;?>">
				<input type="hidden" name="report_type" value="<?php echo $session->report_type;?>">
				<input type="hidden" name="s" value="<?php echo Input::get('s');?>">
				<button type="submit" name="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</fieldset>
	</form>
</div>


<?php

echo '</div>';
require_once("../../includes/footer.php");
?>
<script>
	<?php
	if($sliders) {
		foreach($sliders as $slider) {
			if($slider->type == 0) {
				echo '$("#slider' . $slider->id . '").noUiSlider({
						start: [';
						if(Input::get("slider")) {
							echo Input::get("slider")[$slider->id];
						} else {
							echo '0';
						}
						echo '],
						step: 1,
						range: {
							"min": [ 0 ],
							"max": [ 10 ]
						},
						format: wNumb({
							decimals: 0
						})
					});
					$("#slider' . $slider->id . '").Link("lower").to($("#slider' . $slider->id . '-value"));
					$("#slider' . $slider->id . '").Link("lower").to($("#slider' . $slider->id . '-value-text"));';
			}		
		}
	}
	?>

</script>
<script src="../../scribe/bower_components/requirejs/require.js" data-main="../../scribe/setup.js"></script>

   
  

<?php
// }