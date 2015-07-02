<?php
require_once('includes/header.php');
$f = new Forum;
$un = 'Josh Spinck';
$update = $f->update(['username' => $un, 'username_clean' => strtolower($un)], [['vatsim_id', '=', 1032602]]);