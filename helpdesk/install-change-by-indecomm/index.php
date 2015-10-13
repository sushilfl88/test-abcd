<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: index.php
  Description: Installer

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

@session_start();

date_default_timezone_set('Europe/London');

define('PATH', dirname(__file__).'/');
define('INC', 1);
define('PARENT', 1);
define('REL_PATH', substr(PATH,0,strpos(PATH,'install')-1).'/');
define('RSS_BUILD_DATE_FORMAT', date('D, j M Y H:i:s T'));

include(REL_PATH.'control/classes/class.errors.php');
if (ERR_HANDLER_ENABLED) {
  set_error_handler('msErrorhandler');
}

include(REL_PATH.'control/connect.php');
include(REL_PATH.'control/functions.php');
include(REL_PATH.'control/system/core/c2.php');
include(REL_PATH.'control/timezones.php');

mswDBConnector();

include(PATH.'control/functions.php');

$cmd         = (isset($_GET['s']) ? $_GET['s'] : '1');
$title       = SCRIPT_NAME.': Installation';
$stages      = 6;
$perc_width  = ($cmd>1 ? ceil(($cmd-1)*(100/$stages)) : '0');
$progress    = ($cmd>1 ? ceil(($cmd-1)*(100/$stages)) : '0');

if (isset($_GET['connectionTest'])) {
  $cmd = 'test';
}

// Check if PHP version is too old..
if (phpVersion()<'4.3' || !function_exists('file_get_contents')) {
  $cmd   = 'e';
  $code  = 'old';
  $type  = 'FATAL ERROR';
}

switch ($cmd) {
  case '1':
  include(PATH.'templates/header.php');
  include(PATH.'templates/1.php');
  include(PATH.'templates/footer.php');
  break;
  
  case '2':
  include(PATH.'templates/header.php');
  include(PATH.'templates/2.php');
  include(PATH.'templates/footer.php');
  break;
  
  case '3':
  include(PATH.'templates/header.php');
  include(PATH.'templates/3.php');
  include(PATH.'templates/footer.php');
  break;
  
  case '4':
  
  //Install tables..
  if (isset($_POST['tables'])) {
    include(PATH.'control/tables.php');
    header("Location: index.php?s=".(empty($tableD) ? '5' : 'e&msg=tables'));
    exit;
  }
  
  include(PATH.'control/controller.php');
  include(PATH.'templates/header.php');
  include(PATH.'templates/4.php');
  include(PATH.'templates/footer.php');
  break;
  
  case '5':
  
  //Install data..
  if (isset($_POST['hdeskInfo'])) {
    include(PATH.'control/data.php');
    include(PATH.'control/hdeskdata.php');
    header("Location: index.php?s=".(empty($hdeskdata) ? '6' : 'e&msg=sdata'));
    exit;
  }
  
  include(PATH.'templates/header.php');
  include(PATH.'templates/5.php');
  include(PATH.'templates/footer.php');
  break;
  
  case '6':
  
  //Install user..
  if (isset($_POST['data'])) {
    include(PATH.'control/user.php');
    include(PATH.'control/hdeskdata.php');
    header("Location: index.php?s=".(empty($data) ? '7' : 'e&msg=data'));
    exit;
  }
  
  include(PATH.'templates/header.php');
  include(PATH.'templates/6.php');
  include(PATH.'templates/footer.php');
  break;
  
  case '7':
  include(PATH.'templates/header.php');
  include(PATH.'templates/7.php');
  include(PATH.'templates/footer.php');
  break;
  
  case 'e':
  
  if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
      case 'tables':
      $cmd   = 'e';
      $code  = 'tables';
      $type  = 'DB ERROR';
      break;
      case 'sdata':
      $cmd   = 'e';
      $code  = 'sdata';
      $type  = 'DB ERROR';
      break;
      case 'data':
      $cmd   = 'e';
      $code  = 'tables';
      $type  = 'DB ERROR';
      break;
    }
  }
  
  include(PATH.'templates/header.php');
  include(PATH.'templates/error.php');
  include(PATH.'templates/footer.php');
  break;
  
  case 'test':
  echo dbConnectorTest(true);
  break;
}

?>
