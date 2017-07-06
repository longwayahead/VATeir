<?php
// Create a global configuration
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' 		=> '',
		'username' 	=> '',
		'password' 	=> '',
		'db' 		=> ''
	),
	'vateud' => array(
		'apitoken'		=> '[REQUEST VACC ACCESS FROM VATEUD]',
		'vaccmembers'	=> 'api.vateud.net/emails/irl.json'
	),
	'vatsim' => array(
		'valpath'		=> URL . 'atc_solo/solo.txt', //path to validation files used by network supervisors
		'valduration' 	=> '8' //in weeks
		),
	'session' => array(
		'session_name'	=> 'user',
		'token_name'	=> 'token'
	),
	'oad' => array(
		'apitoken'	=> '' //openaviationdata api
	)
);

//Include functions

require_once(URL . 'functions/functions.php');


// Autoload classes

function autoload($class) {
	require_once (URL . 'classes/' . $class . '.php');
}
spl_autoload_register('autoload');
