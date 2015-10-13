<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: faq-catman.php
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
include_once(PATH.'control/classes/class.faq.php');
$FAQ            = new faqCentre();
$FAQ->settings  = $SETTINGS;

// Enable/disable (Ajax)..
if (isset($_GET['changeState'])) {
  $FAQ->enableDisableCats();
  echo $JSON->encode(
   array('ok')
  ); 
  exit;
}
 
// Delete..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $count = $FAQ->deleteCategories();
  $OK1   = true;
}

// Order..
if (isset($_POST['update-order'])) {
  $FAQ->orderCatSequence();
  $OK2 = true;
}
  
$title          = $msg_adheader45;
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/faq/faq-catman.php');
include(PATH.'templates/footer.php');

?>
