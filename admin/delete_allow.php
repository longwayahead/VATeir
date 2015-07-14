<?php
require_once("includes/header.php");
$a = new Admin;
$a->deleteAllow(Input::get('id'));
Session::flash('success', 'CID Deleted.');
Redirect::to('allow.php');