<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: standard-responses-import.php
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

// Import..
if (isset($_POST['process'])) {
  $lines  = ($_POST['lines'] ? str_replace(array('.',','),array(),$_POST['lines']) : '0');
  $del    = ($_POST['delimiter'] ? $_POST['delimiter'] : ',');
  $enc    = ($_POST['enclosed'] ? $_POST['enclosed'] : '"');
  $total  = $MSSTR->batchImportSR($lines,$del,$enc);
  $OK     = true;
}
  
$title = $msg_adheader60;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/responses/responses-import.php');
include(PATH.'templates/footer.php');

?>
