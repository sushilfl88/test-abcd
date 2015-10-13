<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: faq-import.php
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
     
// Import..
if (isset($_POST['process'])) {
  // Set defaults..
  $lines  = ($_POST['lines'] ? str_replace(array('.',','),array(),$_POST['lines']) : '0');
  $del    = ($_POST['delimiter'] ? $_POST['delimiter'] : ',');
  $enc    = ($_POST['enclosed'] ? $_POST['enclosed'] : '"');
  // Import..
  $total  = $FAQ->batchImportQuestions($lines,$del,$enc);
  $OK1    = true;
}
  
$title = $msg_adheader55;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/faq/faq-import.php');
include(PATH.'templates/footer.php');

?>
