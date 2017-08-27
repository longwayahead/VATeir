<?php
require_once('includes/header.php');
require_once('../teamspeak/init.php');
if(!$user->isLoggedIn() || !$user->hasAdmin("admin")) {
	Session::flash('error', 'Invalid permissions.');
	Redirect::to('../index.php');
}

?>

<h3 class="text-center">Teamspeak admin</h3><br>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-primary">
		  <div class="panel-heading">
		    <h3 class="panel-title">Banned users</h3>
		  </div>
		  <div class="panel-body">
		    <?php
				$b = $conn->prepare("SELECT b.*, c.* FROM bans b
					LEFT JOIN users c ON c.cid = b.cid
					WHERE UNIX_TIMESTAMP(b.time_banned)+b.duration >= UNIX_TIMESTAMP(now())
					AND deleted = 0
					GROUP BY b.cid, b.time_banned
				");
				$b->execute();
				$bans = $b->fetchAll(PDO::FETCH_ASSOC);
				// echo '<pre>';
				// print_r($bans);
				// echo '</pre>';
				if($b->rowCount() > 0) {
					?>
					<table class="table table-responsive table-striped">
						<tr>
							<td><strong>Name</strong></td>
							<td><strong>Reason</strong></td>
							<td><strong>Start</strong></td>
							<td><strong>Ends</strong></td>
							<td><strong>Delete</strong></td>
						</tr>
						<?php
						foreach($bans as $ban) {
							?>
							<tr>
								<td><?php echo $ban['first_name'] . ' ' . $ban['last_name']; ?></td>
								<td><?php echo $ban['reason']; ?></td>
								<td><?php echo date("j\<\s\u\p\>S\<\/\s\u\p\> M Y H:i:s", strtotime($ban['time_banned'])); ?></td>
								<td><?php echo date("j\<\s\u\p\>S\<\/\s\u\p\> M Y H:i:s", strtotime($ban['time_banned'] . ' + ' . $ban['duration'] . ' seconds')); ?></td>
								<td><a class="btn btn-xs btn-default" href="teamspeak_unban.php?cid=<?php echo $ban['cid'];?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
							</tr>
							<?php
						}
						?>
					</table>
					<?php

				} else {
					?>
					<br><div class="text-danger text-center" style="font-size:16px;">No bans</div><br>
					<?php
				}
				?>
		  </div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-primary">
		  <div class="panel-heading">
		    <h3 class="panel-title">Requested aliases</h3>
		  </div>
		  <div class="panel-body">
		    <?php
				$a = $conn->prepare("SELECT a.id as alias_id, a.fname as alias_fname, a.lname as alias_lname, a.registered as alias_registered,
					u.*
					 FROM aliases a
					LEFT JOIN users u ON u.cid = a.cid
					WHERE a.approved = 0
				");
				$a->execute();

				if($a->rowCount() > 0) {
					$aliases = $a->fetchAll(PDO::FETCH_ASSOC);
					?>
					<table class="table table-responsive table-striped">
						<tr>
							<td><strong>CERT</strong></td>
							<td><strong>Alias</strong></td>
							<td><strong>Requested</strong></td>
							<td><strong>Approve</strong></td>
							<td><strong>Reject</strong></td>
						</tr>
						<?php
						foreach($aliases as $alias) {
							?>
							<tr>
								<td><?php echo $alias['first_name'] . ' ' . $alias['last_name']; ?></td>
								<td><?php echo $alias['alias_fname'] . ' ' . $alias['alias_lname']; ?></td>
								<td><?php echo date("j\<\s\u\p\>S\<\/\s\u\p\> M Y H:i:s", strtotime($alias['alias_registered'])); ?></td>
								<td><a class="btn btn-xs btn-default" href="teamspeak_approve_alias.php?cid=<?php echo $alias['alias_id'];?>"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a></td>
								<td><a class="btn btn-xs btn-default" href="teamspeak_reject_alias.php?cid=<?php echo $alias['alias_id'];?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
							</tr>
							<?php
						}
						?>
					</table>
					<?php

				} else {
					?>
					<br><div class="text-danger text-center" style="font-size:16px;">No aliases</div><br>
					<?php
				}
				?>
		  </div>
		</div>
	</div>
</div>
<?php require_once('../includes/footer.php'); ?>
