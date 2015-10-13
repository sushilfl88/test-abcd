<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: dept.php
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

// Add..
if (isset($_POST['process'])) {
  if (trim($_POST['name'])) {
    $MSDEPT->add($MSTEAM->id);
    $OK1 = true;
  }
}
     
// Update..
if (isset($_POST['update'])) {
  if (trim($_POST['name'])) {
    $MSDEPT->update();
    $OK2 = true;
  }
}

$title = (isset($_GET['edit']) ? $msg_dept5 : $msg_dept2);

include(PATH.'templates/header.php');
include(PATH.'templates/system/dept/dept.php');
include(PATH.'templates/footer.php');

?>
