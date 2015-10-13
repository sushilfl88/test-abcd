<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: levelsman.php
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
include_once(PATH.'control/classes/class.levels.php');
$MSLVL = new levels();

// Delete levels..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $count = $MSLVL->deleteLevels();
  $OK1   = true;
}

// Update order sequence..
if (isset($_POST['update-order'])) {
  $MSLVL->orderSequence();
  $OK2 = true;
}

$title          = $msg_adheader51;
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/levels/levelsman.php');
include(PATH.'templates/footer.php');

?>