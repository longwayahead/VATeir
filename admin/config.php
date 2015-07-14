<?php
require_once("includes/header.php");
if(isset($_GET['login'])) {
	if($_GET['login'] == 1) {
		$config = $a->config(['setting' => 1], [['config', '=', 'login']]);
	} elseif($_GET['login'] == 0) {
		$config = $a->config(['setting' => 0], [['config', '=', 'login']]);
	}
	Session::flash('success', 'Site config updated.');
	Redirect::to('index.php');
}