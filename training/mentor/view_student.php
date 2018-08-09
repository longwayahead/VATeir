<?php
$pagetitle = 'View Student';
require_once('../includes/header.php');
if(!$user->hasPermission('mentor')) {
	Session::flash('error', 'Invalid permissions.');
	Redirect::to('../index.php');
}
if(!isset($_GET['cid'])) {
	Session::flash('error', 'You haven\'t supplied a VATSIM CID');
	Redirect::to('../mentor/');
}
try {
	$data = $t->getStudent(Input::get('cid'));
	if(!$data) {
		Session::flash('error', 'No student found for that CID');
		Redirect::to('../mentor/');
	}

	if(!$user->hasPermission($data->program_permissions)) {
		Session::flash('error', 'You cannot mentor at this level');
		Redirect::to('../mentor/');
	}

} catch (Exception $e) {
	echo $e->getMessage();
}
$now = new DateTime;
?>



<h3 class="text-center"><?php echo $data->first_name . ' ' . $data->last_name  ?></h3>
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<br>
				<div class="panel panel-primary">
					<div class="panel-heading">
					<h3 class="panel-title"><?php echo $data->first_name; ?>'s Training Details</h3>
					</div>
					<div class="panel-body">
						<div class="col-md-6">
							<table class="table" style="margin-bottom:0;">
								<tr>
									<td style="width:45%;">CID:</td>
									<td><?php echo $data->cid; ?></td>
								</tr>
								<tr>
									<td style="width:45%;">ATC Rating:</td>
									<td><div class="hidden-xs" style="display:inline-block;"><?php echo $data->long . ' (</div>' . $data->short . '<div class="hidden-xs" style="display:inline-block;">)</div>'; ?></td>
								</tr>
								<tr>
									<td style="width:45%;">Pilot Rating:</td>
									<td><?php echo $data->pratingstring; ?></td>
								</tr>
								<tr>
									<td style="width:45%;">VATSIM Reg:</td>
									<td><?php echo date("j\<\s\u\p\>S\<\/\s\u\p\> M Y", strtotime($data->regdate_vatsim)); ?></td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table" style="margin-bottom:0;">
								<tr>
									<td style="width:45%;">VATeir Reg:</td>
									<td><?php echo date("j\<\s\u\p\>S\<\/\s\u\p\> M Y", strtotime($data->regdate_vateir));
									?></td>
								</tr>
								<tr>
									<td style="width:45%;">Activity:</td>
									<td>
										<a style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo ($data->rating > 0) ? 'Active' : 'Inactive'; ?>">VATSIM</a>
										<a style="cursor:pointer;" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo ($data->alive != 0) ? 'Active' : 'Inactive'; ?>">VATeir</a>
									</td>
								</tr>
								<tr>
									<td style="width:45%;">Programme:</td>
									<td><?php echo $data->name; ?></td>
								</tr>
								<tr>
									<td style="width:45%;">Controller:</td>
									<td><?php echo $data->status; ?></td>
								</tr>

							</table>

						</div>
						<div class="row text-center">
							<div class="col-md-3 col-sm-6">
									<a class="btn btn-default" href="view_validations.php?cid=<?php echo $data->cid; ?>">
										<span class="glyphicon glyphicon-plane" aria-hidden="true"></span>
										 Validations
									</a>

							</div>
							<div class="col-md-3 col-sm-6">
								<a class="btn btn-default" href="view_available.php?cid=<?php echo $data->cid;?>">
										<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
										 Availability
									</a>

							</div>

							<div class="col-md-3 col-sm-6">

									<a class="btn btn-default" href="mailto:<?php echo $data->email ?>">
										<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
										 Email <?php echo $data->first_name; ?>
									</a>


								</div>


							<div class="col-md-3 col-sm-6">
								<a class="btn btn-default" href="<?php echo BASE_URL . 'controllers/profile.php?id=' . $data->cid;?>">
										<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
										 Profile
									</a>
							</div>
							<div class="col-md-3 col-md-offset-3 col-sm-6">
								<br>
								<a target="_blank" class="btn btn-default" href="https://stats.vatsim.net/conn_details_time.php?id=<?php echo $data->cid;?>&timeframe=6_months" data-toggle="tooltip" data-placement="top" title="stats.vatsim.net">
										<span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span>
										6 months
								</a>
							</div>
							<div class="col-md-3 col-sm-6">
								<br>
								<a target="_blank" class="btn btn-default" href="https://www.atsimtest.com/index.php?cmd=admin&sub=memberdetail&memberid=<?php echo $data->cid;?>">
										<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
										ATSimTest
								</a>
							</div>
							</div>

						</div>

				</div>
			</div>
		</div>
		<?php if($user->hasPermission('tdstaff')) { ?>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<br>
					<div class="panel panel-primary">
						<div class="panel-heading">
						<h3 class="panel-title">Admin Functions</h3>
						</div>
						<div class="panel-body">
							<div class="col-md-6">
								<?php $perms = $t->getPermissions(); ?>

								<div class="col-md-3">
									Permissions:
								</div>
								<div class="col-md-9">

									<form action="../staff/mentors.php" method="post">
										<select <?php echo ($data->grou >= 10 && $data->grou <= 15) ? '' : 'disabled' ;?> class="col-md-6 text-left form-control tick" onchange="this.form.submit()" name="data[<?php echo $data->cid;?>]">
											<?php foreach($perms as $perm):	?>

												<?php echo '<option value="' . $perm->id . '"';
												if($perm->id == $data->grou) {
													echo 'selected="" ';
												}
												echo '>';
												echo ($data->grou >= 10 && $data->grou <= 15) ? $perm->name : 'None' ;
												echo '</option>';
												?>

											<?php endforeach;?>
										</select>
									</form>
								</div>
							</div>
							<div class="col-md-6">
								<?php $progs = $t->getPrograms("all"); ?>
								<div class="col-md-3">
									Programme:
								</div>
								<div class="col-md-9">

									<form action="../staff/change_program.php" method="post">
										<select class="col-md-6 text-left form-control tick" onchange="this.form.submit()" name="data[<?php echo $data->cid;?>]">
											<?php foreach($progs as $prog):	?>

												<?php echo '<option value="' . $prog->id . '"';
												if($prog->id == $data->program) {
													echo 'selected="" ';
												}
												echo '>';
												echo $prog->name;
												echo '</option>';
												?>

											<?php endforeach;?>
										</select>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="row">
		        <!-- Card Projects -->
		        <div class="col-md-10 col-md-offset-1">
			        	<div class="panel panel-primary">
						  <div class="panel-heading">
						    <h3 class="panel-title">Training History</h3>
						  </div>
							<div class="panel-body">
							  <div class="row">
								  <div class="col-md-10 col-md-offset-1">
									<div class="col-md-12">
								  		<form action="add_note.php" method="get" class="form-horizontal">
											<div class="form-control">
												<div class="col-md-2 col-sm-3 col-xs-4">
													<label for="noteSelect" class="control-label">Note:</label>
												</div>
												<div class="col-md-10 col-sm-9 col-xs-8">
													<select name="type" id="typeSelect" class="form-control tick" onchange="this.form.submit()">
														<option>Select Type</option>
														<?php
															try {
																$notes = $r->getTypes(1);
																if($notes) {
																	$programs = array();
																	foreach($notes as $note){


																		echo '<option value="' . $note->id . '">' . $note->name . '</option>';
																	}
																}

															} catch(Exception $e) {
																echo '<option>' . $e->getMessage . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<input type="hidden" name="cid" value="<?php echo $data->cid;?>"></input>
										</form>
								 	</div>
								 	<!-- <div class="col-md-6">

										<form action="add_report.php" method="get" class="form-horizontal">
											<div class="form-control">
												<div class="col-md-4 col-sm-3 col-xs-4">
													<label for="typeSelect" class="control-label">Report:</label>
												</div>
												<div class="col-md-8 col-sm-9 col-xs-8">
													<select name="type" id="typeSelect" class="form-control tick" onchange="this.form.submit()">
														<option>Select Type</option>
														<?php
															// try {
															// 	$types = $r->getTypes(0);
															// 	if($types) {
															// 		$programs = array();
															// 		foreach($types as $type){
															// 			if(!in_array($type->pid, $programs)) {
															// 				$programs[] = $type->pid;
															// 					echo '<option class="select-dash" disabled="disabled">----</option>';
															// 				}
															// 			echo '<option value="' . $type->report_type_id . '">' . $type->ident . ': ' . $type->session_type_name . '</option>';
															// 		}
															// 	}

															// } catch(Exception $e) {
															// 	echo '<option>' . $e->getMessage . '</option>';
															// }
														?>
													</select>

												</div>
											</div>
											<input type="hidden" name="cid" value="<?php echo $data->cid;?>"></input>
										</form>
									</div> <---->
								</div>
							</div>


				<?php
			//	$parsedown = new Parsedown();
					$cards = $r->getCards($data->cid);
					// echo '<pre>';
					// print_r($cards);
					// echo '</pre>';
					if($cards) {
						foreach($cards as $card) {
							if($card->card_type == 0) {//mentoring report
								$report = $r->getReport(1, $card->link_id);
								echo '<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div id="card" class="card" style="background-color:' . $report->colour . '; color:white;">
												<div class="nopad">
													<div class="card-title text-center" id="r' . $report->rep_id . '">
														<div class="hidden-xs" style="display:inline-block;"><strong>' . $report->sessname . ':</strong></div> ' . $report->callsign . ' <div class="hidden-xs" style="display:inline-block;"> on ' . date("j\<\s\u\p\>S\<\/\s\u\p\> M Y", strtotime($report->session_date)) . '</div><div class="visible-xs" style="font-size:15px">' . date("j\<\s\u\p\>S\<\/\s\u\p\> M Y", strtotime($report->session_date)) . '</div>
														</div>
													</div>
													<article>
														<div class="card-content">
															<div style="padding-left:10px;">
															' . $report->text;
													$sliders = $r->getSliderAnswers($report->rep_id);

													if($sliders) {
														echo '
										<br><h4 style="color:white;">Syllabus</h4>
															';

																	// echo '<pre>';
																	// print_r($sliders);
																	// echo '</pre>';
																	$sliderCat = [];
																	foreach($sliders as $slider) {
																		if(!in_array($slider->category, $sliderCat)) {
																			$sliderCat[] = $slider->category;
																			end($sliderCat);

																			if(key($sliderCat) != 0) {
																				echo '<br><br>';
																			}
																			echo '<p class="text-center text-uppercase"><u>' . $slider->name . '</u></p>';
																		}
																		echo '<div class="row">
																					<div class="col-xs-9 col-sm-8 col-md-8 text-left">' . $slider->text . '...</div>
																					<div class="col-xs-3 col-sm-4 col-md-4 text-left" style="height:20px;">';
																		if($slider->type == 0) {
																		echo '
																		<div class="hidden-xs">
																		<a style="cursor:pointer;" data-toggle="tooltip" data-placement="top" title="' . $slider->value . '0%">
																				<div class="progress progress-striped active" style="margin-top:10px;">
																					<div class="progress-bar" style="width: ' . $slider->value . '0%; vertical-align:bottom;"></div>
																				</div>
																			</a>
																		</div>
																		<div class="visible-xs">
																		' . $slider->value . '0%
																		</div>
																			';
																		} elseif($slider->type == 1) {
																			if($slider->value ==2) {
																				echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
																			} elseif($slider->value == 1) {
																				echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
																			}
																		}
																		echo '</div>
																			</div>';
																	}
																}

																if($report->files > 0) {
																	$f = new Files;
																	echo '<br><h4 style="color:white;">Files</h4>
																	<table class="table table-responsive table-condensed">
																					<tr>
																						<td>Name</td>
																						<td class="hidden-xs">Uploader</td>
																						<td class="hidden-xs">Size</td>
																					</tr>';
																	$files = $f->get($report->rep_id);

																	foreach($files as $file) {
																		echo '<tr>
																					 <td><a href="' . BASE_URL . 'training/uploads/' . $report->rep_id . '/' . $file->fileName . '">' . $file->originalName . '</a></td>
																					 <td class="hidden-xs">' . $file->first_name . ' ' . $file->last_name . '</td>
																					 <td class="hidden-xs">' . $file->size . '</td>
																					</tr>';
																	}
																	echo '</table>';
																}


																//end back  vv
															echo '</div>
										        		</div>
										        	</article>


												    <div class="card-action">
												        <div class="row">
												            <div class="col-md-6 col-sm-6 col-xs-6" style="display:inline-block; padding-left:5px;">
																<div class="text">' . $report->mfname . ' ' . $report->mlname . '<div class="hidden-xs" style="display:inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;';
																$dtReport = new DateTime($report->submitted_date);
																$dtReportFormat = $dtReport->format("j\<\s\u\p\>S\<\/\s\u\p\> M Y");
																if($dtReport->diff($now)->days < 7){
																	echo '<time class="timeago" datetime="' . $dtReport->format(DateTime::ATOM) . '">' . $dtReportFormat . '</time>';
																} else {
																	echo $dtReportFormat;
																}
																echo '</div></div>
															</div>
															<div class="col-md-6 col-sm-6 col-xs-6 text-right" style="display:inline-block;">
																<a class="btn btn-primary btn-xs" href="edit_report.php?id=' . $report->rep_id . '"><span class="glyphicon glyphicon-pencil"></span><div class="hidden-xs" style="display:inline-block;"> Edit</div></a>
';
														// if($user->hasPermission('superadmin')) {
														// 	echo '<a onclick="return confirm(\'Are you sure?\');" class="btn btn-danger btn-xs" href="delete_card.php?id=' . $card->link_id . '&cid='. $data->cid  .'"><span class="glyphicon glyphicon-remove"></span><div class="hidden-xs" style="display:inline-block;"> Delete</div></a>';
														// }

													echo '</div>
														</div>
													</div>

												</div>
											</div>
										</div>

								';
							} elseif($card->card_type == 1) { //note
								$note = $r->getNote($card->link_id);

								echo '<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div id="card" class="card" style="background-color:' . $note->colour . '; color:white;">
												<div class="nopad">
													<div class="card-title text-center" id="n' . $note->note_id . '">
														<div class="hidden-xs" style="display:inline-block;"><strong>' . $note->name . ':</strong></div> ' . $note->subject . '
													</div>';
											if($note->text) {
									echo '<article>
													<div class="card-content">
														<div style="padding-left:10px;">
														'. $note->text .'
														</div>
													</div>
												</article>';
											}

											 echo '<div class="card-action">
												        <div class="row">
												            <div class="col-md-6 col-sm-6 col-xs-6" style="display:inline-block; padding-left:5px;">
																<div class="text">' . $note->mfname . ' ' . $note->mlname . '<div class="hidden-xs" style="display:inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;';
																$dtNote = new DateTime($note->submitted_date);
																$dtNoteFormat = $dtNote->format("j\<\s\u\p\>S\<\/\s\u\p\> M Y");
																if($dtNote->diff($now)->days < 7){
																	echo '<time class="timeago" datetime="' . $dtNote->format(DateTime::ATOM) . '">' . $dtNoteFormat . '</time>';
																} else {
																	echo $dtNoteFormat;
																}
																echo '</div></div>
															</div>
															<div class="col-md-6 col-sm-6 col-xs-6 text-right" style="display:inline-block;">
																<a class="btn btn-primary btn-xs" href="edit_note.php?id=' . $note->note_id . '"><span class="glyphicon glyphicon-pencil"></span><div class="hidden-xs" style="display:inline-block;"> Edit</div></a>';
																// if($user->hasPermission('superadmin')) {
																// 	echo '<a onclick="return confirm(\'Are you sure?\');" class="btn btn-danger btn-xs" href="delete_card.php?id=' . $card->link_id . '&cid='. $data->cid  .'"><span class="glyphicon glyphicon-remove"></span><div class="hidden-xs" style="display:inline-block;"> Delete</div></a>';
																// }
																echo '</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>';
							} elseif($card->card_type == 2) { //infocard
								$info = $r->getInfo($card->link_id);
								echo '<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div id="card" class="card" style="background-color:' . $info->colour . '; color:white;">
												<div class="nopad">
													<div class="card-title text-center" id="ns' . $info->session_id . '"">
														<div class="hidden-xs"><strong>' . $info->card_name . ':</strong> <div class="hidden-xs" style="display:inline-block;">' . $info->callsign . ' on</div> ' . date('j\<\s\u\p>S\</\s\u\p\> M', strtotime($info->start)) . '<div style="display:inline-block;" class="hidden-xs">&nbsp;' . date('Y', strtotime($info->start)) .'</div></div>
														<div class="visible-xs"> ' . $info->callsign . '</div><div class="visible-xs" style="font-size:15px">' . date("j\<\s\u\p\>S\<\/\s\u\p\> M Y", strtotime($info->start)) . '</div>
													</div>
				 									</div>
				 								</div>
				 							</div>
				 						</div>';
							}
						}

					} else {
						echo '<br><div class="row">
								<div class="col-md-6 col-md-offset-3">
							<div class="text-danger text-center" style="font-size:16px;"><br>No training history</div><br>

							</div>';

					}



				?>

						</div>
					</div>
				</div>
			</div>
		</div>






</div>

<?php
require_once("../../includes/footer.php");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.6.1/jquery.timeago.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Readmore.js/2.0.5/readmore.min.js"></script>
<script>$('article').readmore({
	collapsedHeight: 58,
	speed:200,
	moreLink: '<div class="text-left" style="padding-top:10px; padding-left:15px; margin-bottom:4px;"><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> More</a></div>',
	lessLink: '<div class="text-left" style="padding-top:10px; padding-left:15px; margin-bottom:4px;"><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Less</a></div>'
});
</script>
<script>
jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();
});
</script>
