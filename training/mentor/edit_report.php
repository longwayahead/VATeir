<?php
$pagetitle = "Edit Report";
require_once('../includes/header.php');

if(isset($_GET['id'])) {
	try {
		$report = $r->getReport(1, $_GET['id']);
		//print_r($report);
		if(!$user->hasPermission('mentor') || !$user->hasPermission($report->permissions)) {
			Session::flash('error', 'Insufficient permissions');
			Redirect::to('./');
		}
		$positions = $r->getPositionsByProgram($report->program_id);
		$sliders = $r->getSliders(2, $report->rep_id);
		$allSliders = $r->getSliders(1, $report->program_id);
		$answers = array();
		if($sliders) {
			
			foreach($sliders as $s) {
			//	if($s->value) {
					echo 'no';
					$answers[$s->slider_id] = $s->value;
				//}
				
			}
			var_dump($answers);
		}

		
		if(Input::exists()) { //if form submitted!
			
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'report_type' => array(
					'field_name' => 'Report Type',
					'required' => true
				),
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
				)
			));

			if($validation->passed()) {
				try {
					$r->updateReport(array(
						'report_type_id'	=> Input::get('report_type'),
						'position_id'		=> Input::get('position'),
						'session_date'		=> Input::get('date'),
						'text'				=> Input::get('text')
					), [['id', '=', $_GET['id']]]);

					if(Input::get('slider')) {
						foreach(Input::get('slider') as $slideID => $slideVal) {
							$r->deleteSliderAnswer([['slider_id', '=', $slideID], ['report_id', '=', $_GET['id']]]);
							if($slideVal > 0) {
								$r->addSliderAnswers(array(
								'report_id'	=> $_GET['id'],
								'slider_id' => $slideID,
								'value'		=> $slideVal
							));
							}
						}				
					}

				
					
					Session::flash('success', 'Report Edited');
					Redirect::to('./view_student.php?cid=' . Input::get("cid") . '#r' . $report->rep_id);

						
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
		 }
	} catch(Exception $e) {
		echo $e->getMessage();
	}


		?>



	<div class="col-md-10 col-md-offset-1 well">
		<form class="form-horizontal" method="post" action="">
	        <fieldset>
	          <legend>Edit Report</legend>
	          	<div class="form-group">
					<label for="studentname" class="col-lg-3 control-label">Student Name</label>
					<div class="col-lg-4">
						<input class="form-control" type="text" id="studentname" placeholder="<?php echo $report->sfname . ' ' . $report->slname;?>" readonly>
					</div>
				</div>
				<!-- <div class="form-group">
			      <label for="type" class="col-lg-3 control-label">Report Type</label>
			      <div class="col-lg-4">
			        <select name="report_type type" id="type" class="form-control tick" required>
						<?php
							// try {
								
							// 	if(count($types)) {
							// 		$programs = array();
							// 		foreach($types as $type){
							// 			echo '<option value="' . $type->report_type_id . '"';
							// 			echo ($type->report_type_id == $report->report_type_id) ? ' selected' : '';
							// 			echo '>' . $type->ident . ': ' . $type->session_type_name . '</option>';
							// 		}
							// 	}
							// } catch(Exception $e) {
							// 	echo '<option>' . $e->getMessage . '</option>';
							// }
						?>
					</select>
			      </div>
			    </div> -->
				<div class="form-group">
					<label for="programname" class="col-lg-3 control-label">Report Type</label>
					<div class="col-lg-4">
						<select class="form-control tick" name="report_type">
							<?php
							$types = $r->getTypes(0, ['program' => $report->program_id]);
							$programs = array();
							if(!Input::exists('post')) {
								$r = $report->typ_id;
							} else {
								$r = Input::get('report_type');
							}

							foreach($types as $type) {
								// if(!in_array($type->pid, $programs)) {
								// 	$programs[] = $type->pid;
								// 	echo '<option class="select-dash" disabled="disabled">----</option>';
								// }
								echo '<option value="' . $type->report_type_id . '"';
								if($type->report_type_id == $r) {
									echo ' selected';
								}
								echo '>' . $type->ident . ': ' . $type->session_type_name . '</option>';
							
							}

							?>
						</select>
					</div>
				</div>
	          	<div class="form-group">
					<label for="select" class="col-lg-3 control-label">Position</label>
					<div class="col-lg-4">
						<select name="position" class="form-control tick" id="select" required>
							<?php
							if(!Input::exists()) {
								$p = $report->pos_id;
							} else {
								$p = Input::get('position');
							}
								foreach($positions as $position) {
									echo '<option value="' . $position->position_id . '"';
										if($position->position_id == $p) {
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
						<input name="date" type="date" class="form-control" id="date" value="<?php echo(Input::get('date')) ? Input::get('date') : $report->session_date;  ?>" required></input>
					</div>
				</div>
				<br>
				<?php
				if($allSliders) {
					
					foreach($allSliders as $slider) {
						echo '<div class="form-group">
								<label for="slider' . $slider->id . '" class="col-lg-3 control-label">' . $slider->text . '</label>';
						if($slider->type == 0) {
						
							echo '
							
								<div class="col-md-6">
									<div class="slider slider-material-blue" id="slider' . $slider->id . '"></div>
										<input type="hidden" id="slider' . $slider->id . '-value" name="slider[' . $slider->id . ']"></input>
								
								</div>
								<div class="col-lg-1">
									<div id="slider' . $slider->id . '-value-text" style="display: inline"></div>
								</div>
						

							';
						} elseif($slider->type == 1) {
							
							echo '<div class="col-md-6">
								
								<div class="radio">
 

										<label>
											<input type="radio" name=slider[' . $slider->id . '] value="0"';
											if(!array_key_exists($slider->id, $answers) || $answers[$slider->id] == 0 || (Input::exists() && Input::get('slider')[$slider->id] == 0)) {
												echo ' checked';
											}
											echo '>
											N/A
										</label>
								
										&nbsp;&nbsp;&nbsp;&nbsp;
									
										<label>
											<input type="radio" name=slider[' . $slider->id . '] value="1"';
											if((array_key_exists($slider->id, $answers) && $answers[$slider->id] == 1) || (Input::exists() && Input::get('slider')[$slider->id] == 1)) {
												echo ' checked';
											}
											echo '>
											Poor
										</label>
									
										&nbsp;&nbsp;&nbsp;&nbsp;
									
										<label>
											<input type="radio" name=slider[' . $slider->id . '] value="2"';
											if(array_key_exists($slider->id, $answers) && ($answers[$slider->id] == 2) || (Input::exists() && Input::get('slider')[$slider->id] == 2)) {
												echo ' checked';
											}
											echo '>
											Grand
										</label>
									
								</div>
							</div>

								';


						}
						echo '</div>';
					}
				} else {
					echo '<div class="form-group">
					<div class="col-md-6 col-md-offset-3"><p class="forum-control-static text-danger" stye="font-size:16px;">No Breakdown Data</p></div>
					</div>';
				}
				?>
				<br>
				<div class="form-group">
					<label for="textArea" class="col-lg-3 control-label">Report Text</label>
					<div class="col-lg-8">
						<?php
							require_once(URL . 'scribe/box.php');
						?>
						<div class="scribe" class="form-control"><?php echo (!Input::exists()) ? $report->text : Input::get('text'); ?></div>
						<input type="hidden" name="text" class="scribe-html" value="<?php echo (!Input::exists()) ? $report->text : Input::get('text'); ?>">
						<!-- <textarea name="report" class="form-control" rows="3" id="textArea" required><?php //echo (!Input::exists()) ? $report->text : Input::get('report'); ?></textarea>
						<span class="help-block">This field supports <a target="_blank" href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Markdown</a>!.</span> -->
					</div>
				</div>
				<div class="form-group text-center">
					<div class="col-lg-10 col-md-offset-1">
					<input type="hidden" name="id" value="<?php echo Input::get('id');?>"></input>
					<input type="hidden" name="cid" value="<?php echo $report->student_cid;?>"></input>
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
		if($allSliders) {
			foreach($allSliders as $slider) {
				if($slider->type == 0) {
					echo '$("#slider' . $slider->id . '").noUiSlider({
							start: [';
							if(Input::get("slider")) {
								echo Input::get("slider")[$slider->id];
							} else {
								if(isset($answers[$slider->id])) {
									echo $answers[$slider->id];
								} else {
									echo '0';
								}
								
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
}
