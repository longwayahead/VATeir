<?php
$url = realpath($_SERVER['DOCUMENT_ROOT']) . '/'; //Update me!
require_once($url . 'includes/header.php');
unset($url);
if(!isset($_SESSION['user'])) {
	Redirect::to(BASE_URL . 'login/');
}
$t = new Training;
$r = new Reports;
$n = new Notifications;
$s = new Sessions;

?>
<div class="row">


<div class="col-sm-3 col-md-2">

	<ul class="well nav nav-list nav-list-vivid">


		My Training
			<li class="active"><a href="<?php echo BASE_URL . 'training/'; ?>">Home</a></li>
			<li><a href="<?php echo BASE_URL . 'training/availability.php'; ?>">Availability <?php //echo ($avails = $s->countAvailabilities($user->data()->id)) ? '<span class="badge danger">'. $avails .'</span>' : '';?></a></li>
			<li><a href="<?php echo BASE_URL . 'training/sessions.php'; ?>">Sessions <?php //echo ($sesh = $s->countSessions($user->data()->id)) ? '<span class="badge danger">'. $sesh .'</span>' : '';?></a></li>
			<li><a href="<?php echo BASE_URL . 'training/validations.php'; ?>">Validations</a></li>
			<li><a href="<?php echo BASE_URL . 'training/history.php'; ?>">History</a></li>
			<li><a href="<?php echo BASE_URL . 'training/calendar.php'; ?>">Calendar</a></li>
			<!-- <li><a href="<?php// echo BASE_URL . 'training/token.php'; ?>">Tokens</a></li> -->
			<li><a href="<?php echo BASE_URL . 'teamspeak'; ?>">Teamspeak</a></li>

			<li class="divider"></li>
			<br>
<?php
	if($user->hasPermission('mentor')) {
		?>
		Mentor Menu
			<li>
				<a href="<?php echo BASE_URL . 'training/mentor/'; ?>">
					Home
					<?php //echo ($ment = $s->countMentor($user->data()->id)) ? '<span class="badge danger">'. $ment .'</span>' : '';?>
				</a>
			</li>
			<li><a href="<?php echo BASE_URL . 'training/mentor/student_list.php'; ?>">Students</a></li>
			<li><a href="<?php echo BASE_URL . 'training/mentor/validation_list.php'; ?>">Validations</a></li>
			<li><a href="<?php echo BASE_URL . 'training/mentor/downloads.php'; ?>">Downloads</a></li>
			<br>

<?php
	}
	if($user->hasPermission('tdstaff')) {
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
			<li><a href="<?php echo BASE_URL . 'training/staff/sliders.php'; ?>">Breakdowns</a></li>
<?php
}
?>
	</ul>

</div>
<div class="col-sm-9 col-md-10">
