<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: folder.php
  Description: Mailbox System

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !defined('MAILBOX_LOADER')) {
  $HEADERS->err403(true);
}

// Mark as read..
if (isset($_POST['read']) && !empty($_POST['id'])) {
  $cnt = $MSMB->mark('read',$MSTEAM->id,$_POST['id']);
  $OK  = true;
}

// Mark as unread..
if (isset($_POST['unread']) && !empty($_POST['id'])) {
  $cnt = $MSMB->mark('unread',$MSTEAM->id,$_POST['id']);
  $OK2 = true;
}

// Move to folder..
if (isset($_POST['moveto']) && !empty($_POST['id'])) {
  $cnt = $MSMB->moveTo($_POST['moveto'],$MSTEAM->id,$_POST['id']);
  $OK3 = true;
}

// Delete selected..
if (isset($_POST['delete']) && !empty($_POST['id']) && ($MSTEAM->mailDeletion=='yes' || $MSTEAM->id=='1')) {
  $cnt = $MSMB->delete($MSTEAM->id,$_POST['id']);
  $OK4  = true;
}

// Clear all..
if (isset($_POST['clear']) && ($MSTEAM->mailDeletion=='yes' || $MSTEAM->id=='1')) {
  $MSMB->emptyBin($MSTEAM->id);
  $OK5  = true;
}

?>