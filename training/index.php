<?php
$pagetitle = "Training Home";
require_once("includes/header.php");
?>
<div class="row">
	<h3 class="text-center">My Training Dashboard</h3><br>
	<!-- <?php //if($user->data()->id == 1032602) {
	?>
	<div class="row">
		<div class="col-md-4">

				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Next session</h3>
					</div>
					<div class="panel-body">

						<?php
						// $session = $s->nextSession(1373006);
						// if($session !== false) {
						// 	?>
						// 	<p><?php echo $session->session_type;?></p>
						// 	<p><a class="btn btn-default" href="sessions.php#<?php echo $session->id;?>">View &#187;</a></p>
						// 	<?php
						// 	}
						?>
					</div>
				</div>

		</div>
		<div class="col-md-4"></div>
		<div class="col-md-4"></div>
	</div>
	<?php
	//}
	?>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Welcome!</h3>
			</div>
			<div class="panel-body">
				<div style="font-size:16px;">
					<p>Hi <?php echo $user->data()->first_name;?>, welcome to the VATeir Training System.</p>
				</div>
					<p>By following the links to the left of this dialog box, you can access your submitted availability, your forthcoming and historic sessions, your solo validations, and an extensive training history.</p>
					<p>Your training history includes mentoring reports, breakdowns on your progress as per the VATeir Training Syllabus, and notes added by VATeir mentors on validations awarded, exams, and any other training-related material.</p>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Mentoring checklist</h3>
			</div>
			<div class="panel-body">
				<div style="font-size:16px;">
					Beforehand, make sure to...
				</div>
				<ol>
					<li>Submit numerous availability options&mdash;<i>the more options the mentors have, the more likely it is that you will get mentored</i>;</li>
					<li>Read up on any relevant material before your training session&mdash;<i>being badly-prepared is a waste of everyone's time</i>;</li>
					<li>Note the time of your mentoring session from the booking email, or the sessions interface&mdash;<i>a confirmation email is sent with the time and date of your mentoring session</i>;</li>
					<li>Be in the VATeir teamspeak channel at least 5 minutes before your mentoring session is scheduled to begin&mdash;<i>click the link on the left to connect to the server and channel automatically</i>.</li>
				</ol>
				<div style="font-size:16px;">
					Afterwards, remember to...
				</div>
				<ol>
					<li>Read the training report and mentor's comments from your last session&mdash;<i>mentoring reports are colour-coded in your training history</i>;</li>
					<li>Listen to <a href="http://www.liveatc.net/search/?icao=EIDW" target="_blank">real world ATC</a> to improve your radio-telephony (RT).</li>
				</ol>
			</div>
		</div>
	</div>


<?php
echo '</div>';
require_once("../includes/footer.php");
?>
