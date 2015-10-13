<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: faq.php
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
include_once(PATH.'control/classes/class.faq.php');
$FAQ            = new faqCentre();
$FAQ->settings  = $SETTINGS;

// Preview message..
if (isset($_GET['previewMsg'])) {
  echo $MSPARSER->mswTxtParsingEngine(mswCleanData($_POST['msg']));
  exit;
}

// Add..
if (isset($_POST['process'])) {
  if (trim($_POST['question']) && trim($_POST['answer'])) {
    $return  = $FAQ->addQuestion();
    $OK1     = true;
  }
}
  
// Update
if (isset($_POST['update'])) {
  if (trim($_POST['question']) && trim($_POST['answer'])) {
    $FAQ->updateQuestion();
    $OK2 = true;
  }
}
     
$title          = (isset($_GET['edit']) ? $msg_kbase13 : $msg_adheader46);
$loadBBCSS      = true;
$loadJQAlertify = true;
     
include(PATH.'templates/header.php');
include(PATH.'templates/system/faq/faq.php');
include(PATH.'templates/footer.php');

?>