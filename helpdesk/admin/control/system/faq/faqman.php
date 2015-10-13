<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: faqman.php
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
  $FAQ->enableDisableQuestions();
  echo $JSON->encode(
   array('ok')
  ); 
  exit;
}

// View..
if (isset($_GET['view'])) {
  include(PATH.'templates/system/faq/faq-window.php');
  exit; 
}

// Reset..
if (isset($_POST['reset'])) {
  $FAQ->resetCounts();
  $OK1 = true;
}
     
// Delete..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $count = $FAQ->deleteQuestions();
  $OK2   = true;
}

// Order..
if (isset($_POST['update-order'])) {
  $FAQ->orderQueSequence();
  $OK3 = true;
}
  
$title            = $msg_adheader47;
$loadJQAlertify   = true;
$loadJQNyroModal  = true;
     
include(PATH.'templates/header.php');
include(PATH.'templates/system/faq/faqman.php');
include(PATH.'templates/footer.php');

?>