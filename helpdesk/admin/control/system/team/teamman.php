<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: teamman.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Enable/disable (Ajax)..
if (isset($_GET['changeState'])) {
  $MSUSERS->enable();
  echo $JSON->encode(
   array('ok')
  ); 
  exit;
}

// Delete..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $MSUSERS->delete();
  $OK   = true;
}

$title          = $msg_adheader58;
$loadJQAlertify = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/team/teamman.php');
include(PATH.'templates/footer.php');

?>