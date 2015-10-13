<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: deptman.php
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
include_once(PATH.'control/classes/class.departments.php');
$MSDEPT = new departments();

// Delete departments..
if (isset($_POST['delete']) && USER_DEL_PRIV=='yes') {
  $count = $MSDEPT->delete();
  $OK1   = true;
}

// Update order sequence..
if (isset($_POST['update-order'])) {
  $MSDEPT->order();
  $OK2 = true;
}
  
$title          = $msg_dept9;
$loadJQAlertify = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/dept/deptman.php');
include(PATH.'templates/footer.php');

?>
