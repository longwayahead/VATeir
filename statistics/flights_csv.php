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
    	) as Total_In ";
        $sql .= "
    from movements
    where YEAR(logon_time) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)
      AND MONTH(logon_time) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)
    group by date(days)
    order by date(days) desc";
//  where YEAR(logon_time) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) ////////////////for last month's results
//AND MONTH(logon_time) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)

    $mvts = $conn->prepare($sql);
    $mvts->execute();
    $results = $mvts->fetchAll(PDO::FETCH_ASSOC);
    // echo '<pre>';
    // print_r($results);
    // echo '</pre>';
    foreach($results[0] as $head => $val) {
      $csv[0][] = $head;
    }
    foreach($results as $v) {
      $csv[] = $v;
    }

    $fp = fopen('../uploads/ops/movements/'.date("Y_m", strtotime("- 1 month")) . '_movements_stats.csv', 'w');
    //   header('Content-Type: text/csv; charset=utf-8');
    //   header('Content-Disposition: attachment; filename='. date("m.Y", strtotime("-1 month")) . '.csv');
    //
    // $fp = fopen('php://output', 'w');

    foreach ($csv as $fields) {
        fputcsv($fp, $fields);
     }

    fclose($fp);
