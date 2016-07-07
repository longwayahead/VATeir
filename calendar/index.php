<?php
header('Content-type: text/calendar; charset=utf-8');
define ('URL', realpath($_SERVER['DOCUMENT_ROOT']) . '/');
define ('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
require_once(URL . 'core/init.php');
$c = new Calendar;
if(Input::exists('get')) {
  try {
  echo $c->calendarMake(Input::get('cid'));
  } catch(Exception $e) {
    echo $e->getMessage();
  }
} else {
  echo 'No CID specified';
}
