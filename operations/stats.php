<?php
session_start();
if(isset($_POST['auth_token']) == false) {
  echo 'Auth token not supplied.';
} else {

  if(isset($_POST['auth_token']) == false || $_POST['auth_token'] !== $_SESSION['atkn']) { //if auth token not in form submit, or if form token does not match session token
    echo 'Invalid auth token.';
  } else {
    unset($_SESSION['atkn']);
    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=VATeir_Data.csv');
    define ('URL', realpath($_SERVER['DOCUMENT_ROOT']) . '/');
    define ('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
    require_once(URL . 'core/init.php');
    $g = new Graph;
    $data = $g->sa(120);
    $csv = fopen('php://output', 'w');
    $output[]=['month', 'availability', 'sessions', 'noshow', 'cancelled'];
    foreach($data as $month => $number) {
      $output[] = [
        $month,
        $number['availability'],
        $number['sessions'],
        $number['noshow'],
        $number['cancelled']
      ];

    }

    foreach ($output as $line) {

        fputcsv($csv, $line);


    }

    fclose($csv);

  }



}
