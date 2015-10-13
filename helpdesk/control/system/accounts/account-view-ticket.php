<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: account-view-ticket.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MS_PERMISSIONS')) {
  $HEADERS->err403();
}

// Attachment download..not permitted for guests..
if (isset($_GET['attachment']) && (int)$_GET['attachment']>0) {
  if (MS_PERMISSIONS!='guest') {
    // Check permissions. Can visitor view this attachment?
    $A = mswGetTableData('attachments','id',$_GET['attachment']);
    if (isset($A->ticketID)) {
	  $allow = 'no';
	  // Is the ticket that this attachment relates to a ticket belonging to logged in user?
	  // If not, does this person have access to the ticket because of a dispute?
      $T = mswGetTableData('tickets','id',$A->ticketID,'AND `visitorID` = \''.$LI_ACC->id.'\' AND `spamFlag` = \'no\'');
	  if (isset($T->ts)) {
	    $allow = 'yes';
	  } else {
	    $DS = mswGetTableData('disputes','ticketID',$A->ticketID,'AND `visitorID` = \''.$LI_ACC->id.'\'');
	    if (isset($DS->ticketID)) {
		  $allow = 'yes';
		}
	  }
	  // If allowed, download..
	  if ($allow=='yes') {
	    include(PATH.'control/classes/class.download.php');
        $D = new msDownload();
        $D->ticketAttachment($_GET['attachment'],$SETTINGS);
	    exit;
	  }
    }
  }
  $HEADERS->err403();
  exit;
}

// For redirection..
if (MS_PERMISSIONS=='guest' && isset($_GET['t']) && (int)$_GET['t']>0) {
  $_SESSION['ticketAccessID'] = (int)$_GET['t'];
}

// Load account globals..
include(PATH.'control/system/accounts/account-global.php');
  
// Check log in..
if (MS_PERMISSIONS=='guest' || !isset($_GET['t'])) {
  header("Location:index.php?p=login");
  exit;
}

// Check id..
mswCheckDigit($_GET['t']);
      
// Get ticket information and check permissions..
$T = mswGetTableData('tickets','id',$_GET['t'],'AND `visitorID` = \''.$LI_ACC->id.'\' AND `spamFlag` = \'no\''); 
if (!isset($T->id)) {
  $HEADERS->err403();
}

// Re-open..
if ($T->ticketStatus=='close' && isset($_GET['lk'])) {
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

// Close..
if ($T->ticketStatus!='close' && isset($_GET['cl'])) {
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
  define('T_PERMS', 't');
  include(PATH.'control/system/accounts/account-ticket-reply.php');
}

// Is IP blank?
if ($T->ipAddresses=='' && $T->visitorID==$LI_ACC->id) {
  $MSTICKET->updateIP($T->id);
  $T->ipAddresses = mswIPAddresses();
}

// Variables..
$title = str_replace('{ticket}',mswTicketNumber($_GET['t']),$msg_showticket4);

include(PATH.'control/header.php');

$tpl  = new Savant3();
$tpl->assign('TICKET',$T);
$tpl->assign('TXT',
 array(
  $title,
  $msg_header11,
  $msg_header3,
  $msg_main11,
  $MSYS->levels($T->priority),
  $MSDT->mswDateTimeDisplay($T->ts,$SETTINGS->dateformat),
  $MSDT->mswDateTimeDisplay($T->ts,$SETTINGS->timeformat),
  $msg_viewticket75,
  $MSYS->department($T->department,$msg_script30),
  str_replace('{url}','index.php?t='.$_GET['t'].'&amp;lk=yes',$msg_viewticket45),
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
  $msg_public_ticket3,
  $msg_public_ticket4,
  $msg_public_ticket9,
  $msg_viewticket27,
  $msg_public_ticket10
 )
);
$tpl->assign('COMMENTS', $MSPARSER->mswTxtParsingEngine($T->comments));
$tpl->assign('CUSTOM_FIELD_DATA', $MSFIELDS->display($T->id));
$tpl->assign('ATTACHMENTS', $MSTICKET->attachments($T->id));
$tpl->assign('TICKET_REPLIES', $MSTICKET->replies($T->id,mswSpecialChars($LI_ACC->name)));
$tpl->assign('ENTRY_CUSTOM_FIELDS', $MSFIELDS->build('reply',$T->department));
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
$tpl->display('content/'.MS_TEMPLATE_SET.'/account-view-ticket.tpl.php');

include(PATH.'control/footer.php');  

?>