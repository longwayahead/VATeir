<?php
$pagetitle = "Add Download";
require_once("includes/header.php");

$d = new Download;
if(Input::exists()) { //if form submitted!
	$file = $d->file($_FILES['file']);
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'name' => array(
			'field_name' => 'Name',
			'required' => true,
		),
		'description' => array(
			'field_name' => 'Description',
			'required' => true,
		)
	));

	if($validation->passed()) {

		try {
			$d->edit('download_files', array(
				'name'				=> Input::get('name'),
				'description'				=> Input::get('description'),
			), [['id', '=', Input::get('id')]]);

			Session::flash('success', 'Download Edited');
			Redirect::to('./downloads.php');

				
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
	$download = $d->get(Input::get('id'), "id")[0];
} catch(Exception $e) {
	echo $e->getMessage();
}

?>
<h3 class="text-center">Edit a Download</h3><br>
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
									<label for="name" class="col-lg-2 control-label">Name</label>
									<div class="col-lg-8">
										<input id="name" name="name" type="input" value="<?php echo (Input::get('name')) ? Input::get('name'): $download->name;?>" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="description" class="col-lg-2 control-label">Description</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="3" id="description" name="description" required><?php echo (Input::get('description')) ? Input::get('description'): $download->description;?></textarea>
									</div>
								</div>
<!-- 								<input type="hidden" value="<?php //echo Input::get('id'); ?>" name="sub_category"> -->
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
