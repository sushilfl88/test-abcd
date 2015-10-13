<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: account-view-dispute.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS') || $SETTINGS->disputes=='no') {
  $HEADERS->err403();
}

// For redirection..
if (MS_PERMISSIONS=='guest' && isset($_GET['d']) && (int)$_GET['d']>0) {
  $_SESSION['disputeAccessID'] = (int)$_GET['d'];
}

// Load account globals..
include(PATH.'control/system/accounts/account-global.php');
  
// Check log in..
if (MS_PERMISSIONS=='guest' || !isset($_GET['d'])) {
  header("Location:index.php?p=login");
  exit;
}

// Check id..
mswCheckDigit($_GET['d']);
      
// Get ticket information and check permissions..
$T = mswGetTableData('tickets','id',$_GET['d'],'AND `visitorID` = \''.$LI_ACC->id.'\' AND `spamFlag` = \'no\''); 
if (!isset($T->id)) {
  // Check if this user is in the dispute list...
  $PRIV = mswGetTableData('disputes','visitorID',$LI_ACC->id,'AND `ticketID` = \''.$_GET['d'].'\''); 
  // If privileges allow viewing of dispute, requery without email..
  if (isset($PRIV->id)) {
    $T    = mswGetTableData('tickets','id',$_GET['d']);
	// Get person who started ticket..
	$ORGL = mswGetTableData('portal','id',$T->visitorID); 
  } else {
    $HEADERS->err403();
  }
}

// Users in dispute..
$usersInDispute = $MSTICKET->disputeUserNames($T,(isset($ORGL->name) ? mswSpecialChars($ORGL->name) : mswSpecialChars($LI_ACC->name)));

// Post privileges..
$userPostPriv   = (isset($PRIV->id) ? $PRIV->postPrivileges : $T->disPostPriv);

// Check admin restriction of not allowing any more posts until admin has replied..
if (in_array($T->replyStatus,array('admin','start')) && $SETTINGS->disputeAdminStop=='yes') {
  $userPostPriv = 'no';
}

// Re-open..can only be re-opened by original user..
if ($T->ticketStatus=='close' && isset($_GET['lk']) && $T->visitorID==$LI_ACC->id) {
  $rows = $MSTICKET->openclose($T->id);
  // History if affected rows..
  if ($rows>0) {
    $MSTICKET->historyLog(
	 $T->id,
	 str_replace('{user}',mswSpecialChars($LI_ACC->name),$msg_ticket_history['vis-ticket-open'])
	);
	$T               = mswGetTableData('tickets','id',$T->id);
	$ticketSystemMsg = $msg_public_ticket14;
  }
}

// Close..can only be re-opened by original user..
if ($T->ticketStatus!='close' && isset($_GET['cl']) && $T->visitorID==$LI_ACC->id) {
  $rows = $MSTICKET->openclose($T->id,'close');
  // History if affected rows..
  if ($rows>0) {
    $MSTICKET->historyLog(
	 $T->id,
	 str_replace('{user}',mswSpecialChars($LI_ACC->name),$msg_ticket_history['vis-ticket-close'])
	);
	$T               = mswGetTableData('tickets','id',$T->id);
	$ticketSystemMsg = $msg_public_ticket13;
  }
}

// Add reply..
if (isset($_POST['process'])) {
  define('T_PERMS', 'd');
  include(PATH.'control/system/accounts/account-ticket-reply.php');
}

// Is IP blank?
if ($T->ipAddresses=='' && $T->visitorID==$LI_ACC->id) {
  $MSTICKET->updateIP($T->id);
  $T->ipAddresses = mswIPAddresses();
}

// Variables..
$title = str_replace('{ticket}',mswTicketNumber($_GET['d']),$msg_showticket32);

include(PATH.'control/header.php');

$tpl = new Savant3();
$tpl->assign('TICKET',$T);
$tpl->assign('TXT',
 array(
  $title,
  $msg_header16,
  $msg_header3,
  $msg_main11,
  $MSYS->levels($T->priority),
  $MSDT->mswDateTimeDisplay($T->ts,$SETTINGS->dateformat),
  $MSDT->mswDateTimeDisplay($T->ts,$SETTINGS->timeformat),
  $msg_viewticket75,
  $MSYS->department($T->department,$msg_script30),
  str_replace('{url}','index.php?d='.$_GET['d'].'&amp;lk=yes',$msg_viewticket45),
  $msg_public_ticket,
  $msg_open19,
  $msg_newticket43,
  $msg_viewticket101,
  $msg_showticket5,
  $msg_viewticket78,
  $msg_newticket37,
  $msg_newticket38,
  $attachRestrictions,
  $bb_code_buttons,
  str_replace('{count}',count($usersInDispute),$msg_showticket30),
  $msg_public_ticket4,
  $msg_public_ticket9,
  $msg_viewticket27,
  $msg_public_ticket10,
  $msg_public_ticket3,
  $msg_public_ticket11,
  $msg_public_ticket15
 )
);
$tpl->assign('COMMENTS', $MSPARSER->mswTxtParsingEngine($T->comments));
$tpl->assign('USERS_IN_DISPUTE', implode(', ',$usersInDispute));
$tpl->assign('ORG_USER',(isset($ORGL->name) ? $ORGL : ''));
$tpl->assign('USERS_IN_DISPUTE_COUNT', count($usersInDispute));
$tpl->assign('CUSTOM_FIELD_DATA', $MSFIELDS->display($T->id));
$tpl->assign('ATTACHMENTS', $MSTICKET->attachments($T->id));
$tpl->assign('TICKET_REPLIES', $MSTICKET->replies($T->id,mswSpecialChars($LI_ACC->name)));
$tpl->assign('ENTRY_CUSTOM_FIELDS', $MSFIELDS->build('reply',$T->department));
$tpl->assign('REPLY_PERMISSIONS', $userPostPriv);
$tpl->assign('SYSTEM_MESSAGE', (!empty($eFields) ? str_replace('{count}',count($eFields),$msg_public_ticket8) : $ticketSystemMsg));

// Post fields..will populate on refresh..
$tpl->assign('POST', array(
  'comments' => (isset($_POST['comments']) ? mswSpecialChars($_POST['comments']) : '')
 )
); 

// Custom fields for form refresh..
$tpl->assign('CFIELDS', (isset($_POST['comments']) ? $MSFIELDS->build('reply',$T->department) : ''));

// Field flags for errors..
$tpl->assign('EFIELDS', $eFields);

// Global vars..
include(PATH.'control/lib/global.php');

// Load template..
$tpl->display('content/'.MS_TEMPLATE_SET.'/account-view-dispute.tpl.php');

include(PATH.'control/footer.php');  

?>