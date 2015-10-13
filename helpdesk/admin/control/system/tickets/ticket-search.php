<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  This File: ticket-search.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Department check for filter..
if (isset($_GET['dept']) && $_GET['dept']>0) {
  if (mswDeptPerms($MSTEAM->id,$_GET['dept'],$userDeptAccess)=='fail') {
    $HEADERS->err403(true);
  }
}

// Export..
if (isset($_POST['export-search'])) {
  include_once(REL_PATH.'control/classes/class.download.php');
  $MSDL = new msDownload();
  $MSTICKET->exportTicketStats($MSDT,$MSDL);
  exit;
}

// Update..
if (isset($_POST['update'])) {
  $_POST['ticket'] = $_POST['id'];
  $cn              = $MSTICKET->searchBatchUpdate();
  // If affected rows, write history log for each ticket..
  if ($cn[0]>0) {
    $stats    = array(
	 'close'  => $msg_viewticket15,
	 'open'   => $msg_viewticket14,
	 'closed' => $msg_viewticket16
	);
    $dept = (in_array('dept',$cn[1]) ? $MSYS->department($_POST['department'],$msg_script30) : $msg_search24);
	$pri  = (in_array('priority',$cn[1]) ? $MSYS->levels($_POST['priority']) : $msg_search24);
	$sta  = (in_array('status',$cn[1]) ? (isset($stats[$_POST['status']]) ? $stats[$_POST['status']] : 'N/A') : $msg_search24);
    foreach ($_POST['ticket'] AS $tID) {
	  $MSTICKET->historyLog(
	   $tID,
	   str_replace(
	    array('{user}','{dept}','{priority}','{status}'),
	    array($MSTEAM->name,$dept,$pri,$sta),
	    $msg_ticket_history['edit-ticket-search']
	   )
	  );
	}
	$OK1 = true;
  }
}

// Call relevant classes..
include_once(REL_PATH.'control/classes/class.tickets.php');
$MSPTICKETS            = new tickets();
$MSPTICKETS->settings  = $SETTINGS;
$MSPTICKETS->datetime  = $MSDT;
$title                 = (isset($_GET['keys']) ? $msg_search6 : $msg_search2);
$loadJQAPI             = true;
$loadJQAlertify        = true;
$loadJQNyroModal       = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/tickets/tickets-search.php');
include(PATH.'templates/footer.php');

?>
