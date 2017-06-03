<?php
$pagetitle = "Training Home";
require_once("includes/header.php");
?>
<div class="row">
	<h3 class="text-center">My Training Dashboard</h3><br>
	<div class="col-md-8 col-md-offset-2">
		<!-- <div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">My Open Tasks</h3>
			</div>
			<div class="panel-body">
				<table class="table table-responsive table-striped table-condensed">

					<?php

					// $notifications = $n->getList(0, $user->data()->id);
					// if($notifications) {
					// 	echo '<tr>
					// 			<td><strong>Type</strong></td>
					// 			<td><strong>Submitted By</strong></td>
					// 			<td><strong>Opened On</strong></td>
					// 			<td><strong>View</strong></td>
					// 		</tr>';
					// 	foreach($notifications as $notification) {
					// 		echo '<tr>
					// 				<td>' . $notification->type_name . '</td>
					// 				<td><a href="../mentor/view_student.php?cid=' . $notification->from . '">' . $notification->first_name . ' ' . $notification->last_name . '</a></td>
					// 				<td>' . date("j-M-Y H:i", strtotime($notification->submitted)) . '</td>
					// 				<td><a href="../notifications/view.php?id=' . $notification->notification_id . '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></td>
					// 			</tr>';
					// 	}
					// } else {
					// 	echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No Tasks</div>';
					// }

					?>
				</table>
			</div>
		</div> -->
	</div>
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
