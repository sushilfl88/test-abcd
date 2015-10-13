<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: imapman.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Class..
include_once(PATH.'control/classes/class.imap.php');
$MSIMAP  = new imap();

// Enable/disable (Ajax)..
if (isset($_GET['changeState'])) {
  $MSIMAP->enableDisable();
  echo $JSON->encode(
   array('ok')
  ); 
  exit;
}

// Delete..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $count = $MSIMAP->deleteImapAccounts();
  $OK1   = true;
} 

$title            = $msg_adheader40;
$loadJQAlertify   = true;
$loadJQNyroModal  = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/imap/imapman.php');
include(PATH.'templates/footer.php');

?>