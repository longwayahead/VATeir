$("[data-toggle=popover]").popover();
$("[data-toggle=tooltip]").tooltip();

$('html').click(function(e) {
  if( !$(e.target).parents().hasClass('popover') ) {
    $('#popover_parent').popover('destroy');
  }
});