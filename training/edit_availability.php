<?php
$pagetitle = "Edit Availability";
require_once('includes/header.php');
$a = new Availability;
if(Input::exists('post')) {

  try{
  	$validate = new Validate;
  	$validation = $validate->check($_POST, array(
  		'date' => array(
  			'field_name' => 'Date',
  			'required' => true
  			),
  		'from' => array(
  			'field_name' => 'Time',
  			'required' => true
  			),
  		'to' => array(
  			'field_name' => 'Time Until',
  			'required' => true
  			)
  		));
  	if($validation->passed()) {
  		$test = $a->edit(array(
        'date'  => Input::get('date'),
        'time_from'  => Input::get('from').':00',
        'time_until'  => Input::get('to').':00'
      ), 
      	[
      		['id', '=', Input::get('id')],
      		['cid', '=', $user->data()->id]
      	]
      );
     	Session::flash('success', 'Availability edtited.');
    	Redirect::to('./availability.php#myavailability');
  	}
	  
  } catch(Exception $e) {
    echo $e->getMessage();
  }
}

$availability = $a->get(['id' => Input::get('id')])[0];


?>
<div class="row">
<div class="col-md-10 col-md-offset-1">
 <h3 class="text-center">My Availability</h3><br>
    <div class="panel panel-default">
      <div class="panel-heading">Edit Availability</div>
      <div class="panel-body">

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
                  <div class="col-lg-10 col-lg-offset-4">
                  	<input type="hidden" name="id" value="<?php echo $availability->availability_id; ?>">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
          </fieldset>
        </form>
      </div>
      </div>
    </div>
  </div>
</div>
</div>

<?php
require_once('../includes/footer.php');
?>


<script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker({
          format: 'YYYY-MM-DD',
          collapse: true,
          defaultDate: '<?php echo date("Y", strtotime($availability->date)) . '-' . date("m", strtotime($availability->date)) . '-' . date("d", strtotime($availability->date)) ?>'});
    });

    $(function () {
        $('#datetimepicker2').datetimepicker({
          format: 'HH:mm',
          stepping: '60',
          defaultDate: '<?php echo date("Y", strtotime($availability->date)) . '-' . date("m", strtotime($availability->date)) . '-' . date("d", strtotime($availability->date)) . 'T' . $availability->time_from; ?>'
        });
    });
   

    $(function () {
        $('#datetimepicker3').datetimepicker({
          format: 'HH:mm',
          stepping: '60',
          defaultDate: '<?php echo date("Y", strtotime($availability->date)) . '-' . date("m", strtotime($availability->date)) . '-' . date("d", strtotime($availability->date)) . 'T' . $availability->time_until; ?>'
        });
       
    });
</script>