<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: fieldsman.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Class..
include_once(PATH.'control/classes/class.fields.php');
$MSFIELDS = new fields();

// Enable/disable (Ajax)..
if (isset($_GET['changeState'])) {
  $MSFIELDS->enableDisable();
  echo $JSON->encode(
   array('ok')
  ); 
  exit;
}
  
// Delete..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $count = $MSFIELDS->deleteCustomFields();
  $OK1   = true;
} 

if (isset($_POST['update-order'])) {
  $MSFIELDS->orderSequence();
  $OK2 = true;
}
  
$title          = $msg_adheader43;
$loadJQAlertify = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/fields/fieldsman.php');
include(PATH.'templates/footer.php');

?>
