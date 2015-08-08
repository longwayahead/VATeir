<?php
require_once('includes/header.php');
$d = new Download;
$file = $d->get(Input::get('id'), "id")[0];
$unlink = $d->unlink($file->file_name);
if($unlink == true) {
	$delete = $d->delete($file->id);
	if($delete == true) {
		Session::flash('success', 'Download Deleted');
		Redirect::to('./downloads.php');
	}
}
