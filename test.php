<?php
require_once('includes/header.php');
$r = new Reports;
$sliders = $r->getSliders(1, 2, 1);
print_r($sliders);