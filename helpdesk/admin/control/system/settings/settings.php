<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: settings.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

// Access..
if (!in_array($cmd,$userAccess) && $MSTEAM->id!='1') {
  $HEADERS->err403(true);
}

if (isset($_GET['mailTest'])) {
  include(PATH.'templates/system/settings/mail-test.php');
  exit;
}

if (isset($_GET['genKey'])) {
  $length = (API_KEY_LENGTH>100 ? 100 : API_KEY_LENGTH);
  $chars  = array_merge(range(1,9),range('A','Z'),array('-','-','-'));
  shuffle($chars);
  $key = '';
  for ($i=0; $i<$length; $i++) {
    shuffle($chars);
    $key .= $chars[rand(1,9)];
  }
  echo trim($key);
  exit;
}
  
if (isset($_POST['process'])) {
  $MSSET->updateSettings();
  $SETTINGS  = mysql_fetch_object(mysql_query("SELECT * FROM `".DB_PREFIX."settings` LIMIT 1")) 
               or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $OK        = true;
}  
  
$title            = $msg_adheader2;
$loadJQAPI        = true;
$loadJQNyroModal  = true;
  
include(PATH.'templates/header.php');
include(PATH.'templates/system/settings/settings.php');
include(PATH.'templates/footer.php');

?>