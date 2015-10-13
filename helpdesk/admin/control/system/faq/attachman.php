<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: attachman.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}  

// Download attachment..
if (isset($_GET['fattachment'])) {
  include(REL_PATH.'control/classes/class.download.php');
  $D = new msDownload();
  $D->faqAttachment((int)$_GET['fattachment'],$SETTINGS,true);
  exit;
}

// Class..
include_once(PATH.'control/classes/class.faq.php');
$FAQ            = new faqCentre();
$FAQ->settings  = $SETTINGS;

// Enable/disable (Ajax)..
if (isset($_GET['changeState'])) {
  $FAQ->enableDisableAtt();
  echo $JSON->encode(
   array('ok')
  ); 
  exit;
}

// Delete..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $FAQ->deleteAttachments();
  $OK1 = true;
}

// Order..
if (isset($_POST['update-order'])) {
  $FAQ->orderAttSequence();
  $OK2 = true;
}

$title          = $msg_adheader49;
$loadJQAlertify = true;
     
include(PATH.'templates/header.php');
include(PATH.'templates/system/faq/faq-attachman.php');
include(PATH.'templates/footer.php');

?>