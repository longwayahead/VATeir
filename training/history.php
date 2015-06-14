<?php
$pagetitle = 'My History';
require_once('includes/header.php');

try {
	$data = $t->getStudent($user->data()->id);
} catch (Exception $e) {
	echo $e->getMessage();
}
?>

<h3 class="text-center">My History</h3>


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
									<td><?php echo $data->long . ' (' . $data->short . ')'; ?></td>
								</tr>
								<tr>
									<td style="width:45%;">Pilot Rating:</td>
									<td><?php echo $data->pratingstring; ?></td>
								</tr>
								<tr>
									<td style="width:45%;">VATSIM Reg Date:</td>
									<td><?php echo date("j-M-Y", strtotime($data->regdate_vatsim)); ?></td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table" style="margin-bottom:0;">
								<tr>
									<td style="width:45%;">VATeir Reg Date:</td>
									<td><?php echo date("j-M-Y", strtotime($data->regdate_vateir)); ?></td>
								</tr>
								<tr>
									<td style="width:45%;">Activity Status:</td>
									<td>
										<a data-toggle="tooltip" data-placement="top" data-original-title="<?php echo ($data->rating > 0) ? 'Active' : 'Inactive'; ?>">VATSIM</a>
										<a data-toggle="tooltip" data-placement="top" data-original-title="<?php echo ($data->alive != 0) ? 'Active' : 'Inactive'; ?>">VATeir</a>
									</td>
								</tr>
								<tr>
									<td style="width:45%;">Programme:</td>
									<td><?php echo $data->name; ?></td>
								</tr>
								<tr>
									<td style="width:45%;">Controller Type:</td>
									<td><?php echo $data->status; ?></td>
								</tr>
								
							</table>
						</div>					
					</div>
				</div>
			</div>
		</div>
		<div class="row">
		        <!-- Card Projects -->
		        <div class="col-md-10 col-md-offset-1">
			        	<div class="panel panel-primary">
						  <div class="panel-heading">
						    <h3 class="panel-title">Training History</h3>
						  </div>
							<div class="panel-body">
							 


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
								echo '
								
									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div id="card" class="card" style="background-color:' . $report->colour . '; color:white;">
												<div class="nopad">
													<div id="r' . $report->rep_id . '" class="card-title text-center">
														<div class="hidden-xs" style="display:inline-block;"><strong>' . $report->sessname . ':</strong></div> ' . $report->callsign . ' on ' . date("jS M Y", strtotime($report->session_date)) . '
														</div>
												</div>
												
												
													<article>
														<div class="card-content">
															<div style="padding-left:10px;">
															
																	
																			' . $report->text;
													$sliders = $r->getSliderAnswers($report->rep_id);
													if($sliders) {
														echo '								
										<br><h4 style="color:white;">Breakdowns</h4>	
															';

																
												
														// echo '<pre>';
														// print_r($sliders);
														// echo '</pre>';
														foreach($sliders as $slider) {
															echo '<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-4 text-left">' . $slider->text . '</div>
																		<div class="col-xs-12  col-sm-12 col-md-8 text-left" style="height:20px;">';
															if($slider->type == 0) {
																echo '																				
																			<div class="progress progress-striped active" style="margin-top:10px;">
																			  <div class="progress-bar" style="width: ' . $slider->value . '0%; vertical-align:bottom;"></div>
																			</div>

																';
															} elseif($slider->type == 1) {
																if($slider->value == 2) {
																	echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
																} elseif($slider->value == 1) {
																	echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
																}
															}
															echo '</div>
																</div>';
														}
													}
												

													//end back  vv
												echo '</div>
							        		</div>
							        	</article>
										        
									       		
												    <div class="card-action">
												        <div class="row">
												            <div class="col-md-6 col-sm-6 col-xs-6" style="display:inline-block;">
																<div class="text">' . $report->mfname . ' ' . $report->mlname . '<div class="hidden-xs" style="display:inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;' . date("jS M Y", strtotime($report->submitted_date)) . '</div></div>
															</div>
															
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
													<div class="card-title text-center">
														<div class="hidden-xs" style="display:inline-block;"><strong>' . $note->name . ':</strong></div> ' . $note->subject . '
													</div>';
											if($note->text) {
												echo '<article><div class="card-content" style="margin-left:20px;">
													'. $note->text .'
													</div></article>';
											}
													
											 echo '<div class="card-action" style="margin-top:0px;">
												        <div class="row">
												            <div class="col-md-6 col-sm-6 col-xs-6" style="display:inline-block;">
																<div class="text">' . $note->mfname . ' ' . $note->mlname . '<div class="hidden-xs" style="display:inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;' . date("jS M Y", strtotime($note->submitted_date)) . '</div></div>
															</div>
															
														</div>
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
							<div class="text-danger text-center" style="font-size:16px;">No History</div><br>
			
							</div></div>';

					}
					


				?>

						</div>
					</div>
				</div>
			</div>
	</div>






		        


<?php
require_once("../includes/footer.php");
?>

<script>$('article').readmore({
	collapsedHeight: 58,
	speed:200,
	moreLink: '<div class="text-right" style="padding-right:25px; margin-bottom:4px;"><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> More</a></div>',
	lessLink: '<div class="text-right" style="padding-right:25px; margin-bottom:4px;"><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Less</a></div>'
});
</script>