<?php
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: auto-complete.php
  Description: jQuery Auto Complete
  
  Add in more parameters if required.

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

foreach (explode(',',AUTO_COMPLETE_FIELDS) AS $autoComFields) {
?>
jQuery('input[name="<?php echo $autoComFields; ?>"]').autocomplete({
 source: '<?php echo AUTO_COMPLETE_URL; ?>&field=<?php echo $autoComFields; ?>',
 minLength: 2,
 select: function(event,ui) {
 }
});
<?php
}
?>