<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: ticket-view.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array('assign',$userAccess) && !in_array('open',$userAccess) && 
    !in_array('close',$userAccess) && !in_array('search',$userAccess) && 
    !in_array('odis',$userAccess) && !in_array('cdis',$userAccess) && 
    $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Export history..
if (isset($_GET['exportHistory']) && $SETTINGS->ticketHistory=='yes' && $MSTEAM->ticketHistory=='yes') {
  mswCheckDigit($_GET['exportHistory'],true);
  // Does ticket exists..
  $SUPTICK = mswGetTableData('tickets','id',$_GET['exportHistory']);
  if (!isset($SUPTICK->id)) {
    $HEADERS->err404(true);
  }
  // Check permissions for this log..
  if (mswDeptPerms($MSTEAM->id,$SUPTICK->department,$userDeptAccess)=='fail') {
    $HEADERS->err403(true);
  }
  include_once(REL_PATH.'control/classes/class.download.php');
  $MSDL = new msDownload();
  $MSTICKET->exportTicketHistory($MSDL,$MSDT);
}

// Merge ticket..
if (isset($_GET['merge']) && ($MSTEAM->mergeperms=='yes' || $MSTEAM->id=='1')) {
  include(PATH.'templates/system/tickets/tickets-merge.php');
  exit;
}

// Preview message..
if (isset($_GET['previewMsg'])) {
  if (isset($_POST['msg'])) {
    echo "----after submit---";die('viewticketaftersubmit');
    echo $MSPARSER->mswTxtParsingEngine(mswCleanData($_POST['msg']));
  } else {
    echo $msg_add14;
  }
  exit;
}

// Download attachments..
if (isset($_GET['attachment'])) {
  mswCheckDigit($_GET['attachment'],true);
  // Does attachment exist..
  $A_DAT   = mswGetTableData('attachments','id',$_GET['attachment']);
  if (!isset($A_DAT->id)) {
    $HEADERS->err404(true);
  }
  // Check permissions for this attachment..
  if (mswDeptPerms($MSTEAM->id,$A_DAT->department,$userDeptAccess)=='fail') {
    $HEADERS->err403(true);
  }
  include(REL_PATH.'control/classes/class.download.php');
  $D = new msDownload();
  $D->ticketAttachment($_GET['attachment'],$SETTINGS,true);
  exit;
}

// At this point id should exist..
if (!isset($_GET['id'])) {
  $HEADERS->err403(true);
}

// Check digit..
mswCheckDigit($_GET['id'],true);

// Load ticket data..
$SUPTICK = mswGetTableData('tickets','id',$_GET['id']);
//print_r($SUPTICK);die('ticketdata');

// Checks..
if (!isset($SUPTICK->id)) {
  $HEADERS->err404(true);
}

// Edit notes..
if (isset($_GET['editNotes']) && ($MSTEAM->notePadEnable=='yes' || $MSTEAM->id=='1')) {
  include(PATH.'templates/system/tickets/tickets-notes.php');
  exit;
}

// Department check.. 
if (mswDeptPerms($MSTEAM->id,$SUPTICK->department,$userDeptAccess)=='fail') {
  $HEADERS->err403(true);
}

// Add reply..
if (isset($_POST['process'])) {
  echo "=====process===";//die('process');
  define('TICKET_REPLY', 1);
  include(PATH.'control/system/tickets/ticket-reply.php');
}

// Assign visitor name/email..
$VIS            = mswGetTableData('portal','id',$SUPTICK->visitorID);
$SUPTICK->name  = (isset($VIS->name) ? $VIS->name : 'N/A');
$SUPTICK->email = (isset($VIS->email) ? $VIS->email : 'N/A');

// Update status..
if (isset($_GET['act']) && in_array($_GET['act'],array('open','close','lock','ticket','dispute','reopen'))) {
  //echo "========update status====";die('ticketview1');
  $action = str_replace('{user}',$MSTEAM->name,$msg_ticket_history['ticket-status-'.$_GET['act']]);
  $rows   = $MSTICKET->updateTicketStatus();
  // History if affected rows..
  if ($rows>0) {
    $MSTICKET->historyLog(
	 $_GET['id'],
	 str_replace(
	  array('{user}'),
	  array($MSTEAM->name),
	  $action
	 )
	);
	$SUPTICK        = mswGetTableData('tickets','id',$_GET['id']);
	$SUPTICK->name  = (isset($VIS->name) ? $VIS->name : 'N/A');
    $SUPTICK->email = (isset($VIS->email) ? $VIS->email : 'N/A');
	$actionMsg      = $msg_ticket_actioned[$_GET['act']];
    $OK3            = $_GET['act'];
  }
}

$title            = str_replace('{ticket}',mswTicketNumber($_GET['id']),($SUPTICK->isDisputed=='yes' ? $msg_viewticket80 : $msg_viewticket));
$loadJQAPI        = true;
$loadBBCSS        = true;
$loadJQAlertify   = true;
$loadJQNyroModal  = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-view'.($SUPTICK->isDisputed=='yes' ? '-disputed' : '').'.php');
include(PATH.'templates/footer.php');

?>