<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: fields.php
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
include_once(PATH.'control/classes/class.fields.php');
$MSFIELDS = new fields();
  
if (isset($_POST['process']) && $_POST['fieldInstructions']) {
  $MSFIELDS->addCustomField();
  $OK1 = true;
}
  
if (isset($_POST['update']) && $_POST['fieldInstructions']) {
  $MSFIELDS->editCustomField();
  $OK2 = true;
} 

$title  = (isset($_GET['edit']) ? $msg_customfields11 : $msg_customfields2);
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/fields/fields.php');
include(PATH.'templates/footer.php');

?>
