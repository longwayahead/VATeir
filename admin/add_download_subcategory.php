<?php
$pagetitle = "Add Download Sub-Category";
require_once("includes/header.php");
$d = new Download;
if(Input::exists()) { //if form submitted!
	
	$validate = new Validate();
	$validation = $validate->check($_POST, array(
		'name' => array(
			'field_name' => 'Name',
			'required' => true
		),
		'sort' => array(
			'field_name' => 'Sort',
			'required' => true
		)
	));

	if($validation->passed()) {

		try {
			$d->add('download_sub_categories', array(
				'name'				=> Input::get('name'),
				'category'			=> Input::get('category'),
				'sort'				=> Input::get('sort'),
			));

			Session::flash('success', 'Sub-Category Added');
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
	$cat = $d->cats(Input::get('id'))[0];
} catch(Exception $e) {
	echo $e->getMessage();
}

?>
<h3 class="text-center">Add a Download Sub-Category</h3><br>
<div class="row">
	<div class="col-md-12">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Add</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" action="" method="post" onsubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').value='Submitting...';">
								<div class="form-group">
									<label for="cat" class="col-lg-2 control-label">Category</label>
									<div class="col-lg-8">
										<input disabled id="cat" name="cat" type="input" class="form-control" value="<?php echo $cat->name; ?>">
										<input type="hidden" name="category" value="<?php echo Input::get('id'); ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="name" class="col-lg-2 control-label">Name</label>
									<div class="col-lg-8">
										<input id="name" name="name" type="input" value="<?php echo (Input::get('name')) ? Input::get('name'): '';?>" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label for="sort" class="col-lg-2 control-label">Sort</label>
									<div class="col-lg-8">
										<input id="sort" name="sort" type="input" value="<?php echo (Input::get('sort')) ? Input::get('sort'): '';?>" class="form-control">
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
