<?php
require_once("includes/header.php");
$t = new Training;
$t->deleteVisitingCID(Input::get('id'));
Session::flash('success', 'CID Deleted.');
Redirect::to('visiting.php');
