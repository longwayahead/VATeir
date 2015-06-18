<?php
require_once("../includes/header.php");
if(!$user->hasPermission('tdstaff')) {
	Session::flash('error', 'Invalid permissions');
	Redirect::to(BASE_URL . 'training');
}
?>
<h3 class="text-center">Sliders: Breakdown Options</h3><br>
<div class="row">
	<div class="col-md-12">
		<?php
		$t = new Training;
		$r = new Reports;
		$programs = $t->getPrograms();
		foreach($programs as $program) {
			?>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><?php echo $program->name; ?></h3>
						</div>
						<div class="panel-body">
							<table class="table table-striped table-condensed table-responsive">
							<?php
								$sliders = $r->getSliders(1, $program->id);
								if($sliders !== false) {
									?>
									<tr>
										<td><strong>Text</strong></td>
										<td><strong>Type</strong></td>
										<td><strong>Edit</strong></td>
										<td><strong>Delete</strong></td>
									</tr>
									<?php
									foreach($sliders as $slider):
										?>
										<tr>
											<td><?php echo $slider->text; ?></td>
											<td><?php echo ($slider->type == 0) ? 'Slider' : 'Boolean'; ?></td>
											<td><a href="edit_slider.php?id=<?php echo $slider->id; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></td>
											<td><a href="delete_slider.php?id=<?php echo $slider->id; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
										</tr>
										
										<?php
									endforeach;
								} else {
									echo '<div class="text-danger text-center" style="font-size:16px">No options.</div>';
								}
							?>
							</table>

						</div>
						<div class="panel-footer text-right">
							<a href="add_slider.php?id=<?php echo $program->id;?>">Add</a>
						</div>
					</div>
				</div>
			</div>



			
			<?php			
		}

		?>
	</div>
</div>
<?php
require_once("../../includes/footer.php");
