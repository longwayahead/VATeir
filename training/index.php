<?php
$pagetitle = "Training Home";
require_once("includes/header.php");
?>
<div class="row">
	<h3 class="text-center">My Training Dashboard</h3><br>
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">My Open Tasks</h3>
			</div>
			<div class="panel-body">
				<table class="table table-responsive table-striped table-condensed">

					<?php

					$notifications = $n->getList(0, $user->data()->id);
					if($notifications) {
						echo '<tr>
								<td><strong>Type</strong></td>
								<td><strong>Submitted By</strong></td>
								<td><strong>Opened On</strong></td>
								<td><strong>View</strong></td>
							</tr>';
						foreach($notifications as $notification) {
							echo '<tr>
									<td>' . $notification->type_name . '</td>
									<td><a href="../mentor/view_student.php?cid=' . $notification->from . '">' . $notification->first_name . ' ' . $notification->last_name . '</a></td>
									<td>' . date("j-M-Y H:i", strtotime($notification->submitted)) . '</td>
									<td><a href="../notifications/view.php?id=' . $notification->notification_id . '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></td>
								</tr>';
						}
					} else {
						echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No Tasks</div>';
					}

					?>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
echo '</div>';
require_once("../includes/footer.php");
?>