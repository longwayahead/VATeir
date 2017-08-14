    <?php
    require_once('db.php');
    //////////////////////PREPARE THE QUERY//////////////////////
    $sql = "SELECT date(logon_time) as days,";
    $airfields = $conn->prepare("SELECT * FROM airfields ORDER BY icao ASC");
    $airfields->execute();
    $af = $airfields->fetchAll(PDO::FETCH_ASSOC);
    foreach($af as $k => $a) {
      $sql .= "
      (
          SELECT count(*)
            from movements
            where date(dep_time) = days
              and dep_time is not null
              and dep = '" . $a['icao'] . "'
      ) as " . $a['icao'] . "_Out,
      (
          SELECT count(*)
            from movements
            where date(arr_time) = days
              and arr_time is not null
              and arr = '" . $a['icao'] . "'
      ) as " . $a['icao'] . "_In, ";
    }
    $sql .= "(
    			SELECT count(*)
    				from movements
    				where date(dep_time) = days
    					AND dep_time is not null
    	) as Total_Out,
    	(
    			SELECT count(*)
    				from movements
    				where date(arr_time) = days
    					and arr_time is not null
    	) as Total_In";
        $sql .= "
    from movements
    group by date(days)
    order by date(days) desc
    limit 1, 7";


    $mvts = $conn->prepare($sql);
    $mvts->execute();
    $results = $mvts->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>';
    // print_r($results);
    // echo '</pre>';
    $mvts = [];
    $head = [];
    foreach($results as $day) {
    	foreach($day as $k => $mvt) {
    		if($k != 'days') {

    			$head[$day['days']] = $day['days'];
    			$cat = explode("_", $k);

            $mvts[$cat[0]][$cat[1]][]  = $mvt;


    		}
    	}
    }


    $tot = [];
    foreach($mvts as $icao=> $mvt) {
      // if($icao == 'EICK' || $icao == 'EIDW' || $icao == 'EINN') {
      //
      // }
      foreach($mvt as $kk => $a) {
        foreach($a as $kkk => $b) {
          if(!isset($mvts[$icao]['total'][$kkk])) {
            $mvts[$icao]['total'][$kkk] = 0;
          }
          $mvts[$icao]['total'][$kkk] += $b;
        }
      }
    }
    foreach($mvts as $icao => $mv) {
      if($icao == 'EIDW' || $icao == 'EICK' || $icao == 'EINN' || $icao == 'Total') {
        $tot[$icao] = $mv['total'];
      } else {
        for($i=0; $i<count($mv['total']); $i++) {
          if(!isset($tot['Regionals'][$i])) {
            $tot['Regionals'][$i] = 0;
          }
          $tot['Regionals'][$i] += $mv['total'][$i];
        }




      }
    }
    // echo '<pre>';
    // print_r($tot);
    // echo '</pre>';
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
    <canvas id="chart"></canvas>
    <script>
    var randomColorGenerator = function () {
        return '#' + (Math.random().toString(16) + '0000000').slice(2, 8);
    };

    new Chart(document.getElementById("chart"),
      {"type":"line",
      "data":
        {"labels":
          [
            <?php foreach($head as $h) {
              echo '"' . $h . '",';
            }
            ?>
          ],
        "datasets":
          [
            <?php
            foreach($tot as $icao => $m) {
              echo '{"label":"'.$icao.'",
                "data":[';

                for($i=0;$i<count($m);$i++) {
                  echo $m[$i] .',';
                }

              echo '],
              "fill":false,
              "borderColor":randomColorGenerator(),
              "lineTension":0.1
            },';


            }
            ?>

          ]
        },
        "options":{
          "responsive": true,
    			"scaleBeginAtZero": true
        }
      });
    </script>
