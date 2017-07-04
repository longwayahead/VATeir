<?php
$pagetitle = "Training Home";
require_once("includes/header.php");
?>
<div class="row">
	<h3 class="text-center">My Training Dashboard</h3><br><br>


		<div class="col-md-4">

				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">My next session</h3>
					</div>
					<div class="panel-body">

						<?php
						 $session = $s->nextSession($user->data()->id);
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
						$rep = $r->getReport(3, $user->data()->id);
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
					<?php
					$vals = $t->fetchAllValidations($user->data()->id, '`c`.`id`', null, 'LIMIT 2');
					// echo '<pre>';
					// print_r($vals);
					// echo '</pre>';
					if($vals) {

					foreach($vals as $val) { ?>
						<div style="border-radius:10px; background-color:#4caf50; color:white;">
							<p class="text-center"><span style="font-size:30px;"><?php echo $val->callsign;?></span>
								<br>
								Expires
								<br>
								<span style="font-size:15px;"><?php echo date('j\<\s\u\p\>S\<\/\s\u\p\> M Y', strtotime($val->valid_until));?></span>
									</p>
							</div>
							<?php
					}
				} else {
					?><div class="text-danger text-center" style="font-size:16px;"><br>No validations</div><br><?php
				}
					 ?>
				</div>
				<?php if($vals) {
					?>
					<div class="panel-footer text-right">
						<a href="validations.php">View all</a>
					</div>
					<?php
				}
?>
			</div>
		</div>
	</div>
	<div class="row">
	 <div class="col-xs-12 text-center">
		 <h5>VATeir Training Videos</h5>
	 </div>
 </div>
 <div class="row">
	 <div class="col-sm-8 col-sm-offset-2">
		 <div id="random_number1" class="carousel slide youtube-carousel"  data-ride="carousel" data-interval="false">
			 <div class="carousel-inner">
				 <div class="video-container item active">
					 <div class="youtube-video" id='9MsRdYO_ql8'></div>
					 <div class="carousel-caption">OBS&ndash;S1: Video 1</div>
				 </div>
				 <div class="video-container item">
					 <div class="youtube-video" id='oaDK3ugiZgg'></div>
					 <div class="carousel-caption">OBS&ndash;S1: Video 2</div>
				 </div>
				 <div class="video-container item ">
					 <div class="youtube-video" id='XVdr9grPYdo'></div>
					 <div class="carousel-caption">OBS&ndash;S1: Video 3</div>
				 </div>
			 </div>
			 <div class="controls">
				 <a class="left carousel-control" href="#random_number1" data-slide="prev">
					 <div class="left-button">
						 <div class="glyphicon glyphicon-chevron-left"></div>
					 </div>
				 </a>
				 <a class="right carousel-control" href="#random_number1" data-slide="next">
					 <div class="right-button">
						 <div class="glyphicon glyphicon-chevron-right"></div>
					 </div>
				 </a>
			 </div>
		 </div>
	 </div>
 </div>

<?php
echo '</div>';
?>
<script>
//Start Youtube API
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var youtubeReady = false;

//Variable for the dynamically created youtube players
var players= new Array();
var isPlaying = false;
function onYouTubeIframeAPIReady(){
  //The id of the iframe and is the same as the videoId
  jQuery(".youtube-video").each(function(i, obj)  {
     players[obj.id] = new YT.Player(obj.id, {
			  videoId: obj.id,
			    playerVars: {
			    controls: 2,
		      rel:0,
		      autohide:1,
		      showinfo: 0 ,
		      modestbranding: 1,
		      wmode: "transparent",
		      html5: 1
       	},
        events: {
          'onStateChange': onPlayerStateChange
        }
       });
     });
     youtubeReady = true;
  }


function onPlayerStateChange(event) {
  var target_control =  jQuery(event.target.getIframe()).parent().parent().parent().find(".controls");

  var target_caption = jQuery(event.target.getIframe()).parent().find(".carousel-caption");
  switch(event.data){
    case -1:
      jQuery(target_control).fadeIn(500);
      jQuery(target_control).children().unbind('click');
      break
     case 0:
      jQuery(target_control).fadeIn(500);
      jQuery(target_control).children().unbind('click');
      break;
     case 1:
      jQuery(target_control).children().click(function () {return false;});
      jQuery(target_caption).fadeOut(500);
      jQuery(target_control).fadeOut(500);
       break;
      case 2:
        jQuery(target_control).fadeIn(500);
        jQuery(target_control).children().unbind('click');
        break;
        case 3:
           jQuery(target_control).children().click(function () {return false;});
           jQuery(target_caption).fadeOut(500);
           jQuery(target_control).fadeOut(500);
           break;
          case 5:
            jQuery(target_control).children().click(function () {return false;});
            jQuery(target_caption).fadeOut(500);
            jQuery(target_control).fadeOut(500);
            break;
          default:
            break;
    }
};

jQuery(window).bind('load', function(){
  jQuery(".carousel-caption").fadeIn(500);
  jQuery(".controls").fadeIn(500);
 });

jQuery('.carousel').bind('slid.bs.carousel', function (event) {
   jQuery(".controls").fadeIn(500);
});
</script>
<?php
require_once("../includes/footer.php");
?>
