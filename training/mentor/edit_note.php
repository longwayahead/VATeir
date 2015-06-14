<?php
require_once('../includes/header.php');

if(isset($_GET['id'])) {
	$note = $r->getNote($_GET['id']);
	$types = $r->getTypes(1);
		if(!$user->hasPermission('mentor') || !$user->hasPermission($note->permissions)) {
			Session::flash('error', 'Insufficient permissions');
			Redirect::to('./');
		}
	if(Input::exists()) { //if form submitted!
		
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'type' => array(
				'field_name' => 'Note Type',
				'required' => true
			),
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
				$update = $r->updateNote(array(
					'note_type'			=> Input::get('type'),
					'subject'			=> Input::get('subject'),
					'text'				=> Input::get('text')
				), 

					[
						['id', '=', Input::get('id')]

					]

				);
				if($update === true) {
					Session::flash('success', 'Note Edited');
					Redirect::to('./view_student.php?cid=' . Input::get("cid"));
				}
				

					
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
		?>



	<div class="col-md-10 col-md-offset-1 well">
		<form class="form-horizontal" method="post" action="">
	        <fieldset>
	          <legend>Edit Note</legend>
	          	<div class="form-group">
					<label for="studentname" class="col-lg-3 control-label">Student Name</label>
					<div class="col-lg-4">
						<input class="form-control" type="text" id="studentname" placeholder="<?php echo $note->sfname . ' ' . $note->slname;?>" readonly>
					</div>
				</div>
				<div class="form-group">
					<label for="notetype" class="col-lg-3 control-label">Type</label>
					<div class="col-lg-4">
						<select name="type" class="form-control tick">

							<?php

								foreach($types as $type) {
									echo '<option value="' . $type->id .  '"'; 
										if($type->id == $note->note_type) {
											echo ' selected';
										}
									echo '>' . $type->name . '</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="subject" class="col-lg-3 control-label">Subject</label>
					<div class="col-lg-4">
						<input class="form-control" autocomplete="off" name="subject" type="text" id="subject" value="<?php echo (!Input::exists()) ? $note->subject : Input::get('subject'); ?>" required>
					</div>
				</div>
				
				
				<div class="form-group">
					<label for="textArea" class="col-lg-3 control-label">Text</label>
					<div class="col-lg-8">
						<?php
							require_once(URL . 'scribe/box.php');
						?>
						<div class="scribe" class="form-control"><?php echo (!Input::exists()) ? $note->text : Input::get('text'); ?></div>
						<input type="hidden" name="text" class="scribe-html" value="<?php echo (!Input::exists()) ? $report->text : Input::get('text'); ?>">
						
						<!-- <textarea name="text" autocomplete="off" class="form-control" rows="3" id="textArea"><?php echo (!Input::exists()) ? $note->text : Input::get('text'); ?></textarea>
						<span class="help-block">(Optional) This field supports <a target="_blank" href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Markdown</a>!.</span>
				 -->	</div>
				</div>
				<div class="form-group text-center">
					<div class="col-lg-10 col-md-offset-1">
					<input type="hidden" name="cid" value="<?php echo $note->student_cid;?>"></input>
					<input type="hidden" name="id" value="<?php echo $note->note_id;?>"></input>
					<button type="submit" name="submit" class="btn btn-primary">Submit</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>


<?php
echo '</div>';
require_once("../../includes/footer.php");
}
?>
<script src="../../scribe/bower_components/requirejs/require.js" data-main="../../scribe/setup.js"></script>
