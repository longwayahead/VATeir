<?php
$pagetitle = "Edit Report";
require_once('../includes/header.php');
$f = new Files;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/8.5.1/nouislider.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<?php
if(isset($_GET['id'])) {
	try {
		$report = $r->getReport(1, $_GET['id']);
		if($report->files > 0) {

			$files = $f->get($_GET['id']);
		}

		// print_r($report);
		// echo $report->files;

		if(!$user->hasPermission('mentor') || !$user->hasPermission($report->permissions)) {
			Session::flash('error', 'Insufficient permissions');
			Redirect::to('./');
		}
		$positions = $r->getPositionsByProgram($report->program_id);
		$sliders = $r->getSliders(2, $report->rep_id);
		$allSliders = $r->getSliders(1, $report->typ_id);
		$answers = array();
		if($sliders) {

			foreach($sliders as $s) {
				if($s->value) {
					//echo 'no';
					$answers[$s->slider_id] = $s->value;
				}

			}
			//var_dump($answers);
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

					if(isset($_FILES['upload'])) {
						$status = $f->upload($_FILES['upload'], $_GET['id'], $user->data()->id, $_SERVER['REMOTE_ADDR']);
					}

					if(Input::get('fileDelete')) {
						foreach(Input::get('fileDelete') as $fileID => $on) {
							$deleteFile = $f->remove($fileID);
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
		<form class="form-horizontal" method="post" action=""  onsubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').value='Editing...';" enctype="multipart/form-data">
	        <fieldset>
	          <legend>Edit Report</legend>
	          	<div class="form-group">
					<label for="studentname" class="col-lg-3 control-label">Student Name</label>
					<div class="col-lg-4">
						<input class="form-control" type="text" id="studentname" placeholder="<?php echo $report->sfname . ' ' . $report->slname;?>" readonly>
					</div>
				</div>
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
					$sliderCat = [];
					foreach($allSliders as $slider) {
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
								<label for="slider' . $slider->sid . '" class="col-lg-3 control-label">' . $slider->text . '</label>';
						if($slider->type == 0) {

							echo '

								<div class="col-md-6">
									<div class="slider slider-material-blue" id="slider' . $slider->sid . '"></div>
										<input type="hidden" id="slider' . $slider->sid . '-value" name="slider[' . $slider->sid . ']"></input>

								</div>
								<div class="col-lg-1">
									<div id="slider' . $slider->sid . '-value-text" style="display: inline"></div>
								</div>


							';
						} elseif($slider->type == 1) {

							echo '<div class="col-md-6">

								<div class="radio">


										<label>
											<input type="radio" name=slider[' . $slider->sid . '] value="0"';
											if(!array_key_exists($slider->sid, $answers) || $answers[$slider->sid] == 0 || (Input::exists() && Input::get('slider')[$slider->sid] == 0)) {
												echo ' checked';
											}
											echo '>
											N/A
										</label>

										&nbsp;&nbsp;&nbsp;&nbsp;

										<label>
											<input type="radio" name=slider[' . $slider->sid . '] value="1"';
											if((array_key_exists($slider->sid, $answers) && $answers[$slider->sid] == 1) || (Input::exists() && Input::get('slider')[$slider->sid] == 1)) {
												echo ' checked';
											}
											echo '>
											Poor
										</label>

										&nbsp;&nbsp;&nbsp;&nbsp;

										<label>
											<input type="radio" name=slider[' . $slider->sid . '] value="2"';
											if(array_key_exists($slider->sid, $answers) && ($answers[$slider->sid] == 2) || (Input::exists() && Input::get('slider')[$slider->sid] == 2)) {
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
					<div class="col-md-6 col-md-offset-3"><p class="forum-control-static text-danger" stye="font-size:16px;">No Syllabus Data</p></div>
					</div>';
				}
				?>
				<br><br><br>
				<div class="form-group">
					<label for="textArea" class="col-lg-3 control-label">Report Text</label>
					<div class="col-lg-8">
						<?php
							require_once(URL . 'scribe/box.php');
						?>
						<div class="scribe" id="textArea" class="form-control"><?php echo (!Input::exists()) ? $report->text : Input::get('text'); ?></div>
						<input type="hidden" name="text" class="scribe-html" value="<?php echo (!Input::exists()) ? htmlentities($report->text) : htmlentities(Input::get('text')); ?>">

					</div>
				</div>
				<br><br>
				<?php if($user->data()->id == 1032602 && $report->sess_type_id == 3) {
					?>
					<div class="form-group">
						<label for="uploads" class="control-label col-lg-3">Files</label>
						<div class="col-lg-8">
							<?php
							if($files){
								?>
								<table class="table table-condensed table-responsive table-striped">
									<tr>
										<td><input style="margin-left:5px;" type="checkbox" class="checkbox check" id="checkAll"></td>
										<td>Name</td>
										<td>Uploader</td>
										<td>Size</td>
									</tr>
								<?php
								foreach($files as $file) {
									?>

										<tr>
											<td>
												<input style="margin-left:5px;" class="checkbox check" name="fileDelete[<?php echo $file->id; ?>]" type="checkbox">
											</td>
											<td><a href="<?php echo BASE_URL . 'training/uploads/' . $_GET['id'] . '/' .  $file->fileName; ?>"><?php echo $file->originalName; ?></a></td>
											<td><?php echo $file->first_name . ' ' . $file->last_name;?></td>
											<td><?php echo $file->size;?></td>

									<?php
								}
								?>
								</tr>
								</table>
								<?php
							}
							 ?>
							<input id="uploads" name="upload[]" type="file" class="file" multiple data-browseClass="btn btn-default" data-show-upload="false" data-show-caption="true">
						</div>
					</div>
					<?php
				}
				?>
				<br><br>
				<div class="form-group text-center">
					<div class="col-lg-10 col-md-offset-1">
					<input type="hidden" name="id" value="<?php echo Input::get('id');?>"></input>
					<input type="hidden" name="cid" value="<?php echo $report->student_cid;?>"></input>
					<button type="submit" id="submit" name="submit" class="btn btn-primary btn-lg">Submit</button>
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
	<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/8.5.1/nouislider.min.js"></script> -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.2/js/fileinput.min.js" type="text/javascript"></script>

	<script>
		<?php
		if($allSliders) {
			foreach($allSliders as $slider) {
				if($slider->type == 0) {
					echo '$("#slider' . $slider->sid . '").noUiSlider({
							start: [';
							if(Input::get("slider")) {
								echo Input::get("slider")[$slider->sid];
							} else {
								if(isset($answers[$slider->sid])) {
									echo $answers[$slider->sid];
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
						$("#slider' . $slider->sid . '").Link("lower").to($("#slider' . $slider->sid . '-value"));
						$("#slider' . $slider->sid . '").Link("lower").to($("#slider' . $slider->sid . '-value-text"));';
				}
			}
		}
		?>

	</script>
	<script src="../../scribe/bower_components/requirejs/require.js" data-main="../../scribe/setup.js"></script>
	<script>
	$("#checkAll").click(function () {
	    $(".check").prop('checked', $(this).prop('checked'));
	});
	</script>
	<?php
}
