<?php
require_once('includes/header.php');
	$a = new Airports;
	$all = $a->all();
	$airports = array();
	foreach($all as $air) {
	  if(!in_array($air['icao'], $airports) && $air['icao'] != 'EISN') {
	    $airports[] = $air['icao'];
	  }
	}
	$i = 1;

	foreach($all as $air) {
	  if($air['icao'] != 'EISN') {
	    $str .= '[\'' . $air['data']['name'] . '\', '; //0
	    $str .= $air['data']['lat'] . ', '; //1
	    $str .= $air['data']['lon'] . ', ';//2
	    $str .= $i . ', '; //3
	    $str .= '\'http://www.iaa.ie/safe_reg/iaip/aip_' . strtolower($air['icao']) . '_charts.htm\']'; //4
	   
	    if($air['icao'] != end($airports)) {
	      $str .= ', ';
	    }
	  }
	  $i++;
	}
	?>
	  <h3 class="text-center">Pilots Home</h3> <br>
	  <div class="row">
	  <div class="col-md-6">
	  		<div class="panel panel-info">
			  <div class="panel-heading">
			    <h3 class="panel-title">Charts</h3>
			  </div>
			  <div class="panel-body">

				  <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
				  <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.1.min.js"></script>

				  <div id="map" class="img-responsive" style="width: 500px; height: 400px;"></div>
				 
				  <script type="text/javascript">
				    var locations = [
				      <?php echo $str; ?>
				    ];

				    var map = new google.maps.Map(document.getElementById('map'), {
				      zoom: 10,
				      center: new google.maps.LatLng(0,0),
				      mapTypeId: google.maps.MapTypeId.ROADMAP,
				      disableDefaultUI: true

				    });

				    var infowindow = new google.maps.InfoWindow();

				    var marker, i;
				    var markers = new Array();
				    var image = {
				      url: '<?php echo BASE_URL ?>img/airport.png',
				      // This marker is 20 pixels wide by 32 pixels tall.
				      size: new google.maps.Size(32, 37),
				      // The origin for this image is 0,0.
				      origin: new google.maps.Point(0,0),
				      // The anchor for this image is the base of the flagpole at 0,32.
				      anchor: new google.maps.Point(16, 37)
				    };
				     var shape = {
				        coords: [4, 7, 10, 7, 16, 3, 23, 7, 29, 7, 29, 32, 4, 32, 4, 7],
				        type: 'poly'
				    };
				    for (i = 0; i < locations.length; i++) {  
				      marker = new google.maps.Marker({
				        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				        map: map,
				        icon: image,
				        shape: shape,
				        url: locations[i][4]
				      });

				      markers.push(marker);

				      google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
				        return function() {
				          infowindow.setContent(locations[i][0]);
				          infowindow.open(map, marker);
				        }
				      })(marker, i));
				      google.maps.event.addListener(marker, 'click', function() {
				        window.open(this.url, '_blank');
				      });
				    }

				    function AutoCenter() {
				      //  Create a new viewpoint bound
				      var bounds = new google.maps.LatLngBounds();
				      //  Go through each...
				      $.each(markers, function (index, marker) {
				      bounds.extend(marker.position);
				      });
				      //  Fit these bounds to the map
				      map.fitBounds(bounds);
				    }
				    AutoCenter();

				  </script> 
			  </div>
			</div>
	</div>
	
	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">ATC Bookings</h3>
			</div>
			<div class="panel-body">
				<?php
				$t = new Training;
				$bookings = $t->bookings();
				if(count($bookings->atc) > 0) {					
					echo '<table class="table table-striped table-responsive table-condensed">
						<tr>
							<td><strong>Position</strong></td>
							<td><strong>Controller</strong></td>
							<td><strong>From (UTC)</strong></td>
							<td><strong>Until (UTC)</strong></td>
						</tr>';
					foreach($bookings->atc as $booking) {

								echo '<tr><td>';
									echo $booking->callsign;
								echo '</td>';
								echo '<td>';
									echo $booking->name;
								echo '</td><td>';
									echo date('d-m-y H:i', strtotime($booking->time_start));
								echo '</td><td>';
									echo date('d-m-y H:i', strtotime($booking->time_end));
								echo '</td></tr>';

					}
					echo '</table>';
				} else {
					echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No bookings</div><br>';
				}
				?>
			</div>
		</div>
	</div>
	</div>
<!-- 	<div class="row">

	<div class="col-md-6">
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Relevant NOTAMs</h3>
			</div>
			<div class="panel-body">
				<?php
				// $notams = $a->notams();
				// $i = 1;
				// $icao = array();
				// if(count($notams)) {					
				// 	echo '<table class="table table-striped table-responsive table-condensed">
				// 		<tr>
				// 			<td><strong>ICAO</strong></td>
				// 			<td><strong>Message</strong></td>
				// 			<td><strong>From</strong></td>
				// 			<td><strong>Until</strong></td>
				// 		</tr>';
				// 	foreach($notams as $notam) {
				// 		if(!in_array($notam['icao'], $icao)) {
				// 			$icao[] = $notam['icao'];
				// 			if($i <= 7) {
				// 				echo '<tr><td>';
				// 					echo $notam['icao'];
				// 				echo '</td>';
				// 				echo '<td>';
				// 					echo $notam['message'];
				// 				echo '</td><td>';
				// 					echo date('d-m-y H:i', strtotime($notam['start']));
				// 				echo '</td><td>';
				// 					echo date('d-m-y H:i', strtotime($notam['end']));
				// 				echo '</td></tr>';
				// 			}
				// 			$i++;
				// 		}
				// 	}
				// 	echo '</table>';
				// }
				?>
			</div>
			<?php
			// if($i >= 7) {
			// 	echo '<div class="panel-footer text-right"><a href="notams.php">View All</a></div>';
			// }
			?>
		</div>

</div>
</div> -->
</div>

<?php
require_once('../includes/footer.php');
?>