<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-disputes.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || $SETTINGS->disputes=='no') {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
} 
   
// Department check for filter.. 
if (isset($_GET['dept'])) {
  if (mswDeptPerms($MSTEAM->id,$_GET['dept'],$userDeptAccess)=='fail') {
    $HEADERS->err403(true);
  }
}

// Call relevant classes..
include_once(REL_PATH.'control/classes/class.tickets.php');
$MSPTICKETS            = new tickets();
$MSPTICKETS->settings  = $SETTINGS;
$MSPTICKETS->datetime  = $MSDT;
$title                 = $msg_adheader28;
$loadJQNyroModal       = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-disputes.php');
include(PATH.'templates/footer.php');

?>
