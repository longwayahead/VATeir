<?php
$pagetitle = 'View Notification';
require_once("../includes/header.php");

$n = new Notifications;

if(Input::exists('get')) {
	try{
		$notification = $n->getNotification(Input::get('id'), $user->data()->id);
		if($notification) {
			echo '<div class="row">
						<div class="col-md-10 col-md-offset-2">
							<div class="col-md-10">
								<div class="panel panel-primary">
								  <div class="panel-heading">
							  		  <h3 class="panel-title">Task Details</h3>
							 	  </div>
							  	<div class="panel-body" style="padding-bottom:0;">
							    	<table class="table table-responsive table-condensed">
									    <tr>
											<td><strong>Type</strong></td>
											<td><strong>Submitted By</strong></td>
											<td><strong>Opened On</strong></td>
											<td><strong>Status</strong></td>
									
										</tr>
									    <tr>
											<td>' . $notification->type_name . '</td>
											<td>' . $notification->first_name . ' ' . $notification->last_name . '</td>
											<td>' . date("j-M-Y H:i", strtotime($notification->submitted)) . '</td>
											<td>'; echo ($notification->status == 0 ? 'Open' : 'Closed'); echo '</td>
										</tr>
							    	</table>
							 	 </div>
								 <div class="panel-footer">
									<div class="text-right">
										<form method="get" action="close.php">
											<input type="hidden" name="id" value="' . Input::get('id') . '">';
									if($notification->status == 0) {
										echo '<button type="submit" name="close" value="1" class="btn btn-danger">Close Task</button>';
									} else {
										echo '<button type="submit" name="open" value="1" class="btn btn-success">Reopen Task</button>';
									}
									echo '</form>
									</div>
								</div>
							</div>
							<h5>Comments</h5>
						</div>
				
			';
			$comments = $n->getComments(Input::get('id'));
			
			if($comments) {
				foreach($comments as $comment) {
					
					if($comment->submitted_by == $notification->from) {
						
								echo '
										<div class="col-md-12">
										<div class="col-md-2">
											<img src="'.  $user->getAvatarUrl(['email' => $comment->email])  .'" class="img-responsive img-circle">
										</div>
										<div class="col-md-8 well">
											<div class="row">
													<div class="col-md-8">
														<p class="text-uppercase" style="font-size:20px; margin-bottom:-10px;">' . $comment->first_name . ' ' . $comment->last_name . '</p>
													</div>
													<div class="col-md-4">
														<p class="text-right" style="color:grey;"><time class="timeago" datetime="' . $commentTime . '">' . $date . '</time></p>
													</div>
											</div>
											
												<br>
												'. $comment->text .'
										</div>
									</div>

							<br>';
					} elseif($comment->submitted_by != $notification->from) {
						$date = date("j-M-y H:i:s", strtotime($comment->submitted));
						$datetime = new DateTime($date);
						$commentTime = $datetime->format(DateTime::ISO8601);

								echo '
									<div class="col-md-12">
										<div class="col-md-8 well">
											<div class="row">
													
												<div class="col-md-4">
													<p class="text-left" style="color:grey;">
														<time class="timeago" datetime="' . $commentTime . '">' . $date . '</time>
													</p>
												</div>
												<div class="col-md-8 text-right">
													<p class="text-uppercase" style="font-size:20px; margin-bottom:-10px;">' . $comment->first_name . ' ' . $comment->last_name . '</p>
												</div>
											</div>
											
												<br>
												<div class="text-right">'. $comment->text .'</div>
										</div>
										<div class="col-md-2">
											<img src="'.  $user->getAvatarUrl(['email' => $comment->email])  .'" class="img-responsive img-circle">
										</div>
									</div>

							<br>';	
					}
					
				}
			} else {
				echo '<div class="row">
						<p class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-1 well text-center danger" style="font-size:16px;">
							No comments yet
						</p>
					</div>';
			}
			
			
		}	

	} catch(Exception $e) {
		echo $e->getMessage();
	}
} else {
	echo 'No notification ID specified!';
}

require_once('../includes/footer.php');
?>

