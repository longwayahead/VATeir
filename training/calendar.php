<?php
$pagetitle = "My Calendar";
require_once('includes/header.php');
$c = new Calendar;
$hash = $c->getHash($user->data()->id);

try {
  if(Input::exists()) {
    if(Input::get('delete')) {
        $c->delete($user->data()->id);
        Session::flash('success', 'Access token deleted!');
    } elseif(Input::get('generate')) {
        $c->make($user->data()->id);
        Session::flash('success', 'Access token generated!');
    }
    Redirect::to('./calendar.php');
  }
  if(isset($_GET['e'])) {
    echo 'hi';
   if($_GET['e'] == 0) {
     echo 'tried to set to 0';
     $c->edit(['events' => 0],[['cid', '=', $user->data()->id]]);
   } else {
     $c->edit(['events' => 1],[['cid', '=', $user->data()->id]]);
   }
   Session::flash('success', 'Preferences updated!');
   Redirect::to('./calendar.php');
 }


} catch(Exception $e) {
  Session::flash('error', $e->getMessage());
  Redirect::to('./calendar.php');
}


?>
<h3 class="text-center">My Calendar Link</h3>
<div class="col-md-6 col-md-offset-3">
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">What is this?</h3>
      </div>
      <div class="panel-body">
        Get forthcoming sessions pushed to your calendar app automatically.<br>
        <br>
        <strong>Select your OS for a how-to guide</strong>
        <p><a href="http://www.imore.com/how-subscribe-calendars-your-iphone-or-ipad" target="_blank" class="btn btn-xs btn-primary">iOS</a>
        <a href="https://support.google.com/calendar/answer/37100?hl=en" target="_blank" class="btn btn-xs btn-primary">Android</a>
        <a href="http://www.howtogeek.com/howto/30834/add-an-ical-or-.ics-calendar-to-google-calendar/" target="_blank" class="btn btn-xs btn-primary">Google Calendar</a>
        <a href="https://support.apple.com/en-ie/HT202361" target="_blank" class="btn btn-xs btn-primary">Mac</a>
        <a href="https://support.office.com/en-ie/article/View-and-subscribe-to-Internet-Calendars-f6248506-e144-4508-b658-c838b6067597" target="_blank" class="btn btn-xs btn-primary">Outlook</a></p>
      </p>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">My access URL</h3>
      </div>
      <div class="panel-body">
        <?php if($hash === false) { ?>
          <form id="gener" action="" method="post" onsubmit="document.getElementById('generate').disabled=true;">
            <div class="form-group col-md-offset-4">
  					<input type="submit" name="generate" class="btn btn-lg btn-primary" value="Generate">
  				</form>
        <?php } else { ?>
          <table class="table table-condensed table-responsive">
            <tr>
              <td>My URL:</td>
              <td><samp><?php echo BASE_URL . 'calendar/?h=' . $hash->hash; ?></samp></td>
            </tr>
            <tr>
              <td>Events Included:</td>
              <td><?php echo ($hash->events == 1) ? '<a href="calendar.php?e=0" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>' : '<a href="calendar.php?e=1" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>'; ?></td>
            </tr>
          </table>
          <form id="hash" class="form" action="" method="post" onsubmit="document.getElementById('delete').disabled=true;document.getElementById('request').disabled=true;">
            <div class="form-group col-md-offset-3">
    					<input name="delete" type="submit" class="btn btn-danger" value="Delete">
    					<input type="submit" name="generate" class="btn btn-primary" value="Re-generate">
    				</form>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<?php
// echo '<pre>';
// print_r($c->calendarMake("04f409627e"));
// echo '</pre>';
require_once('../includes/footer.php');
