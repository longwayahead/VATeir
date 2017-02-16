<?php
session_start();
if(($_SESSION['token'] == $_SESSION['forum']['token']) && ($_SESSION['forum_id'] == $_SESSION['forum']['forum_id'])) {
    $id = $_SESSION['forum']['forum_id'];

    define('IN_PHPBB', true);
    $phpbb_root_path = './';    //Path to forum
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    function phpbbAutoLogin($id) //User id from phpbb users table
    {
        global $phpbb_root_path, $phpEx, $user;

        $user->session_begin(); //Start Session
        $user->session_create($id, false, true, true); //User id////persist login//view online//

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
    phpbbAutoLogin($id);
    echo '<a target="_blank" href="' . $phpbb_root_path . append_sid("index.$phpEx").'"> Link </a>';

    echo '<pre>';
    print_r($user);
    echo '</pre>';
} else {
    header("Location: ./index.php");
}
?>
