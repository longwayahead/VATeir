<?php
$pagetitle = "Edit a syllabus item";
require_once("../includes/header.php");
if(!$user->hasPermission('tdstaff')) {
	Session::flash('error', 'Invalid permissions');
	Redirect::to(BASE_URL . 'training');
}
$r = new Reports;

if(Input::exists()) { //if form submitted!
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'textarea' => array(
			'field_name' => 'Option Text',
			'required' => true,
			'max' => 50,
		),
		'type' => array(
			'field_name' => 'Report Type',
			'required' => true
		),
		'check' => array(
			'field_name' => 'Slider/Boolean',
			'required' => false
		)
	));

	if($validation->passed()) {
		try {
			$check = (Input::get('check')) ? 1 : 0;
			$r->updateS(array(
				'type'				=> $check,
				'report_type'		=> Input::get('type'),
				'text'				=> Input::get('textarea'),
				'category'			=> Input::get('cat')
			), [['id', '=', Input::get('id')]]);

			Session::flash('success', 'Option edited');
			Redirect::to('./sliders.php');


		} catch (Exception $e) {
			die($e->getMessage());
		}
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
}

$option = $r->getSlider(Input::get('id'));
//print_r($option);

$t = new Training;
$programs = $t->getPrograms();
$categories = $t->getSliderCategories();
$types = $r->getTypes(0, $a = null);
?>
<h3 class="text-center">Edit a syllabus item</h3><br>
<div class="row">
	<div class="col-md-12">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Edit</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" action="" method="post" onsubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').value='Submitting...';">
								<div class="form-group">
									<label for="textarea" class="col-lg-2 control-label">Option text</label>
									<div class="col-lg-8">
										<input id="textarea" name="textarea" type="input" value="<?php echo (isset($_GET['text'])) ? $_GET['text'] : $option->text;?>" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="typeSelect" class="control-label col-lg-2">Report Type:</label>
									<div class="col-lg-8">
										<select name="type" id="typeSelect" class="form-control tick" onchange="this.form.submit()">
											<option value="">Select Type</option>
											<?php
												try {

													if($types) {
														$programs = array();
														foreach($types as $type){
															if(!in_array($type->pid, $programs)) {
																$programs[] = $type->pid;
																	echo '<option class="select-dash" disabled="disabled">----</option>';
																}
															echo '<option value="' . $type->report_type_id . '"';
															if(Input::get('type')) {
																echo (Input::get('type') == $type->report_type_id) ? ' selected' : '';
															} else {
																echo ($option->report_type == $type->report_type_id) ? ' selected' : '';
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
								<!-- <div class="form-group">
									<label for="program" class="col-lg-2 control-label">Programme</label>
									<div class="col-lg-8">
										<select class="form-control" id="program" name="program">
								          <?php foreach($programs as $program): ?>
								          	<option value="<?php echo $program->id; ?>"
								          	<?php if(isset($_GET['program']) && ($_GET['program'] == $option->program_id)) {
								          				echo 'selected';
								          			} elseif($option->program_id == $program->id) {
								          				echo 'selected';
								          			} ?> >

								          	<?php echo $program->name; ?></option>
								          <?php endforeach; ?>
								        </select>
									</div>
								</div> -->
								<div class="form-group">
									<label for="cat" class="col-lg-2 control-label">Category</label>
									<div class="col-lg-8">
										<select class="form-control" id="cat" name="cat">
								          <?php foreach($categories as $category): ?>
								          	<option value="<?php echo $category->id; ?>"
								          	<?php if(isset($_GET['cat']) && ($_GET['cat'] == $option->category)) {
								          				echo 'selected';
								          			} elseif($option->category == $category->id) {
								          				echo 'selected';
								          			} ?> >

								          	<?php echo $category->name; ?></option>
								          <?php endforeach; ?>
								        </select>
									</div>
								</div>
								<div class="form-group">
									<label for="check" class="col-lg-2 control-label">Is Boolean</label>
									<div class="col-lg-10">
										<div class="checkbox">
											<label>
												<input name="check" style="margin-left:5px;" value="1" type="checkbox"
													<?php if($option->type == 1) {
														echo 'checked ';
													} ?>
												 class="checkbox check" id="check">
											</label>
										</div>
										<span class="help-block">Check for boolean. Leave unchekced for slider.</span>
									</div>
								</div>
								<div class="form-group text-center">
									<input type="hidden" name="id" value="<?php echo Input::get('id'); ?>">
									<input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>
<?php
require_once("../../includes/footer.php");
