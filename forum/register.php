<?php
session_start();
require_once('../classes/Token.php');
require_once('../classes/Session.php');

$data = unserialize($_POST['data']);
if($_SESSION['token'] == $_POST['token']) {
$token = new Token;
$tok = $token->generate();

define('IN_PHPBB', true);
$phpbb_root_path = './';  // Your path here
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require_once($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

require($phpbb_root_path .'includes/functions_user.php');


$username = $data['username'];
$password = substr(str_shuffle(MD5(microtime())), 0, 10); // Do NOT encrypt !
$email  = $data['email']; // Please validate this email yourself ! Phpbb will accept non-valid emails.
$vatsim_id = $data['vatsim_id'];


// Do a check if username is allready there, same for email, otherwhise a nasty error will occur

$user_row = array(
'username' => $username,
'vatsim_id'	=> $vatsim_id,
'user_password' => md5($password),
'user_email' => $email,
'group_id' => 2, //Set the usergroup
'user_timezone' => 'Europe/Dublin',
'user_lang' => 'en',
'user_type' => '0',
'user_actkey' => '',
'user_dateformat' => 'd M Y H:i',
'user_style' => 1,
'user_regdate' => time(),
);

$phpbb_user_id = user_add($user_row);
$_SESSION['forum_id'] = $phpbb_user_id;
echo '<form id="form" action="login.php" method="post">
			<input type="hidden" name="token" value="' . $tok . '">
			<input type="hidden" name="forum_id" value="' . $phpbb_user_id . '">
		</form>
		<script type="text/javascript">
			document.getElementById("form").submit();
		</script>';



echo "New user id = ".$phpbb_user_id;














}

