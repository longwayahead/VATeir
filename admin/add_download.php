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
		'textfile' => array(
			'field_name' => 'File',
			'required' 	=> true,
			'fileError'	=> $file->error,
			'fileType' 	=> $file->type,
		)
	));

	if($validation->passed()) {

		try {
			$d->upload($file->tmp_name);
			$d->add('download_files', array(
				'name'				=> Input::get('name'),
				'sub_category'		=> Input::get('sub_category'),
				'date_added'		=> Input::get('date_added'),
				'added_by'			=> $user->data()->id,
				'file_name'			=> $file->name,
				'file_size'			=> $file->size,
				'file_type'			=> $file->type,
			));

			Session::flash('success', 'Download Added');
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
	$cat = $d->sub_cat(Input::get('id'));
} catch(Exception $e) {
	echo $e->getMessage();
}

?>
<h3 class="text-center">Add a Download</h3><br>
<div class="row">
	<div class="col-md-12">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Add</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" onsubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').value='Submitting...';">
								<div class="form-group">
									<label for="cat" class="col-lg-2 control-label">Sub-Category</label>
									<div class="col-lg-8">
										<input disabled id="cat" name="cat" type="input" value="<?php echo $cat->name; ?>" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="name" class="col-lg-2 control-label">Name</label>
									<div class="col-lg-8">
										<input id="name" name="name" type="input" value="<?php echo (Input::get('name')) ? Input::get('name'): '';?>" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="file" class="col-lg-2 control-label">File</label>
									<div class="col-lg-8">
										<div class="input-group">
											<span class="input-group-btn">
												<span class="btn btn-sm	 btn-primary btn-file">
												Browse&hellip; <input name="file" id="file" type="file" multiple>
												</span>
											</span>
											<input type="text" name="textfile" class="form-control"value="<?php echo (Input::get('textfile')) ? Input::get('textfile') : ''; ?>" readonly>
										</div>
									</div>
								</div>
								<input type="hidden" value="<?php echo Input::get('id'); ?>" name="sub_category">
								<input type="hidden" value="<?php echo date("y-m-d H:i:s"); ?>" name="date_added">
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
