<?php
if($_SESSION['token'] == $_GET['token']) {
        $id = $_POST['id'];



    define('IN_PHPBB', true);
    $phpbb_root_path = './';    //Path to forum
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);



    function phpbbAutoLogin($id) //User id from phpbb users table
    {
        global $phpbb_root_path, $phpEx, $user;

        $user->session_begin(); //Start Session
        $user->session_create($id); //Create Session

        //Check if User has successfully Logged in
        if($user->data['is_registered']==1 && $user->data['user_type'] != USER_INACTIVE && $user->data['user_type'] != USER_IGNORE)
        {
            header("Location: " . append_sid("index.$phpEx"));
        }
        else
        {
            echo 'Error Logging In';
        }
    }
    //Auto Login User with phpBB User ID 2 (u/name = "admin")
    phpbbAutoLogin($id);
    echo '<a target="_blank" href="' . $phpbb_root_path . append_sid("index.$phpEx").'"> Link </a>';

    echo '<pre>';
    print_r($user);
    echo '</pre>';
}
?>