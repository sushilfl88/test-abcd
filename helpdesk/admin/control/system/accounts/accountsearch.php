<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: accountsearch.php
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
include_once(PATH.'control/classes/class.accounts.php');
include_once(REL_PATH.'control/classes/class.download.php');
$MSACC            = new accounts();
$MSDL             = new msDownload();
$MSACC->settings  = $SETTINGS;

// Export..
if (isset($_GET['export'])) {
  $MSACC->export($msg_accounts37,$MSDL);
}

// Enable/disable (Ajax)..
if (isset($_GET['changeState'])) {
  $MSACC->enable();
  echo $JSON->encode(
   array('ok')
  ); 
  exit;
}

// Delete levels..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  @ini_set('memory_limit', '100M');
  @set_time_limit(0);
  $MSACC->delete();
  $OK = true;
}

$title          = $msg_adheader56;
$loadJQAPI      = true;
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/accounts/search.php');
include(PATH.'templates/footer.php');

?>