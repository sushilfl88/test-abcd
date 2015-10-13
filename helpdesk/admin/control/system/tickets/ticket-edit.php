<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-edit.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || (!in_array('add',$userAccess) && $MSTEAM->id!='1')) {
  $HEADERS->err403(true);
}

// Check digit..
mswCheckDigit($_GET['id'],true);

// Get ticket data..
$SUPTICK = mswGetTableData('tickets','id',$_GET['id']);

// Checks..
if (!isset($SUPTICK->id)) {
  $HEADERS->err404(true);
  exit;
}

// Department check.. 
if (mswDeptPerms($MSTEAM->id,$SUPTICK->department,$userDeptAccess)=='fail') {
  $HEADERS->err403(true);
}

// Edit..
if (isset($_POST['process'])) {
  $rows = $MSTICKET->updateTicket();
  // Log if affected rows..
  if ($rows>0) {
    $MSTICKET->historyLog(
	 $_GET['id'],
	 str_replace(
	  array('{user}'),
	  array($MSTEAM->name),
	  $msg_ticket_history['edit-ticket']
	 )
	);
  }
  $SUPTICK = mswGetTableData('tickets','id',$_GET['id']);
  $OK      = true;
}

$title          = str_replace('{ticket}',mswTicketNumber($SUPTICK->id),$msg_viewticket20);
$loadJQAPI      = true;
$loadBBCSS      = true;
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-edit.php');
include(PATH.'templates/footer.php');

?>
