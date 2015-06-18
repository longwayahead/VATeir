<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
if(!$user->isLoggedIn()) {
	Session::flash('info', 'Please login to access the training section');
	Redirect::to('../login/');
}
$t = new Training;
$r = new Reports;
$n = new Notifications;
$s = new Sessions;

?>
<div class="row">


<div class="col-sm-3 col-md-2">
	
	<ul class="well nav nav-list nav-list-vivid">
	<?php
	if($user->hasPermission('student')) {
		?>

		My Training
			<li class="active"><a href="<?php echo BASE_URL . 'training/'; ?>">Home</a></li>
			<li><a href="<?php echo BASE_URL . 'training/history.php'; ?>">History</a></li>
			<li><a href="<?php echo BASE_URL . 'training/sessions.php'; ?>">Sessions <?php echo ($ses = $s->countStudent($user->data()->id) > 0) ? '<span class="badge danger">'.$ses.'</span>' : '';?></a></li>
			<li><a href="<?php echo BASE_URL . 'training/availability.php'; ?>">Availability</a></li>
			<li><a href="<?php echo BASE_URL . 'training/validations.php'; ?>">Validations</a></li>
			<li><a href="<?php echo BASE_URL . 'training/token.php'; ?>">Exam Tokens</a></li>
			
			<li class="divider"></li>
			<br>
<?php
	}
	if($user->hasPermission('mentor')) {
		?>
		Mentor Menu
			<li>
				<a href="<?php echo BASE_URL . 'training/mentor/'; ?>">
					Home
					<span class="badge"></span>
				</a>
			</li>
			<li><a href="<?php echo BASE_URL . 'training/mentor/student_list.php'; ?>">Student List</a></li>
			<li><a href="<?php echo BASE_URL . 'training/mentor/validation_list.php'; ?>">Validation List</a></li>
			<br>
	
<?php
	}
	if($user->hasPermission('tdstaff')) {
		//$vals = $t->plus();
	?>
		Staff Menu
			<li>
				<a href="<?php echo BASE_URL . 'training/staff/'; ?>">
					Home
					<span class="badge"></span>
				</a>
			</li>
			<li><a href="<?php echo BASE_URL . 'training/staff/validations.php'; ?>">
				Validations
				<span class="badge"><?php //echo $vals;?></span>
			</a></li>
			<li><a href="<?php echo BASE_URL . 'training/staff/mentors.php'; ?>">Mentors</a></li>
			<li><a href="<?php echo BASE_URL . 'training/staff/sliders.php'; ?>">Sliders</a></li>
<?php
}
?>
	</ul>

</div>
<div class="col-sm-9 col-md-10">
