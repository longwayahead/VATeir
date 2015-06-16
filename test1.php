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
    $str .= '\'urlgoeshere.\']'; //4
    $coords .= 'new google.maps.LatLng (' . $air['data']['lat'] . ', ' . $air['data']['lon'] . ')';;
  
    if($air['icao'] != end($airports)) {
      $str .= ', ';
      $coords .= ', ';
    }
  }
  $i++;
}
//echo $coords;
?>
  <script src="http://maps.google.com/maps/api/js?sensor=false" 
          type="text/javascript"></script>

  <div id="map" style="width: 500px; height: 400px;"></div>

  <script type="text/javascript">
    var locations = [
     <?php echo $str; ?>
    ];
    var mapOptions = {
      zoom: 8,
      center: new google.maps.LatLng(51, -8)
    }
    var map = new google.maps.Map(document.getElementById('map'), mapOptions);


    var infowindow = new google.maps.InfoWindow();

    var marker, i;
    var image = {
      url: 'img/airport.png',
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
        title: locations[i][0],
        url: locations[i][4]
      });

      google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
      google.maps.event.addListener(marker, 'click', function() {
        window.location.href = this.url;
      });
    }
  </script>
</body>
</html>