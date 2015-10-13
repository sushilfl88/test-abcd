<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: reports.php
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

// Export..
if (isset($_GET['ex'])) {
  $MSSET->exportReportCSV($MSDL);
}
     
$title      = $msg_adheader34;
$loadJQAPI  = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/settings/reports.php');
include(PATH.'templates/footer.php');

?>