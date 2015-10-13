<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: home.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Update default days..
if (isset($_GET['dd']) && $_GET['dd']!=$MSTEAM->defDays) {
  $MSUSERS->updateDefDays($MSTEAM->id);
  $MSTEAM->defDays = $_GET['dd'];
}

$title       = $msg_adheader11;
$loadJQAPI   = true;
$loadJQPlot  = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/home.php');
include(PATH.'templates/footer.php');

?>
