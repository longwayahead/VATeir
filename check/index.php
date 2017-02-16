<?php
$pagetitle = 'Check a CID';
require_once('../includes/header.php');
?>
<div class="container">
  <h3 class="text-center">CID Status Check</h3>
  <div class="text-center col-md-6 col-md-offset-3">
    <p>The output of this form is arranged hierarchically from top to bottom. All queries must return green.</p>
    <p>The bottlekneck in registering your VATSIM CID with the VATeir database will be indicated by a red arrow next to the elemement which is causing issues.</p>
    <p>Click on the red arrow to find out more information, including potential steps that can be taken to solve the issue.</p>
  </div>
  <div class="col-md-6 col-md-offset-3">
     <div class="panel panel-primary">
       <div class="panel-heading">
      	  <h3 class="panel-title">
            <?php
      		    if(!Input::exists('post') || !Input::exists('get')) {
                echo 'Is your CID registered with VATeir?';
              } else {
                echo 'Results for ' . Input::get('cid');
              }
            ?>
      	  </h3>
      </div>
      <div class="panel-body text-center">
        <?php
        if(Input::exists('post') || Input::exists('get')) {

          $c = new Check;
          $check = $c->check(Input::get('cid'));
          // echo '<pre>';
          // print_r($check);
          // echo '</pre>';

          ?>
          <table class="table table-responsive table-striped table-condensed">

            <?php if(!empty($check['vateud'])) : ?>
            <tr>
              <td rowspan="3">VATEUD API</td>
              <td>VATSIM Account:</td>
              <td><?php echo ($check['vateud']['account'] == 1) ? '<div class="text-success">Found</div>' : '<div class="text-danger">Not found</div>';?></td>
              <td><?php echo ($check['vateud']['account'] == 0 && $check['vateud']['eud'] == 0) ? '<button class="btn btn-xs btn-danger glyphicon glyphicon-arrow-left" aria-hidden="true"></button>' : '';?></td>
            </tr>

            <tr>
              <td>Division = EUD:</td>
              <td><?php echo ($check['vateud']['eud'] == 1) ? '<div class="text-success">Set</div>' : '<div class="text-danger">Not set</div>';?></td>
              <td><?php echo ($check['vateud']['eud'] == 0 && $check['vateud']['account'] == 1) ? '<a tabindex="0" class="btn btn-xs btn-danger" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-html="true" title="Set division" data-content="If you haven\'t selected a division yet '. htmlentities('<a target="_blank" href="https://cert.vatsim.net/vatsimnet/divas.php">select one</a>.<br>If you have already set your division, please allow a number of days for the setting to be picked up.') . '"><span class="glyphicon glyphicon-arrow-left"></span></a>' : '';?></td>
            </tr>
            <tr>
              <td>vACC = VATeir:</td>
              <td><?php echo ($check['vateud']['irl'] == 1) ? '<div class="text-success">Set</div>' : '<div class="text-danger">Not set</div>';?></td>
              <td><?php echo ($check['vateir']['irl'] == 0 && $check['vateud']['eud'] == 1) ? '<a tabindex="0" class="btn btn-xs btn-danger" data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Email VATEUD" data-content="If you haven\'t selected a vACC, email members@vateud.net and request that your account be assigned to VATeir."><span class="glyphicon glyphicon-arrow-left"></span></a>' : '';?></td>
            </tr>
            <?php endif; ?>
            <?php if(!empty($check['vateir'])) : ?>
            <tr>
              <td rowspan="2">VATeir Database</td>
              <td>Controller account:</td>
              <td><?php echo ($check['vateir']['controller'] == 1) ? '<div class="text-success">Found</div>' : '<div class="text-danger">Not found</div>';?></td>
              <td><?php echo ($check['vateud']['controller'] == 0 && $check['vateud']['irl'] == 1) ? '<a tabindex="0" class="btn btn-xs btn-danger" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-html="true" title="Please wait" data-content="Please allow a number of days for our database to register your VATEUD API account."><span class="glyphicon glyphicon-arrow-left"></span></a>' : '';?></td>
            </tr>
            <tr>
              <td>Student record:</td>
              <td><?php echo ($check['vateir']['student'] == 1) ? '<div class="text-success">Found</div>' : '<div class="text-danger">Not found</div>';?></td>
              <td><?php echo ($check['vateir']['student'] == 1 && $check['vateir']['controller'] == 0) ? '<a tabindex="0" class="btn btn-xs btn-danger" data-toggle="popover" data-trigger="focus" data-placement="bottom" data-html="true" title="Please wait" data-content="Set division" data-content="Please allow up to 24 hours for this issue to fix itself."><span class="glyphicon glyphicon-arrow-left"></span></a>' : '';?></td>
            </tr>
            <?php endif; ?>


          </table>


          <?php
        } else {

        ?>



        <form class="form form-horizontal" action="" method="post" onsubmit="document.getElementById('submit').disabled=true; document.getElementById('submit').value='Checking...';">
          <fieldset>
            <div class="form-group">
              <label for="cid" class="col-lg-2 col-lg-offset-3 control-label">CID</label>
              <div class="col-lg-4">
                <input type="number" min="1" required="required" class="form-control" id="cid" autocomplete="off" name="cid" value="<?php echo (Input::exists('post') || Input::exists('get')) ? Input::get('cid') : '';?>" placeholder="CID">
              </div>
            </div>
            <div class="form-group">
              <div class="col-lg-2 col-lg-offset-5">
                <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Check">
              </div>
            </div>
          </fieldset>
        </form>



        <?php
      } ?>
      </div>
    </div>
  </div>


</div>
<?php

require_once('../includes/footer.php');
