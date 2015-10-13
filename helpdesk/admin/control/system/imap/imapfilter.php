<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: imapfilter.php
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

// Update..
if (isset($_POST['process'])) {
  $MSIMAP->updateB8();
  $OK = true;
}

$title            = $msg_adheader62;
$loadJQAlertify   = true;
$loadJQNyroModal  = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/imap/imap-filters.php');
include(PATH.'templates/footer.php');

?>