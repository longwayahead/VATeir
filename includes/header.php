<?php
ini_set('display_errors', 1);
session_start();
ob_start();
try {
  define ('URL', realpath($_SERVER['DOCUMENT_ROOT']) . '/'); // go to /training/includes/ and update the soft coded link too
  define ('BASE_URL', 'https://'.$_SERVER['HTTP_HOST'].'/');
  require_once(URL . "core/init.php");
  $user = new User;
} catch (Exception $e) {
  echo $e->getMessage();
}?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Irish vACC of the VATSIM Network!"/>
    <meta name="keywords" content="VATSIM VATeir Virtual ATC Air Traffic Control Virtual Air Traffic Simulation Network Ireland Dublin">
    <meta name="author" content="Cillian Long">
    <!--Facebook Optimisation-->
    <meta property="og:title" content="VATeir"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="https://i.img.ie/suw.png"/>
    <meta property="og:url" content="www.vateir.org"/>
    <meta property="og:description" content="The Irish vACC of the VATSIM Network!"/>
    <!--Twitter Optimisation-->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:url" content="www.vateir.org"/>
    <meta name="twitter:title" content="VATeir"/>
    <meta name="twitter:description" content="The Irish vACC of the VATSIM Network!"/>
    <meta name="twitter:image" content="https://i.img.ie/suw.png"/>
    <!--Safari Optimisation-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <title><?php echo (isset($pagetitle) ? 'VATeir | ' . $pagetitle : 'VATeir');?></title>
    <!-- Bootstrap
    <!-- <link href=<?php //echo BASE_URL . "css/bootstrap.min.css"; ?> rel="stylesheet"> -->
    <!-- <link href=<?php //echo BASE_URL . "css/paper.css"; ?> rel="stylesheet"> -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.6/paper/bootstrap.min.css" rel="stylesheet">
    <link href=<?php echo BASE_URL . "css/custom.css"; ?> rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/8.5.1/nouislider.min.css">
    <!-- <link href=<?php //echo BASE_URL . "css/slider.css"; ?> rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.14.30/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <!-- <link href=<?php //echo BASE_URL . "datetimepicker/bootstrap-datetimepicker.css"; ?> rel="stylesheet"> -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
    <!-- <script type="text/javascript">
        window.cookieconsent_options = {
          "message":"This website uses cookies to ensure you get the best experience on it",
          "dismiss":"Grand!",
          "learnMore":"More",
          "link":"<?php //echo BASE_URL . 'privacy.php'; ?>",
          "theme":"light-top"};
    </script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script> -->
    <!-- End Cookie Consent plugin -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-65017894-1', 'auto');
  ga('send', 'pageview');
</script>
</head>
<body>
<div class="container">
  <div class="masthead">
  	<h3 class="text-muted" style="display:inline;">
      <a style="text-decoration:none;" href="<?php echo BASE_URL;?>">
        <img class="img-responsive" style="display:inline;" width="200px" src="https://i.img.ie/suw.png">
      </a>

  	</h3>
  </div>
<?php
$directory = 0;
$dir = getcwd();
$directory = 0;
switch($directory) {
  case(strpos($dir, "training") != true):
    $directory = 1;
  break;
  case(strpos($dir, "events") != true):
    $directory = 2;
  break;
  case(strpos($dir, "pilots") != true):
    $directory = 4;
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
      <li <?php echo ($directory === 0) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL; ?>>Home</a></li>
      <?php if($user->isLoggedIn()) { ?>

          <li <?php echo ($directory == 1) ? 'class="active"' : '' ;?>>

          <a href="<?php echo BASE_URL . "training/"; ?>"; ?>Training</a></li>
            <li> <a target="_blank" href="<?php echo BASE_URL . 'training/calendar.php'?>">Calendar </a></li>
      <?php } ?>
      <li <?php echo ($directory == 2) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL . "events"; ?>>Events</a></li>
      <li <?php echo ($directory == 4) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL . "pilots"; ?>>Pilots</a></li>
      <li <?php echo ($directory == 5) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL . "controllers"; ?>>Controllers</a></li>
     <li <?php echo ($directory == 6) ? 'class="active"' : '' ;?>><a href=<?php echo BASE_URL . "about"; ?>>About Us</a></li>
     <!-- <li class="hidden-sm"><a href="https://www.twitch.tv/vatsim_atc/profile" target="_blank">Streams</a></li> -->

      <li><a target="_blank" href="<?php echo BASE_URL . 'forum'?>">Forum</a></li>
   </ul>
    <ul class="nav navbar-nav navbar-right">
    <?php
    if(!isset($_SESSION['user'])) {
        echo '<li><a href="' . BASE_URL . 'login/index.php">Login</a></li>';
    } else {
    ?>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <div style="display:inline-block;">
            <img src="<?php echo $user->getAvatarURL(['email' => $user->data()->email, 'size' => 20]); ?>" class="img-circle">
            <div class="hidden-sm" style="display:inline-block;"><?php echo $user->data()->first_name . ' ' . $user->data()->last_name; ?></div>
          </div>
          <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li>
              <a href="<?php echo BASE_URL . 'controllers/profile.php?id=' . $user->data()->id; ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Profile</a>
            </li>
            <li>
              <a href="<?php echo BASE_URL . 'training/availability.php';?>"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Availability</a>
            </li>
            <li> <a target="_blank" href="<?php echo BASE_URL . 'training/calendar.php'?>"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Calendar</a></li>
            <li>
              <a href="<?php echo BASE_URL . 'training/history.php';?>"><span class="glyphicon glyphicon-education" aria-hidden="true"></span> History</a>
            </li>
            <?php
              if($user->hasAdmin('operations')) { ?>
            <li class="divider"></li>

                <li>
                  <a href="<?php echo BASE_URL . 'operations/';?>"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Operations</a>
                </li>
              <?php }
             ?>
             <?php
               if($user->hasAdmin('admin')) { ?>
                 <li>
                   <a href="<?php echo BASE_URL . 'admin/';?>"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Admin</a>
                 </li>
               <?php }
              ?>
            <li class="divider"></li>
            <li>
              <a href=<?php echo BASE_URL . "login/logout.php"; ?>><span class="glyphicon glyphicon-off" aria-hidden="true"></span> Logout</a>
            </li>
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
