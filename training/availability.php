<?php
$pagetitle = "My Availability";
require_once('includes/header.php');
$a = new Availability;
if(Input::exists()) {

  try{
  	$validate = new Validate;
  	$validation = $validate->check($_POST, array(
  		'date' => array(
  			'field_name' => 'Date',
  			'required' => true
  			),
  		'from' => array(
  			'field_name' => 'Time From',
  			'required' => true,
        	'time_less' => 'to',
        	'time_same'	=> 'to'
  			),
  		'to' => array(
  			'field_name' => 'Time Until',
  			'required' => true
  			)
  		));
  	if($validation->passed()) {
	  $a->add(array(
        'cid'   => $user->data()->id,
        'date'  => Input::get('date'),
        'time_from'  => Input::get('from').':00',
        'time_until'  => Input::get('to').':00'
      ));
     	Session::flash('success', 'Availability added.');
    	Redirect::to('./availability.php');
    } else {
    	echo '<div class="row">
            <div class="col-md-6 col-md-offset-3">
              <div class="panel panel-danger">
                <div class="panel-heading">
                  <h3 class="panel-title">The following errors occured:</h3>
                </div>
                <div class="panel-body">';
                  foreach($validation->errors() as $error) {
                  echo $error.'<br>';
                }
                echo '</div>
              </div>
            </div></div>
      ';
    }
  } catch(Exception $e) {
    echo $e->getMessage();
  }
}

?>
  <div class="row">
  <div class="col-md-10 col-md-offset-1">
 <h3 class="text-center">My Availability</h3><br>
    <div class="panel panel-default">
      <div class="panel-heading">Add Availability</div>
      <div class="panel-body">
      <?php if($user->data()->rating < 5) { ?>
        <div class="col-md-8 col-md-offset-2">
        <form class="form-horizontal" action="" method="post">
          <fieldset>
            <div class="form-group">
              <label for="inputEmail" class="col-lg-2 control-label">Date</label>
              <div class="col-lg-6 col-lg-offset-1">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' name="date" class="form-control"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword" class="col-lg-2 control-label">Time</label>
              <div class="col-lg-8">
                <div class="col-md-5">
                  <div class='input-group date' id='datetimepicker2'>
                      <input type='text' name="from" class="form-control" />
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-time"></span>
                      </span>
                  </div>

                </div>
                <div class="col-md-2">
                to
                </div>
                <div class="col-md-5">
                  <div class='input-group date' id='datetimepicker3'>
                      <input type='text' name="to" class="form-control" />
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-time"></span>
                      </span>
                  </div>
                </div>
                <br>
                
              </div>
            </div>
            <br>
            <div class="form-group">
                  <div class="col-lg-10 col-lg-offset-3">
                    <button type="reset" class="btn btn-default">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
          </fieldset>
        </form>
      </div>
      <?php } else { ?>
        <div class="text-danger text-center" style="font-size:16px;"><br>Your training is finished.</div><br>
      <?php } ?>
      </div>
    </div>
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title" id="myavailability">My Availability</h3>
      </div>
      <div class="panel-body">
      <?php
      try {
        $availabilities = $a->get(array(
            'student' => $user->data()->id,
            'deleted' => 0
          ));
          if(!empty($availabilities)) {
           ?>
           <table class="table table-condensed table-striped">
            <tr>
              <td>
                <strong>Date</strong>
              </td>
              <td>
                <strong>Time From</strong>
              </td>
              <td>
                <strong>Time Until</strong>
              </td>
              <td>
                <strong>Edit</strong>
              </td>
              <td>
                <strong>Delete</strong>
              </td>
            </tr>
            <?php foreach($availabilities as $availability): ?>
              <tr>
                <td><?php echo date("j-M-y", strtotime($availability->date));?></td>
                <td><?php echo date("H:i", strtotime($availability->time_from));?></td>
                <td><?php echo date("H:i", strtotime($availability->time_until));?></td>
                <td><?php echo '<a class="btn btn-xs btn-default" href="edit_availability.php?id=' . $availability->availability_id . '"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>';?></td>
                <td><?php echo '<a class="btn btn-xs btn-default" href="delete_availability.php?id=' . $availability->availability_id . '"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>';?></td>
              </tr>
            <?php endforeach; ?>
          </table>
          <?php
        } else {
          echo '<br><div class="row">
                <div class="col-md-6 col-md-offset-3">
              <div class="text-danger text-center" style="font-size:16px;">No Availability</div><br>
      
              </div></div>';}
      } catch(Exception $e) {
        echo $e->getMessage();
      }
      ?>
       
      </div>

    </div>
    </div>

  </div>
</div>

<?php
require_once('../includes/footer.php');
?>


<script type="text/javascript">
    var today = new Date();
    $(function () {
        $('#datetimepicker1').datetimepicker({
          format: 'YYYY-MM-DD',
          minDate: today,
          collapse: true
        });
    });

    $(function () {
        $('#datetimepicker2').datetimepicker({
          format: 'HH:mm',
          stepping: '60'
        });
    });
   

    $(function () {
        $('#datetimepicker3').datetimepicker({
          format: 'HH:mm',
          stepping: '60'
        });
       
    });
</script>