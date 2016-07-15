<?php
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');
define ('URL', realpath($_SERVER['DOCUMENT_ROOT']) . '/');
define ('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
require_once(URL . 'core/init.php');
$c = new Calendar;

if(Input::get('h')) {
  try {
  echo $c->calendarMake(Input::get('h'));
  } catch(Exception $e) {
    echo $e->getMessage();
  }
} else {
  echo 'No authcode';
}
