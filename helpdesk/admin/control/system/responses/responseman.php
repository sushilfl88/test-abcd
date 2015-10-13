<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: responseman.php
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
include_once(PATH.'control/classes/class.responses.php');
$MSSTR = new standardResponses();
$MSSTR->settings = $SETTINGS;

// Enable/disable (Ajax)..
if (isset($_GET['changeState'])) {
  $MSSTR->enableDisable();
  echo $JSON->encode(
   array('ok')
  ); 
  exit;
}
 
// View preview..
if (isset($_GET['view'])) {
  include(PATH.'templates/system/responses/responses-window.php');
  exit; 
}

// Delete..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $count = $MSSTR->deleteResponses();
  $OK1 = true;
}

// Update order sequence..
if (isset($_POST['update-order'])) {
  $MSSTR->orderSequence();
  $OK2 = true;
}
  
// Department check..
if ($MSTEAM->id!='1') { 
  if (isset($_GET['dept']) && mswDeptPerms($MSTEAM->id,$_GET['dept'],$userDeptAccess)=='fail') {
    $HEADERS->err403(true);
  }
}

$title            = $msg_adheader54;
$loadJQAlertify   = true;
$loadJQNyroModal  = true;
     
include(PATH.'templates/header.php');
include(PATH.'templates/system/responses/responses-man.php');
include(PATH.'templates/footer.php');

?>
