<?php
$pagetitle = "Edit T&C";
require_once("includes/header.php");

$t = new Terms;
if(Input::exists()) { //if form submitted!
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'name' => array(
			'field_name' => 'Name',
			'required' => true,
		),
		'type' => array(
			'field_name' => 'Type',
			'required' => true,
		),
		'text' => array(
			'field_name' => 'Description',
			'required' => true,
		),
	));

	if($validation->passed()) {

		try {
			$typ = Input::get('type');
			switch($typ) {
				case($typ == 1):
					$type = 0;
				break;
				case($typ == 2):
					$type = 1;
				break;
			}
			$t->edit(array(
				'type'			=> $type,
				'name'			=> Input::get('name'),
				'text'			=> Input::get('text'),
				'date'			=> date("y-m-d H:i:s"),
			), [['id', '=', Input::get('id')]]);

			Session::flash('success', 'T&C Edited');
			Redirect::to('./terms.php');

				
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

try {
	$term = $t->get(Input::get('id'))[0];
} catch(Exception $e) {
	echo $e->getException();
}

?>
<h3 class="text-center">Edit a T&C</h3><br>
<div class="row">
	<div class="col-md-12">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Edit</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" onsubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').value='Submitting...';">
								<div class="form-group">
							      <label for="type" class="col-lg-2 control-label">Type</label>
							      <div class="col-lg-10">
							        <select class="form-control" id="type" name="type">
							          <option value="1" <?php echo ($term->type == 0) ? ' selected' : '';?>>Website</option>
							          <option value="2"<?php echo ($term->type == 1) ? ' selected' : '';?>>Forum</option>
							        </select>
							      </div>
							    </div>
								<div class="form-group">
									<label for="name" class="col-lg-2 control-label">Name</label>
									<div class="col-lg-8">
										<input required id="name" name="name" type="input" value="<?php echo (Input::get('name')) ? Input::get('name'): $term->name;?>" class="form-control">
									</div>
								</div>
								<div class="form-group">
							      <label for="text" class="col-lg-2 control-label">Description</label>
							      <div class="col-lg-10">
							        <textarea required class="form-control" name="text" rows="3" id="text"><?php echo (Input::get('text')) ? Input::get('text'): $term->text;?></textarea>
							      </div>
							    </div>
								<div class="form-group text-center">
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
require_once("../includes/footer.php");
