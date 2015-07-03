<?php
require_once('includes/header.php');
$s = new Sessions;
print_r($s->countMentor($user->data()->id));

