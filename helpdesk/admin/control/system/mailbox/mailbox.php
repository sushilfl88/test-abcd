<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: mailbox.php
  Description: Mailbox loader file

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !isset($MSTEAM->mailbox)) {
  $HEADERS->err403(true);
}

define('MAILBOX_LOADER',1);

// Is mailbox enabled?
if ($MSTEAM->mailbox=='no') {
  $HEADERS->err403(true);
}

// Load mail params
include(REL_PATH.'control/mail-data.php');

// Mail class..
include(PATH.'control/classes/class.mailbox.php');
$MSMB            = new mailBox();
$MSMB->settings  = $SETTINGS;
$MSMB->datetime  = $MSDT;

// Folder management..
include(PATH.'control/system/mailbox/folder.php');

$title           = $msg_adheader61.' ('.$msg_mailbox.')';
$loadJQAlertify  = true;
$boxName         = $msg_mailbox;
$temp            = 'mailbox-folder.php';
$toLoad          = 'inbox';

// Run auto purge..
if ($MSTEAM->mailPurge>0 && !isset($_SESSION['autoPurgeRan'])) {
  $MSMB->autoPurge($MSTEAM->id,$MSTEAM->mailPurge);
  // We`ll set a session cookie here, this prevents it being run again in this session..
  $_SESSION['autoPurgeRan'] = true;
}

// Load folder..
if (isset($_GET['f'])) {
  $temp  = (in_array($_GET['f'],array('outbox','bin')) ? $_GET['f'] : (int)$_GET['f']);
  switch ($temp) {
    case 'outbox':
	$title   = $msg_adheader61.' ('.$msg_mailbox2.')';
	$boxName = $msg_mailbox2;
	$toLoad  = 'outbox';
	break;
	case 'bin':
	$title   = $msg_adheader61.' ('.$msg_mailbox3.')';
	$boxName = $msg_mailbox3;
	$toLoad  = 'bin';
	break;
	default:
	if ($temp>0) {
	  $FLDER   = mswGetTableData('mailfolders','id',$temp,'AND `staffID` = \''.$MSTEAM->id.'\'');
	  if (isset($FLDER->folder)) {
	    $boxName = mswSpecialChars($FLDER->folder);
		$title   = $msg_adheader61.' ('.$boxName.')';
		$toLoad  = $FLDER->id;
	  } else {
	    $HEADERS->err403(true);
	  }
	} else {
	  $HEADERS->err403(true);
	}
	break;
  }
  $loadJQAlertify = true;
  $temp           = 'mailbox-folder.php';
}

// New message..
if (isset($_GET['new'])) {
  $title = $msg_adheader61.' ('.$msg_mailbox4.')';
  include(PATH.'control/system/mailbox/message.php');
  $temp = 'new-message.php';
}

// View message..
if (isset($_GET['msg'])) {
  $MID = (int)$_GET['msg'];
  // Check permissions..
  $perms = $MSMB->perms();
  if (empty($perms) || !in_array($MSTEAM->id,$perms)) {
    $HEADERS->err403(true);
  }
  // Load message..
  $MMSG = mswGetTableData('mailbox','id',$MID);
  if (!isset($MMSG->id)) {
    $HEADERS->err404(true);
  }
  // Mark as read..
  $MSMB->mark('read',$MSTEAM->id,array($MID));
  include(PATH.'control/system/mailbox/message.php');
  $title = $msg_adheader61.' ('.$msg_mailbox7.')';
  $temp  = 'view-message.php';
}

// Manage folders..
if (isset($_GET['folders']) && $MSTEAM->mailFolders>0) {
  $title = $msg_adheader61.' ('.$msg_mailbox6.')';
  include(PATH.'control/system/mailbox/manage-folders.php');
  $temp  = 'manage-folders.php';
}
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/mailbox/'.$temp);
include(PATH.'templates/footer.php');

?>