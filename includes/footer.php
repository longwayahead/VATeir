</div> <!-- Container -->
Footer working...huzaah!
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src=<?php echo BASE_URL . "js/bootstrap.min.js";?>></script>
    <script src=<?php echo BASE_URL . "js/global.js";?>></script>
    <script src=<?php echo BASE_URL . "js/jquery.nouislider.all.min.js";?>></script>
    <script src=<?php echo BASE_URL . "js/jquery.flip.js";?>></script>
    <script src=<?php echo BASE_URL . "js/readmore.min.js";?>></script>
    <script src=<?php echo BASE_URL . "js/timeago.js";?>></script>
    <script src=<?php echo BASE_URL . "datetimepicker/moment.js";?>></script>
    <script src=<?php echo BASE_URL . "datetimepicker/bootstrap-datetimepicker.min.js";?>></script>
    
<?php if(isset($user) && $user->isLoggedIn() && $user->data()->id == 937032) {
    ?>
        <script>
        function pulse() {
            $('.blink').fadeIn(300);
            $('.blink').fadeOut(500);
        }
        setInterval(pulse, 2000);
		</script>
        <?php
        }
        ?>
        <script>
            jQuery(document).ready(function() {
              jQuery("time.timeago").timeago();
            });
        </script>
        
    </div>
  </body>
</html>

<?php
echo '<br>Site:';
echo memory_get_usage();

?>