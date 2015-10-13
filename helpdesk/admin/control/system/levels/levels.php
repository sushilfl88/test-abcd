<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: levels.php
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

// Add..
if (isset($_POST['process'])) {
  if (trim($_POST['name'])) {
    $return = $MSLVL->addLevel();
    $OK     = true;
  }
}
   
// Update..
if (isset($_POST['update'])) {
  if (trim($_POST['name'])) {
    $MSLVL->updateLevel();
    $OK2 = true;
  }
}

$title = (isset($_GET['edit']) ? $msg_levels5 : $msg_adheader50);

include(PATH.'templates/header.php');
include(PATH.'templates/system/levels/levels.php');
include(PATH.'templates/footer.php');

?>
