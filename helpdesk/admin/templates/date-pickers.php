<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: date-pickers.php
  Description: jQuery Date Picker
  
  Add in more parameters if required.

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

?>
jQuery(function() {
 jQuery('#from').datepicker({
  changeMonth: true,
  changeYear: true,
  monthNamesShort: <?php echo trim($msg_cal); ?>,
  dayNamesMin: <?php echo trim($msg_cal2); ?>,
  firstDay: <?php echo ($SETTINGS->weekStart=='sun' ? '0' : '1'); ?>,
  dateFormat: '<?php echo $MSDT->mswDatePickerFormat(); ?>',
  isRTL: <?php echo $msg_cal3; ?>
 });
});
jQuery(function() {
 jQuery('#to').datepicker({
  changeMonth: true,
  changeYear: true,
  monthNamesShort: <?php echo trim($msg_cal); ?>,
  dayNamesMin: <?php echo trim($msg_cal2); ?>,
  firstDay: <?php echo ($SETTINGS->weekStart=='sun' ? '0' : '1'); ?>,
  dateFormat: '<?php echo $MSDT->mswDatePickerFormat(); ?>',
  isRTL: <?php echo $msg_cal3; ?>
 });
});
