<?php
session_start();
if(isset($_POST['auth_token']) == false) {
  echo 'Auth token not supplied.';
} else {

  if(isset($_POST['auth_token']) == false || $_POST['auth_token'] !== $_SESSION['atkn']) { //if auth token not in form submit, or if form token does not match session token
    echo 'Invalid auth token.';
  } else {
    unset($_SESSION['atkn']);
    require_once('../statistics/db.php');
    $get = $conn->prepare("SELECT s.cid, s.position, v.first_name, v.last_name,
    (SELECT SUM(time_to_sec(TIMEDIFF(finish, start))) FROM sessions WHERE cid = s.cid AND position = s.position AND YEAR(CURRENT_DATE() - interval 1 month) = YEAR(finish)
    	AND MONTH(CURRENT_DATE() - interval 1 month) = MONTH(finish)) as duration
    FROM sessions s
    LEFT JOIN vateir.controllers v ON v.id = s.cid
    WHERE YEAR(CURRENT_DATE() - interval 1 month) = YEAR(s.finish)
    	AND MONTH(CURRENT_DATE() - interval 1 month) = MONTH(s.finish)
    GROUP BY s.cid, s.position
    ORDER BY  s.facility DESC, v.last_name DESC
    ");
    $get->execute();
    $results = $get->fetchAll(PDO::FETCH_ASSOC);
    $stats = [];
    foreach($results as $r) {
      $name = $r['first_name'] . ' ' . $r['last_name'];
      $stats['cid'][$name][$r['position']] = $r['duration'];
      if(isset($stats['total'][$r['position']]) == false) {
      $stats['total'][$r['position']] = '0';
      }
      $stats['total'][$r['position']] += $r['duration'];
    }

    $out = [];

    $overall_time = 0;
    foreach($stats['total'] as $pos => $time) {
      $out[0][] = $pos;
      $out[1][] = $time;
    	$overall_time += $time;
    }
    $out[1]['total'] = $overall_time;
    foreach($stats['cid'] as $cid => $q) {
    	$controller_total = 0;
      foreach($q as $p => $t) {
        $out['cid'][$cid][array_search($p, $out[0])] = $t;
    		$controller_total += $t;
      }
    	$out['cid'][$cid]['total'] = $controller_total;
    }
    function timeLength($sec)
    {
        $s=$sec % 60;
        $m=(($sec-$s) / 60) % 60;
        $h=floor($sec / 3600);
        return $h.":".substr("0".$m,-2).":".substr("0".$s,-2);
    }
        //headers
    	//CSV STUFF
      $csv[0][] = date("F Y", strtotime("-1 month"));
    	$csv[1][0] = '';
    		foreach($out[0] as $key => $v) {
    			$csv[1][$key+1] = $v;
    		}
    	$csv[1][] = 'Total';
    	//\CSV STUFF
    end($out[0]);
    $total = key($out[0]);
    //cids
    foreach($out['cid'] as $controller_name => $z) {
    	//CSV STUFF
    	$csv[$controller_name][] = $controller_name;
    	//\CSV STUFF
      for($i = 0; $i <= $total; $i++) {
          if(array_key_exists($i, $z)) {
    				//CSV STUFF
    				$csv[$controller_name][] = $z[$i];
    				//\CSV STUFF
          } else {
    				$csv[$controller_name][] = 0;
          }
      }
    	$csv[$controller_name][] = $z['total'];

    }

    	$csv['totals'][] = 'Total';
    	foreach($out[1] as $total_time) {
    		$csv['totals'][] = $total_time;
    	}
      header('Content-Type: text/csv; charset=utf-8');
      header('Content-Disposition: attachment; filename='. date("m.Y", strtotime("- 1 month")) . '_month_controller_stats.csv');

    $fp = fopen('php://output', 'w');

    foreach ($csv as $fields) {
        fputcsv($fp, $fields);
     }

    fclose($fp);
  }
}
