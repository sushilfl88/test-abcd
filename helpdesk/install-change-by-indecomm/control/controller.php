<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: controller.php
  Description: Installer File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  msw403();
}

$cSets       = array();
$defaultSet  = 'latin1_swedish_ci';

// Get MySQL version..
$query       = @mysql_query("SELECT VERSION() AS v");
$VERSION     = @mysql_fetch_object($query);

// Character sets..
$DCHARSET = @mysql_query("SHOW CHARACTER SET");
while ($CH  = mysql_fetch_object($DCHARSET)) {
  if (is_object($CH)) {
    $CH_SET = (array)$CH;
    if (isset($CH_SET['Charset'])) {
      $DCOLL = @mysql_query("SHOW COLLATION LIKE '".$CH_SET['Charset']."%'");
      while ($COL  = mysql_fetch_object($DCOLL)) {
        if (is_object($COL)) {
          $COL_SET = (array)$COL;
          if (isset($COL_SET['Collation'])) {
            $cSets[] = $COL_SET['Collation'];
          }
        }
      }
    }
  }
}

if (isset($VERSION->v)) {
  $mysqlVer  = $VERSION->v;
} else {
  $mysqlVer  = 5;
}

?>
