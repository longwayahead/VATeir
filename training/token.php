<?php
$pagetitle = "Request an Exam Token";
require_once("includes/header.php");
$n = new Notifications;
if(Input::exists()) {
	try {
		$rat = $user->data()->rating;
		if($rat > 0 && $rat < 5) {
			$rating = $t->getRating(++$rat);
		}

		if(!$n->exists(1, $user->data()->id)) {

			$id = $n->add(array(
					'type' 		=> 1,
					'from' 		=> $user->data()->id,
					'to_type' 	=> 1,
					'to' 		=> 3,
					'submitted' => date("Y-m-d H:i:s"),
					'status'	=> 0
				));
		
			$comment = $n->addComment(array(
					'notification_id'	=> $id,
					'submitted'			=> date("Y-m-d H:i:s"),
					'submitted_by'		=> 0,
					'text'				=> 
					'<p>ATSimTest Theory token requested.</p><p><strong>Next Rating: </strong>' . $rating->short . ' (' . $rating->long . ')</p><p><strong>Admin Link: </strong><a target="_blank" class="btn btn-xs btn-primary" href="https://www.atsimtest.com/index.php?cmd=admin&sub=memberdetail&memberid=' . $user->data()->id . '">ATSimTest</a></p>'
					));

			Session::flash('success', 'A theory token has been requested. Please allow 24 hours.');
			Redirect::to('./index.php');
		}

	} catch(Exception $e) {
		echo $e->getMessage();
	}
}
?>

<div class="row">
<h3 class="text-center">My ATSimTest Token</h3><br>
	<div class="col-md-8 col-md-offset-2 well text-center">
		
		<br>
		
	<?php if($user->data()->rating < 5) { ?>
	<p>You are eligible for a rating upgrade requiring a written test. When you wish to request the ability to take this test please press the button below.</p>
		<br>
		<?php
			if(!$not = $n->exists(1, $user->data()->id)) {
				?>
				<form action="" method="post">
					<button type="submit" name="button" class="btn btn-lg btn-primary">
					Click to Request
					</button>
				</form>
				<?php
			} else {
				?>
				<div style="font-size:16px; color:red;">
					You have a request pending. <a class="btn btn-xs btn-primary" href="../notifications/view.php?id=<?php echo $not;?>">View</a>
				</div>
				<?php
			}


		?>
		
	<?php } else { ?>
		<div style="font-size:16px; color:red;">
			You have no further upgrades.
		</div>
	<?php } ?>
		<br><br>
	
	</div>
</div>



<?php


echo '</div>';
require_once("../includes/footer.php");
?>