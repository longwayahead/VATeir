<?php
$pagetitle = 'Downloads';
require_once("includes/header.php");
if(!$user->isLoggedIn()) {
	Redirect::to(BASE_URL . 'login/index.php');
}
echo '<h3 class="text-center">Controller Downloads</h3><br>';
$d = new Download;
$cats = $d->sub_cats(1);
if($cats !== false) {
	foreach($cats as $category) {
		?>
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo $category->name; ?>	</h3>
				</div>
				<div class="panel-body">
				<?php
				$files = $d->get($category->id);
				if($files) {
					?>
					<table class="table table-responsive table-striped table-condensed">
						<tr>
							<td><strong>Name</strong></td>
							<td><strong>Date</strong></td>
							<td><strong>File Size</strong></td>
						</tr>
						<?php foreach($files as $file) : ?>
							<tr>
								<td><?php echo '<a href="' . BASE_URL . 'uploads/' .$file->file_name . '">' . $file->name . '</a>'; ?></td>
								<td><?php echo date("j M y", strtotime($file->date_added)); ?></td>
								<td><?php echo $file->file_size; ?></td>
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
			</div>
		</div>
	</div>
		<?php
	}
}
require_once('../includes/footer.php');