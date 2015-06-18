<?php
require_once('../includes/header.php');


if(Input::exists()) { //if form submitted!
	
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'subject' => array(
			'field_name' => 'Subject',
			'max' => 20,
			'required' => true
		),
		'text' => array(
			'field_name' => 'Note Text',
			'required' => false
		)
	));

	if($validation->passed()) {
		try {
			$r->addNote(array(
				'student_cid'		=> Input::get('cid'),
				'mentor_cid'		=> Input::get('mentorcid'),
				'note_type'			=> Input::get('type'),
				'submitted_date'	=> date("Y-m-d"),
				'subject'			=> Input::get('subject'),
				'text'				=> Input::get('text')
			));

			$noteID = $r->getLatestID('notes');

			$r->addCard(array(
				'cid'		=> Input::get("cid"),
				'card_type'	=> 1, //For session. 1 = note.
				'link_id'	=> $noteID,
				'submitted'	=> date('Y-m-d H:i:s')
			));
			
			Session::flash('success', 'Note Added');
			Redirect::to('./view_student.php?cid=' . Input::get("cid"));

				
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

	if(!Input::get('cid')) {
		Session::flash('error', 'You haven\'t supplied a Controller ID');
		Redirect::to('./view_student.php?cid=' . Input::get("cid"));

	} elseif(!Input::get('type')) {
		Session::flash('error', 'You haven\'t supplied a note type');
		Redirect::to('./view_student.php?cid=' . Input::get("cid"));

	}
	try {
		$student = $t->getStudent(Input::get('cid'));
		if(!$student) {
			Session::flash('error', 'Couldn\'t find student info');
			Redirect::to('./view_student.php?cid=' . Input::get("cid"));
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
				<form class="form-horizontal" method="post" action="add_note.php">
	                <fieldset>
	                  <legend>Add Note</legend>
	                  	<div class="form-group">
							<label for="studentname" class="col-lg-3 control-label">Student Name</label>
							<div class="col-lg-4">
								<input class="form-control" type="text" id="studentname" placeholder="<?php echo $student->first_name . ' ' . $student->last_name;?>" readonly>
							</div>
						</div>
						<div class="form-group">
							<label for="notetype" class="col-lg-3 control-label">Type</label>
							<div class="col-lg-4">
								<select name="type" id="typeSelect" class="form-control tick" onchange="this.form.submit()">
									<option>Select Type</option>
									<?php
										try {
											$notes = $r->getTypes(1);
											if($notes) {
												$programs = array();
												foreach($notes as $note){				
												

													echo '<option value="' . $note->id . '"';

													if($note->id == Input::get('type')) { echo ' selected '; }
													echo '>' . $note->name . '</option>';
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
							<label for="subject" class="col-lg-3 control-label">Subject</label>
							<div class="col-lg-4">
								<input class="form-control" name="subject" type="text" id="subject" required>
							</div>
						</div>
						
						
						<div class="form-group">
							<label for="textArea" class="col-lg-3 control-label">Text</label>
							<div class="col-lg-8">
							<?php
								require_once(URL . 'scribe/box.php');
							?>
							<div class="scribe" class="form-control"><?php echo Input::get('text'); ?></div>
							<input type="hidden" name="text" class="scribe-html" value="<?php echo Input::get('text'); ?>">
							
								<!-- <textarea name="text" class="form-control" rows="3" id="textArea"><?php echo Input::get('report');?></textarea>
								<span class="help-block">(Optional) This field supports <a target="_blank" href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Markdown</a>!.</span>
							 --></div>
						</div>
						<div class="form-group text-center">
							<div class="col-lg-10 col-md-offset-1">
							<input type="hidden" name="cid" value="<?php echo Input::get('cid');?>"></input>
							<input type="hidden" name="type" value="<?php echo Input::get('type');?>"></input>
							<input type="hidden" name="mentorcid" value="<?php echo $user->data()->id;?>"></input>
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
<script src="../../scribe/bower_components/requirejs/require.js" data-main="../../scribe/setup.js"></script>

