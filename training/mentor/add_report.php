<?php
$pagetitle = "Add Report";
require_once('../includes/header.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/8.5.1/nouislider.min.css">
<?php

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
				'report_type_id'	=> Input::get('type'),
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

			$sess = $s->edit(['report_id' => $reportID], [['id', '=', Input::get('s')]]);

			$r->addCard(array(
				'cid'		=> Input::get("cid"),
				'card_type'	=> 0, //For session. 1 = note.
				'link_id'	=> $reportID,
				'submitted'	=> date('Y-m-d H:i:s')
			));

			if(isset($_POST['pass'])) {
				if(!$n->exists(2, Input::get('cid'))) {

					$rat = Input::get('rating');
					if($rat > 0 && $rat < 5) {
						$rating = $t->getRating(++$rat);
					}

					$id = $n->add(array(
							'type' 		=> 2,
							'from' 		=> Input::get('cid'),
							'to_type' 	=> 1,
							'to' 		=> 3,
							'submitted' => date("Y-m-d H:i:s"),
							'status'	=> 0
						));

					$comment = $n->addComment(array(
							'notification_id'	=> $id,
							'submitted'			=> date("Y-m-d H:i:s"),
							'submitted_by'		=> 0,
							'text'				=>
							'<p>Exam passed. Student requires upgrade.</p><p><strong>Next Rating:</strong>' . $rating->long . ' (' . $rating->short . ')<br><strong>Report: </strong><a target="_blank" class="btn btn-xs btn-default" href="' . BASE_URL . 'training/mentor/view_student.php?cid=' . Input::get('cid') . '#r' . $reportID . '">View</a><br><strong>Upgrade Link: </strong><a target="_blank" class="btn btn-xs btn-primary" href="https://www.atsimtest.com/index.php?cmd=admin&sub=memberdetail&memberid=' . Input::get('cid') . '">ATSimTest</a></p>'
							));
				}
			}


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
		$sliders = $r->getSliders(1, $session->report_type);

		if(!count($type)) {
			Session::flash('error', 'No report type found for that ID');
			Redirect::to('./view_student.php?cid=' . $session->student);
		}

		if(!$user->hasPermission($session->program_permissions)) {
			Session::flash('error', 'Insufficient permissions');
			Redirect::to('./');
		}
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	?>
<div class="col-md-10 col-md-offset-1 well">
	<form id="thisForm" class="form-horizontal" method="post" action="add_report.php" onsubmit="document.getElementById('submit').disabled=true;document.getElementById('cancel').disabled=true;document.getElementById('noshow').disabled=true; document.getElementById('submit').value='Submitting...';">
        <fieldset>
          <legend>Add Report</legend>
          	<div class="form-group">
				<label for="studentname" class="col-lg-3 control-label">Student Name</label>
				<div class="col-lg-4">
					<input class="form-control" type="text" id="studentname" placeholder="<?php echo $session->sfname . ' ' . $session->slname;?>" readonly>
				</div>
			</div>
			<div class="form-group">
		      <label for="type" class="col-lg-3 control-label">Report Type</label>
		      <div class="col-lg-4">
		        <select name="type" id="type" class="form-control tick" readonly>
					<?php
						try {
							$types = $r->getTypes(0, ['program' => $session->program_id]);
							if(count($types)) {
								$programs = array();
								foreach($types as $type){
									echo '<option value="' . $type->report_type_id . '"';
									echo ($type->report_type_id == $session->report_type) ? ' selected' : '';
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
				$sliderCat = [];
				foreach($sliders as $slider) {
					if(!in_array($slider->category, $sliderCat)) {
						$sliderCat[] = $slider->category;

						echo '<p class="text-center">';
						if(count($sliderCat) > 1) {
							echo '<br><br>';
						}
						echo '<h6 class="text-center">';
						echo $slider->name . '</h6></p>';
					}
					echo '<div class="form-group">
							<label for="slider' . $slider->sid . '" class="col-lg-3 control-label">' . $slider->text . '</label>
							';
					if($slider->type == 0) { //is a slider
						echo '
						<div class="col-md-6">
							<div class="slider slider-material-blue" id="slider' . $slider->sid . '"></div>
								<input type="hidden" id="slider' . $slider->sid . '-value" name="slider[' . $slider->sid . ']"></input>
							</div>
							<div class="col-lg-1">
								<div id="slider' . $slider->sid . '-value-text" style="display: inline"></div>
							</div>


						';
					} elseif($slider->type == 1) { //is a radio button
						echo '<div class="col-md-6">

								<div class="radio">

										<label>
											<input type="radio" name=slider[' . $slider->sid . '] value="0"';
											if(!Input::exists() || Input::get('slider')[$slider->sid] == 0) {
												echo ' checked';
											}
											echo '>
											N/A
										</label>

										&nbsp;&nbsp;&nbsp;&nbsp;

										<label>
											<input type="radio" name=slider[' . $slider->sid . '] value="1"';
											if(Input::exists() && Input::get('slider')[$slider->sid] == 1) {
												echo ' checked';
											}
											echo '>
											Poor
										</label>

										&nbsp;&nbsp;&nbsp;&nbsp;

										<label>
											<input type="radio" name=slider[' . $slider->sid . '] value="2"';
											if(Input::exists() && Input::get('slider')[$slider->sid] == 2) {
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
				<div class="col-md-6 col-md-offset-3"><p class="forum-control-static text-danger" stye="font-size:16px;">No Syllabus Data</p></div>
				</div>';
			}
			?>
			<br><br><br>
			<div class="form-group">
				<label for="text" class="col-lg-3 control-label">Report Text</label>
				<div class="col-lg-8">
					<?php
						require_once(URL . 'scribe/box.php');
					?>
					<div class="scribe" class="form-control"><?php echo Input::get('text'); ?></div>
					<input type="hidden" name="text" class="scribe-html" value="<?php echo htmlentities(Input::get('text')); ?>">
					<!-- <textarea name="report" class="form-control" rows="3" id="textArea" required><?php //echo Input::get('report');?></textarea>
					<span class="help-block">This field supports <a target="_blank" href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Markdown</a>!.</span> -->
				</div>
			</div>
			<?php if($session->isexam == 1) : ?>
				<div class="form-group">
					<label for="pass" class="col-lg-3 control-label">Pass</label>
					<div class="col-lg-2">
						<div class="checkbox">
							<label>
								<input name="pass" style="margin-left:5px;" value="1" type="checkbox" class="checkbox check" id="pass">
							</label>
						</div>
						<span class="help-block">Exam passed.</span>
					</div>
				</div>
			<?php endif; ?>
			<div class="row form-group text-center">
				<div class="col-lg-10 col-md-offset-1">
				<input type="hidden" name="cid" value="<?php echo $session->student;?>">
				<input type="hidden" name="report_type" value="<?php echo $session->report_type;?>">
				<input type="hidden" name="rating" value="<?php echo $session->rating;?>">
				<input type="hidden" name="s" value="<?php echo Input::get('s');?>">
				<button id="submit" type="submit" name="submit" class="btn btn-primary">Submit!</button>
			</div>
			<div class-"row col-lg-10 col-md-offset-1">
				<br>
				<br>
				<br>
				<br>
				<a id="noshow" href="noshow_session.php?id=<?php echo Input::get('s');?>" class="btn btn-danger">No Show</a>
				<a id="cancel" href="cancel_session.php?id=<?php echo Input::get('s');?>&e=0" class="btn btn-warning">Cancelled</a>

				</div>
			</div>
		</fieldset>
	</form>
</div>


<?php

echo '</div>';
require_once("../../includes/footer.php");
?>
<script src=<?php echo BASE_URL . "js/jquery.nouislider.all.min.js";?>></script>

<script>
	<?php
	if($sliders) {
		foreach($sliders as $slider) {
			if($slider->type == 0) {
				echo '$("#slider' . $slider->sid . '").noUiSlider({
						start: [';
						if(Input::get("slider")) {
							echo Input::get("slider")[$slider->sid];
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
					$("#slider' . $slider->sid . '").Link("lower").to($("#slider' . $slider->sid . '-value"));
					$("#slider' . $slider->sid . '").Link("lower").to($("#slider' . $slider->sid . '-value-text"));';
			}
		}
	}
	?>

</script>
<script src="../../scribe/bower_components/requirejs/require.js" data-main="../../scribe/setup.js"></script>

<script>
$('#cancel').click(function(e){
		event.preventDefault();
    var c = confirm('Are you sure you would like to cancel this session?');
		if (c == true) {
			$('#cancel').addClass('disabled');
			$('#noshow').addClass('disabled');
			$('#submit').addClass('disabled');
			$('#cancel').click(false);
			$('#noshow').click(false);
			$('#submit').click(false);
			window.location = $(this).attr('href');
		}

});
</script>
<script>
$('#noshow').click(function(e){
		event.preventDefault();
    var c = confirm('Are you sure you would like to mark this session as a No Show?');
		if (c == true) {
			$('#cancel').addClass('disabled');
			$('#noshow').addClass('disabled');
			$('#submit').addClass('disabled');
			$('#cancel').click(false);
			$('#noshow').click(false);
			$('#submit').click(false);
			window.location = $(this).attr('href');
		}

});
</script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/8.5.1/nouislider.min.js"></script> -->

<?php
