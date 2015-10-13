<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: graph.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || !isset($_GET['id'])) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

// Lets check someone isn`t trying to view the admin user..
if ($_GET['id']=='1' && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

$U = mswGetTableData('users','id',(int)$_GET['id']);
checkIsValid($U);
  
$title       = $msg_user86.' ('.mswSpecialChars($U->name).')';
$loadJQAPI   = true;
$loadJQPlot  = true;

include(PATH.'templates/header.php');
include(PATH.'templates/system/team/graph.php');
include(PATH.'templates/footer.php');

?>