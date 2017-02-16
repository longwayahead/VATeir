<?php
$pagetitle = 'Downloads';
require_once("includes/header.php");
if(!$user->isLoggedIn()) {
	Redirect::to(BASE_URL . 'login/index.php');
}
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
<?php
echo '<h3 class="text-center">Downloads</h3><br>';
$d = new Download;
foreach($d->cats() as $cat) {
	echo '<h5>' . $cat->name;
	echo '  <a class="btn btn-xs btn-default" href="add_download_subcategory.php?id=' . $cat->id . '" data-toggle="tooltip" data-placement="top" title="Add Sub-Category"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a> ';
	echo '</h5>';
	$sub_cats = $d->sub_cats($cat->id);
	if($sub_cats !== false) {
		foreach($sub_cats as $category) {
			?>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php echo $category->name; ?> <a href="edit_download_subcategory.php?id=<?php echo $category->id; ?>" data-toggle="tooltip" data-placement="top" title="Edit Sub-Category"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></h3>
						</div>
						<div class="panel-body">
						<?php
						$files = $d->get($category->id);
						if($files) {
							?>
							<table class="table table-responsive table-striped table-condensed">
								<tr>
									<td class="nowrap"><strong>Name</strong></td>
									<td><strong>Description</strong></td>
									<td class="nowrap"><strong>Date</strong></td>
									<td><strong></strong></td>
									<td><strong></strong></td>
								</tr>
								<?php foreach($files as $file) : ?>
									<tr>
										<td class="nowrap"><?php echo '<a href="' . BASE_URL . 'uploads/' .$file->file_name . '">' . $file->name . '</a>'; ?></td>
										<td><?php echo $file->description; ?></td>
										<td class="nowrap"><?php echo date("j M y", strtotime($file->date_added)); ?></td>
										<td><?php echo '<a class="btn btn-xs btn-default" href="edit_download.php?id=' . $file->id . '" data-toggle="tooltip" data-placement="top" title="Edit Download"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>'; ?></td>
										<td><?php echo '<a class="btn btn-xs btn-default" href="delete_download.php?id=' . $file->id . '" data-toggle="tooltip" data-placement="top" title="Delete Download"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>'; ?></td>
									</tr>
								<?php endforeach; ?>
							</table>

							<?php		
						} else {
							echo '<div class="row">
							<div class="col-md-6 col-md-offset-3">
								<div class="text-danger text-center" style="font-size:16px;">No downloads</div>
							</div></div>';
						}
						?>
						</div>
						<div class="panel-footer text-right">
							<a href="<?php echo BASE_URL . 'admin/add_download.php?id=' . $category->id;?>" data-toggle="tooltip" data-placement="left" title="Add Download">Add</a>
						</div>
					</div>

			<?php
		}

	}
}
?>
	</div>
</div>
<?php
require_once('../includes/footer.php');