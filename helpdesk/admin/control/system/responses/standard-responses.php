<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: standard-responses.php
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
include_once(PATH.'control/classes/class.responses.php');
$MSSTR           = new standardResponses();
$MSSTR->settings = $SETTINGS;

// Preview message..
if (isset($_GET['previewMsg'])) {
  echo $MSPARSER->mswTxtParsingEngine(mswCleanData($_POST['msg']));
  exit;
}

// Add..
if (isset($_POST['process'])) {
  if (trim($_POST['title']) && trim($_POST['answer'])) {
    $MSSTR->addResponse();
    $OK1 = true;
  }
}

// Update..
if (isset($_POST['update'])) {
  if (trim($_POST['title']) && trim($_POST['answer'])) {
    $MSSTR->updateResponse();
    $OK2 = true;
  }
}
     
$title          = (isset($_GET['edit']) ? $msg_response13 : $msg_adheader53);
$loadBBCSS      = true;
$loadJQAlertify = true;
     
include(PATH.'templates/header.php');
include(PATH.'templates/system/responses/responses.php');
include(PATH.'templates/footer.php');

?>
