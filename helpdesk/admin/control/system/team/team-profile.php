<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: team-profile.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT') || $MSTEAM->profile=='no') {
  $HEADERS->err403(true);
}

// Load mail params
include(REL_PATH.'control/mail-data.php');

if (isset($_POST['process'])) {
  // Revert to default if blank..
  $_POST['name']  = ($_POST['name'] ? $_POST['name'] : mswCleanData($MSTEAM->name));
  $_POST['email'] = ($_POST['email'] && mswIsValidEmail($_POST['email']) ? $_POST['email'] : mswCleanData($MSTEAM->email));
  $rows           = $MSUSERS->profile($MSTEAM);
  if ($rows>0) {
    $OK = true;
  }
}
  
$title  = $msg_adheader64;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/team/team-profile.php');
include(PATH.'templates/footer.php');

?>