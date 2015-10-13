<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: attachments.php
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

// Add
if (isset($_POST['process'])) {
  $total = $FAQ->addAttachments();
  $OK1   = true;
}

// Update..
if (isset($_POST['update'])) {
  $FAQ->updateAttachment();
  $OK2 = true;
}

$title          = (isset($_GET['edit']) ? $msg_attachments12 : $msg_attachments2);
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/faq/faq-attachments.php');
include(PATH.'templates/footer.php');

?>