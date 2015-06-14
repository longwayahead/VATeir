<?php
session_start();
ob_start();
define ('URL', realpath($_SERVER['DOCUMENT_ROOT']) . '/'); // go to /training/includes/ and update the soft coded link too
define ('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
require_once(URL . "core/init.php");
$user = new User;

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo (isset($pagetitle) ? 'VATeir | ' . $pagetitle : 'VATeir');?></title>

    <!-- Bootstrap -->
    <!-- <link href=<?php //echo BASE_URL . "css/bootstrap.min.css"; ?> rel="stylesheet"> -->
    <link href=<?php echo BASE_URL . "css/paper.css"; ?> rel="stylesheet">
    <link href=<?php echo BASE_URL . "css/custom.css"; ?> rel="stylesheet">
    <link href=<?php echo BASE_URL . "css/slider.css"; ?> rel="stylesheet">
    <link href=<?php echo BASE_URL . "datetimepicker/bootstrap-datetimepicker.css"; ?> rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
<div class="container">
<?php if($user->isLoggedIn() && $user->data()->id == 937032) {
  ?>
<div style="color:red; font-size:100px;" class="text-center blink">GO FUCK YOURSELF STEPHEN DONNELLY</div>
<?php
}
?>
<div class="masthead">
	<h3 class="text-muted" style="display:inline;">
    <a href="<?php echo BASE_URL;?>">
      <img class="img-responsive" style="display:inline;" width="200px" src=<?php echo BASE_URL . "img/logo.png"; ?> \>
    </a>
	</h3>
</div>
<?php
$dir = getcwd();
$directory = 0;
switch($directory) {
  case(strpos($dir, "training") != true):
    $directory = 1;
  break;
  case(strpos($dir, "events") != true):
    $directory = 2;
  break;
  case(strpos($dir, "controllers") != true):
    $directory = 5;
  break;
  case(strpos($dir, "about") != true):
    $directory = 6;
  break;
   case(strpos($dir, "admin") != true):
    $directory = 7;
  break;
  default:
    $directory = 0;
  break;
}
?>

<div class="navbar navbar-default">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
  </div>
  <div class="navbar-collapse collapse navbar-responsive-collapse">
    <ul class="nav navbar-nav">
      <li <?php echo ($directory == 0) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL; ?>>Home</a></li>
      <li <?php echo ($directory == 1) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL . "training"; ?>>Training</a></li>
      <li <?php echo ($directory == 2) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL . "events"; ?>>Events</a></li>
      <li><a href="#">Pilots</a></li>
      <li <?php echo ($directory == 5) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL . "controllers"; ?>>Controllers</a></li>
     <li <?php echo ($directory == 6) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL . "about"; ?>>About Us</a></li>
      <li><a href="#">Forum</a></li>
      <?php
      if($user->isLoggedIn() && $user->hasPermission('admin')) {
       echo '<li';
       echo ($directory == 7) ? ' class="active"' : '' ;
       echo '><a href="'. BASE_URL . 'admin/">Admin</a></li>';
      }
      ?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
    <?php
    if(!$user->isLoggedIn()) {
        echo '<li><a href="' . BASE_URL . 'login/">Login</a></li>';
    } else {
    ?>
      
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span><?php echo ' ' . $user->data()->first_name . ' ' . $user->data()->last_name ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="#">Controller Profile</a></li>
          <li><a href="#">My Notifications <span class="badge danger">5</span></a></li>
          <li class="divider"></li>
          <li><a href=<?php echo BASE_URL . "login/logout.php"; ?>><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a></li>
        </ul>
      </li>
      <?php
      }
      ?>
    </ul>
  </div>
</div>
<?php
if(Session::exists('success')) {
  echo '<div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="alert alert-dismissable alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Success!     </strong>' , Session::flash('success'), '
        </div>
      </div>
    </div>';
} elseif (Session::exists('error')) {
  echo '<div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="alert alert-dismissable alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Error!     </strong>' , Session::flash('error'), '
        </div>
      </div>
    </div>';
} elseif (Session::exists('info')) {
  echo '<div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="alert alert-dismissable alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Some Info!     </strong>' , Session::flash('info'), '
        </div>
      </div>
    </div>';
}
?>