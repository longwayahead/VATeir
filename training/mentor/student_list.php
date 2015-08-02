<?php
$pagetitle = "Student List";
require_once("../includes/header.php");
?>
<h3 class="text-center">Student List</h3>

<?php
echo '<br><div class="col-md-10 col-md-offset-1">

	<div class="row">
  <div class="col-md-9 col-sm-10" style="margin-bottom:4px;">
  <form action="student_list.php" method="get">
  
    <div class="input-group">
      <input type="text" class="form-control" name="search" autocomplete="off" value="' . Input::get('search') . '" placeholder="Search..." style="padding-left:10px; padding-right:10px;">
      <span class="input-group-btn">
      <input type="hidden" name="a" value="1">
        <input class="btn btn-primary" type="submit" value="Go!">
        
      </span>
    </div><!-- /input-group -->
    </form>
  </div><!-- /.col-lg-9 -->
  <div class="col-md-3 col-sm-2">

			<div class="pull-right">';
				if(!isset($_GET['a']) || ($_GET['a'] != 1)) {
					echo '<a href="student_list.php?a=1">By List</a> <br>';
				} else {
					echo '<a href="student_list.php">By Programme</a>';
				}
		echo '	</div>

  </div>
</div><!-- /.row -->';
if(!isset($_GET['c']) && (!isset($_GET['a'])) || isset($_GET['c'])) {
	try {
		foreach($t->getPrograms() as $p) {
			if($user->hasPermission($p->permissions)) {
				echo '<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">' . $p->name . ' Programme</h3>
						</div>
					<div class="panel-body">';
				echo '<table class="table table-condensed table-responsive table-striped">';
					$students = $t->getStudentsProgram($p->id);
					if($students) {			

						echo '<tr>
									<td><strong>Name</strong></td>
									<td class="hidden-xs"><strong>CID</strong></td>
									<td><strong><div class="hidden-xs" style="display:inline-block;">ATC</div> Rating</strong></td>
									<td class="hidden-xs"><strong>Pilot Rating</strong></td>
									<td><strong>View</strong></td>
								</tr>';

						foreach($students as $student) {
							echo '<tr>
									<td>' . $student->first_name . ' ' . $student->last_name . '</td>
									<td class="hidden-xs">' . $student->cid . '</td>
									<td><div class="hidden-xs" style="display:inline-block;">' . $student->long . ' (</div>' . $student->short . '<div class="hidden-xs" style="display:inline-block;">)</div></td>
									<td class="hidden-xs">' . $student->pratingstring . '</td>
									<td>
										<a href="view_student.php?cid=' . $student->cid . '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
									</td>
								</tr>';
						}
						
					} else {
						echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No students</div>';
					}
				echo '</table>';
				echo '</div>
					</div>';
			}
		}
	} catch(Exception $e) {
		echo $e->getMessage();
	}
} else {

	try {
		if(isset($_GET['search'])) {
			$searchBy = $_GET['search'];
		} else {
			$searchBy = null;
		}
		$students = $t->getStudents($searchBy);
		if($students) {
			echo '<div class="panel panel-primary" style="margin-top:4px;">
					<div class="panel-heading">
						<h3 class="panel-title">Student List</h3>
					</div>
					<div class="panel-body">';
					echo '<table class="table table-responsive table-striped table-condensed"><tr>
							<td><strong>Name</strong></td>
							<td class="hidden-xs"><strong>CID</strong></td>
							<td><strong><div class="hidden-xs" style="display:inline-block;">ATC </div>Rating</strong></td>
							<td class="hidden-xs"><strong>Pilot Rating</strong></td>
							<td class="hidden-xs"><strong>Programme</strong></td>
							<td><strong>View</strong></td>
						</tr>';

			foreach($students as $student) {
				echo '<tr>
						<td>' . $student->first_name . ' ' . $student->last_name . '</td>
						<td class="hidden-xs">' . $student->cid . '</td>
						<td><div class="hidden-xs" style="display:inline-block;">' . $student->long . ' (</div>' . $student->short . '<div class="hidden-xs" style="display:inline-block;">)</div></td>
						<td class="hidden-xs">' . $student->pratingstring . '</td>
						<td class="hidden-xs">' . $student->name . '</td>
						<td>
							<a href="view_student.php?cid=' . $student->cid . '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
						</td>
					</tr>';
			}

				echo '</table></div>
				</div>
				';
		} else {
			echo '<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Student List</h3>
					</div>
					<div class="panel-body" style="padding-top:8px; padding-bottom:23px;">
						<div class="text-danger text-center" style="font-size:16px;" margin-top:8px;">No students<br></div>
        </div>
				</div>';
		}
	} catch(Exceptoin $f) {
		echo $f->getMessage();
	}
}
 


echo '</div>';
echo '</div>';
require_once("../../includes/footer.php");
?>