<?php
$pagetitle = "Fuel Planning";
require_once('includes/header.php');
?>
<h3 class="text-center">Fuel Planning</h3>
<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">Not for real world use</div> <br>
<div class="row">
	<div class="col-md-8 col-md-offset-2">

	<?php
	if(Input::exists()) {
		$x = abs(Input::get('gs')/60);
		//echo 'x = '. $x;
		$y = abs(Input::get('nm')) / $x;
		//echo 'y = '. $y;
		$ex = abs(Input::get('ta')) + abs(Input::get('ht')) + abs(Input::get('et'));
		$z = ($y + 45 + $ex)/60;
		//echo 'z = '. $z;
		//$fr = round($z, 2)*1000;
		$fr = $z * (abs(Input::get('fb')));
		//echo 'fr = '. $fr;

		?>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Fuel calculation</h3>
			</div>
			<div class="panel-body">
				<table class="table table-responsive table-striped">
					<tr>
						<td><strong>EET:</strong></td>
						<td><?php echo round($y); ?> mins</td>
					</tr>
					<tr>
						<td><strong>Endurance:</strong></td>
						<td><?php echo convertTime($z); ?> hrs</td>
					</tr>
					<tr>
						<td><strong>Fuel required:</strong></td>
						<td><?php echo round($fr); ?> kg<br>
							<?php echo round($fr*2.2046);?> pounds</td>
					</tr>
				</table>
			</div>
		</div><br><br>
		<?php
	}






	?>




		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Calculate requisite fuel</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" action="" method="post">
					<div class="form-group">
						<label for="gs" class="col-lg-4 control-label">Ground Speed</label>
						<div class="col-lg-8">
							<input required class="form-control" id="gs" name="gs" placeholder="kts" type="number" value="<?php echo (Input::exists()) ? Input::get('gs') : '';?>">
							<span class="help-block">GS of aircraft minus winds en route.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="nm" class="col-lg-4 control-label">Trip Distance</label>
						<div class="col-lg-8">
							<input required class="form-control" id="nm" name="nm" placeholder="NM" type="number" value="<?php echo (Input::exists()) ? Input::get('nm') : '';?>">
							<span class="help-block">Distance to destination in NM.</span>
						</div>

					</div>
					<div class="form-group">
						<label for="fb" class="col-lg-4 control-label">Fuel Burn</label>
						<div class="col-lg-8">
							<input required class="form-control" id="fb" name="fb" placeholder="kg" type="number" value="<?php echo (Input::exists()) ? Input::get('fb') : '';?>">
							<span class="help-block">Rate of aircraft's fuel burn per hour in kilograms.</span>
						</div>

					</div>
					<div class="form-group">
						<label for="ta" class="col-lg-4 control-label">Time to Alternate</label>
						<div class="col-lg-8">
							<input class="form-control" id="ta" name="ta" placeholder="mins" type="number" value="<?php echo (Input::exists()) ? Input::get('ta') : '';?>">
							<span class="help-block">Time to alternate in mins.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="ht" class="col-lg-4 control-label">Holding Time</label>
						<div class="col-lg-8">
							<input class="form-control" id="ht" name="ht" placeholder="mins" type="number" value="<?php echo (Input::exists()) ? Input::get('ht') : '';?>">
							<span class="help-block">Amount of holding time in mins.</span>
						</div>
					</div>
					<div class="form-group">
						<label for="et" class="col-lg-4 control-label">Extra Time</label>
						<div class="col-lg-8">
							<input class="form-control" id="et" name="et" placeholder="mins" type="number" value="<?php echo (Input::exists()) ? Input::get('et') : '';?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-6 col-lg-offset-5">
							<input type="submit" name="calculate" class="btn btn-info" value="Calculate">
						</div>
					</div>
				</form>
			</div>
	</div>
</div>


<?php

require_once('../includes/footer.php');
