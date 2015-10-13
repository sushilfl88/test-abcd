<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-open.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Ticket preview message..
if (isset($_GET['loadTicketMessage']) && (int)$_GET['loadTicketMessage']>0) {
  $T = mswGetTableData('tickets','id',mswSafeImportString($_GET['loadTicketMessage']));
  echo $MSPARSER->mswTxtParsingEngine($T->comments);
  exit;
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
$title                 = $msg_adheader5;
$loadJQAlertify        = true;
$loadJQNyroModal       = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-open.php');
include(PATH.'templates/footer.php');

?>
