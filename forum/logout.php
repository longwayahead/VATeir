<?php
define('IN_PHPBB', true);
$phpbb_root_path = './';    //Path to forum
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('ucp');

if($user->data['is_registered'])
{
    $user->session_kill();
    $user->session_begin();

    $redirect = request_var('redirect', "../index.$phpEx");
    meta_refresh(1, $redirect);
    
    trigger_error('LOGOUT_REDIRECT');
}
else
{
    trigger_error('LOGOUT_FAILED');
}
?>