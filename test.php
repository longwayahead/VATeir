<?php
require_once('includes/header.php');
// $ij = $user->innerjoin();
// echo '<pre>';
// print_r($ij);
// echo '</pre>';
// $t = new Training;
// foreach($ij as $i) {
// 	$program = $t->program($i->rating);
// 	$studentMake = $t->createStudent(array(
// 		'cid'		=> $i->id,
// 		'program'	=> 	$program
// 	));
// }

$e = new Events;
$events = $e->sessions();
echo '<pre>';
print_r($events);
echo '</pre>';
