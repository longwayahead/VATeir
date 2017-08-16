<?php
$pagetitle = 'Donate to VATeir';
require_once('includes/header.php') ?>
<div class="row">
  <div class="col-md-6 col-md-offset-3">
      <h4 class="text-center">Please consider donating to support VATeir</h4><br>
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Donate</h3>
        </div>
        <div class="panel-body">
          <div style="font-size:16px">
            <p>The payments to run the VATeir web services are made from the private funds of two individuals. This money is paid on the 24th of each month to keep the web server, email server, and databases online. To help pay for these services provided by VATeir, we have set up a PayPal donation option. Any donation, no matter how small, is greatly appreciated. Below is an agreement that we must place on the site as per our agreement with the VATSIM Board of Governors.<br></p>
            <p><strong>Our Agreement</strong><br>

            <ul>
              <li>Every EUR donated will go towards funding our web services. As we will be using the PayPal system to accept donations, your financial commitment will be guaranteed towards funding our web services.</li>
            <li>VATeir will continue to work proactively to grow the Irish community and will do its utmost to constantly improve the final product offered to the membership.</li>
            <li>We will not disclose the details of anyone who has donated, or how much they have donated.</li>
            <li>We will not treat those who do or don’t donate any differently, as below.</li></ul>

            <p><strong>Your Agreement</strong><br>
              <ul><li>By donating, you agree that your financial contribution is solely an act of goodwill, and that such a contribution does not grant you with any higher say in how the VACC is run. You agree that you will never use the fact that you have made a financial contribution as a tool to attempt to influence or intimidate staff members or the general membership.</li>
            <li>You will not use the fact that you have made a financial contribution for elevating your status in the VACC, nor to attempt to “bribe” the VACC into granting you special privileges.</li>
            <li>Once you have donated, you have surrendered your right to claim your money back at any time, unless your financial contribution does not go towards running costs for web services, as described.</li>
            <li>You confirm that you are 18 years or older.</li></ul>
            </p>
            <p>If you are in understanding of this agreement, and wish to place a donation, please click the button below. Thank you so much for your support!</p>
            <div class="text-center">
              <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        				<input type="hidden" name="cmd" value="_s-xclick">
        				<input type="hidden" name="hosted_button_id" value="JG4HJV7WBLXFN">
        				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        			</form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php require_once('includes/footer.php'); ?>
