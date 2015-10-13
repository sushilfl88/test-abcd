<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: upgrade.php
  Description: Upgrade

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

@session_start();

define('PATH', dirname(__file__).'/');
define('INC', 1);
define('PARENT',1);
define('REL_PATH', substr(PATH,0,strpos(PATH,'install')-1).'/');
define('RSS_BUILD_DATE_FORMAT', date('D, j M Y H:i:s T'));
define('UPGRADE_ROUTINE', 'yes');

include(REL_PATH.'control/classes/class.errors.php');
if (ERR_HANDLER_ENABLED) {
  set_error_handler('msErrorhandler');
}

include(REL_PATH.'control/connect.php');
include(REL_PATH.'control/system/core/c2.php');
include(REL_PATH.'control/functions.php');
include(REL_PATH.'control/timezones.php');

$title       = SCRIPT_NAME.': Upgrade Routine';
$tableType   = '';
$ops         = array();

mswDBConnector();

// LOAD SETTINGS DATA..
// We can mask the error thrown here and redirect index file to installer..
$SETTINGS = @mysql_fetch_object(
             mysql_query("SELECT * FROM ".DB_PREFIX."settings LIMIT 1")
             );

// CHECK INSTALLER..
if (!isset($SETTINGS->language)) {
  header("Location: index.php");
  exit;
} 

// Table and collation..
$qCSVer  = @mysql_query("SHOW VARIABLES");
$VARS    = @mysql_fetch_object($qCSVer);
if (is_object($VARS)) {
  $VARS = (array)$VARS;
  if (isset($VARS['character_set_database'])) {
    $tableType = 'DEFAULT CHARACTER SET '.$VARS['character_set_database'].PHP_EOL;
    $tableType.= 'COLLATE '.$VARS['collation_database'].PHP_EOL;
  }
  if (isset($VARS['version'])) {
    if ($VARS['version']<5) {
      $tableType .= 'TYPE = MyISAM';
    } else {
      $tableType .= 'ENGINE = MyISAM';
    }
  } else {
    $tableType .= 'ENGINE = MyISAM';
  }
}

// Legacy version..
if (!isset($SETTINGS->encoderVersion)) {
  die('Version appears to be older than 2.0. Upgrade not possible, sorry.');
}

// v2.0..
if (!isset($SETTINGS->softwareVersion)) {
  $SETTINGS->softwareVersion = '2.0';
}

include(PATH.'control/functions.php');

if (isset($_GET['upgrade'])) {
  include(PATH.'control/upgrade-routine.php');
}

if (isset($_GET['completed'])) {
  include(PATH.'templates/header.php');
  include(PATH.'templates/upgrade-completed.php');
  include(PATH.'templates/footer.php');
  exit;
}

include(PATH.'templates/header.php');
include(PATH.'templates/upgrade.php');
include(PATH.'templates/footer.php');

?>
