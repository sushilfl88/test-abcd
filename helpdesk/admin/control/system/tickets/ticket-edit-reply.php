<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-edit-reply.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || (!isset($_GET['id']) || !in_array('add',$userAccess) && $MSTEAM->id!='1')) {
  $HEADERS->err403(true);
}

// Check digit..
mswCheckDigit($_GET['id'],true);

// Add ticket..
if (isset($_POST['process'])) {
  $MSTICKET->updateTicketReply($msg_ticket_history['reply-edit']);
  $OK = true;
}

// Get reply..
$REPLY = mswGetTableData('replies','id',$_GET['id']);

// Checks..
if (!isset($REPLY->id)) {
  $HEADERS->err404(true);
}

// Get ticket data..
$SUPTICK = mswGetTableData('tickets','id',$REPLY->ticketID);

// Checks..
if (!isset($SUPTICK->id)) {
  $HEADERS->err403(true);
}

// Department check.. 
if (mswDeptPerms($MSTEAM->id,$SUPTICK->department,$userDeptAccess)=='fail') {
  $HEADERS->err403(true);
}

$title          = $msg_viewticket36;
$loadJQAPI      = true;
$loadBBCSS      = true;
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-edit-reply.php');
include(PATH.'templates/footer.php');

?>
