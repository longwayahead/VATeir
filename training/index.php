<?php
$pagetitle = "Training Home";
require_once("includes/header.php");
?>
<div class="row">
	<h3 class="text-center">My Training Dashboard</h3><br>
	 <?php if($user->data()->id == 1032602) {
	?>

		<div class="col-md-4">

				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">My next session</h3>
					</div>
					<div class="panel-body">

						<?php
						 $session = $s->nextSession(1341574);
					 if($session !== false) {
					// 	 print_r($session);
						 	?>
							<div style="border-radius:10px; background-color:<?php echo $session->colour;?>; color:white;">
								<p class="text-center"><span style="font-size:30px;"><?php echo $session->callsign;?></span>
									<br>

									<span style="font-size:15px;"><?php echo date('j\<\s\u\p\>S\<\/\s\u\p\> M Y', strtotime($session->start));?></span>
									<br>
									<br>
									
									<span class="glyphicon glyphicon-plane" aria-hidden="true"></span> <span style="font-size:15px;"><?php echo $session->type;?></span>
									<br>
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span> <span style="font-size:15px;"><?php echo $session->first_name . ' ' . $session->last_name;?></span>
									<br>
									<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <span style="font-size:15px;"><?php echo date('H:i', strtotime($session->start)) . ' to ' . date('H:i', strtotime($session->finish));?> IST</span>
									<br>


									<br>
									<a style="margin-top:-20px;" target="_blank" class="btn btn-primary btn-xs" href="sessions.php#s<?php echo $session->id;?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View</a>
								</p>
								</div>

							<?php
					 	} else {
							  ?><div class="text-danger text-center" style="font-size:16px;"><br>None booked :-(</div><br><?php
						}
						?>
					</div>
				</div>

		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Latest reports</h3>
				</div>
				<div class="panel-body">
					<?php
						$rep = $r->getReport(3, 1194728);
						if($rep !== false) {
							// print_r($rep);
							foreach($rep as $report) {
								?>
								<div style="border-radius:10px; background-color:<?php echo $report->colour;?>; color:white;">
									<p class="text-center"><span style="font-size:30px;"><?php echo $report->callsign;?></span>
										<br>
										<span style="font-size:15px;"><?php echo date('j\<\s\u\p\>S\<\/\s\u\p\> M Y', strtotime($report->session_date));?></span>
										<br>
										<br>
										<a style="margin-top:-20px;" target="_blank" class="btn btn-primary btn-xs" href="history.php#r<?php echo $report->rep_id;?>"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> Read</a>
									</p>
									</div>
								<?php
							}
						} else {
							?><div class="text-danger text-center" style="font-size:16px;"><br>No reports</div><br><?php
						}
					?>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Latest validations</h3>
				</div>
				<div class="panel-body">

					<div style="border-radius:10px; background-color:#4caf50; color:white;">
						<p class="text-center"><span style="font-size:30px;"><?php echo $session->callsign;?></span>
							<br>

							<span style="font-size:15px;"><?php echo date('j\<\s\u\p\>S\<\/\s\u\p\> M Y', strtotime($session->start));?></span>
							<br>
							<br>
							<br>
							<span class="glyphicon glyphicon-plane" aria-hidden="true"></span> <?php echo $session->type;?>
							<br>
							<span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $session->first_name . ' ' . $session->last_name;?>
							<br>
							<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?php echo date('H:i', strtotime($session->start)) . ' to ' . date('H:i', strtotime($session->finish));?> IST
							<br>
							<br>

							<br>
							<a style="margin-top:-20px;" target="_blank" class="btn btn-primary btn-xs" href="sessions.php#s<?php echo $session->id;?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View</a>
						</p>
						</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">My last login</h3>
				</div>
				<div class="panel-body">
					<?php
					$login = $user->lastLogin($user->data()->id);
					if($login !== false) {
						?>
						<p>Last login was on <br><span style="font-size:20px;"><?php echo date('d\<\s\u\p\>S\<\/\s\u\p\> M Y', strtotime($login->datetime));?></span><br>
							from <br>
							<span style="font-size:20px;">
							<?php echo $login->ip;?></span></p>
						<?php
					} else {
						  ?><div class="text-success text-center" style="font-size:16px;"><br>Welcome to VATeir!</div><br><?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4"></div>

	<?php
	}
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
