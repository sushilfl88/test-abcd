<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: log.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Classes..
include_once(REL_PATH.'control/classes/class.download.php');
$MSDL = new msDownload();

// Delete logs..
if (!empty($_GET['del']) && USER_DEL_PRIV=='yes') {
  $MSSET->deleteLogs();
  header("Location: index.php?p=log&deleted=yes");
  exit;
}

// Clear all
if (isset($_GET['clear']) && USER_DEL_PRIV=='yes') {
  $MSSET->clearLogFile();
  $OK1 = true;
}

// Export..
if (isset($_GET['export'])) {
  $MSSET->exportLogFile($MSDL);
}
  
$title          = $msg_adheader20;
$loadJQAlertify = true;
$loadJQAPI      = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/settings/log.php');
include(PATH.'templates/footer.php');

?>
