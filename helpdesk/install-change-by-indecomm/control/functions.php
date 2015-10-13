<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: functions.php
  Description: Installer File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  msw403();
}

function dbConnectorTest($test=false) {
  $connect = @mysql_connect(DB_HOST,DB_USER,DB_PASS);
  if (!$connect) {
    if ($test) {
      return 'Connection Failed - Check Connection Parameters';
    }
	  mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__);
  }
  if ($connect && !mysql_select_db(DB_NAME,$connect)) {
    if ($test) {
      return 'Connection Failed - Check Connection Parameters';
    }
    mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__);
  }
  if ($test) {
    return 'Connection Successful';
  }
}

function mswCheckTable($table) {
  $q  = mysql_query("SHOW TABLES WHERE `Tables_in_".DB_NAME."` = '".DB_PREFIX.$table."'");
  $c  = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
  $f  = (isset($c->rows) ? $c->rows : '0');
  return ($f>0 ? 'yes' : 'no');
}

function mswCheckColumnType($table,$field,$string) {
  $q  = mysql_query("SHOW FIELDS FROM `".DB_PREFIX.$table."` WHERE `Field` = '{$field}'");
  $R  = mysql_fetch_object($q);
  $f  = (isset($R->Type) ? strtolower($R->Type) : '');
  return (strpos($f,strtolower($string))!==false ? 'yes' : 'no');
}

function mswCheckColumn($table,$col) {
  $q  = mysql_query("SELECT count(*) AS `c` FROM `information_schema`.`COLUMNS` WHERE `TABLE_SCHEMA` = '".DB_NAME."' 
        AND `TABLE_NAME`  = '".DB_PREFIX.$table."' 
        AND `COLUMN_NAME` = '{$col}'
        ");
  $R  = mysql_fetch_object($q);
  $f  = (isset($R->c) ? $R->c : '0');
  return ($f>0 ? 'yes' : 'no');
}

function mswCheckIndex($table,$index) {
  $q  = mysql_query("SHOW INDEX FROM `".DB_PREFIX.$table."` WHERE `Key_name` = '$index'");
  $c  = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
  $f  = (isset($c->rows) ? $c->rows : '0');
  return ($f>0 ? 'yes' : 'no');
}

function mswUpgradeLog($text) {
  if (is_writeable(REL_PATH.'logs')) {
    $header  = '';
    if (!file_exists(REL_PATH.'logs/upgrade-routine-log.txt')) {
      $query    = @mysql_query("SELECT VERSION() AS `v`");
      $VERSION  = @mysql_fetch_object($query);
      $header   = 'Script: '.SCRIPT_NAME.mswDefineNewline();
      $header  .= 'Script Version: '.SCRIPT_VERSION. mswDefineNewline();
      $header  .= 'PHP Version: '.phpVersion().mswDefineNewline();
      $header  .= 'MySQL Version: '.(isset($VERSION->v) ? $VERSION->v : 'Unknown').mswDefineNewline();
      if (isset($_SERVER['SERVER_SOFTWARE'])) {
        $header  .= 'Server Software: '.$_SERVER['SERVER_SOFTWARE'].mswDefineNewline();
      }
      if (isset($_SERVER["HTTP_USER_AGENT"])) {
        if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'win')) {
          $platform = 'Windows';
        } else if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mac')) {
          $platform = 'Mac';
        } else {
          $platform = 'Other';
        }
        $header  .= 'Platform: '.$platform.mswDefineNewline();
      }
      $header  .= '================================================================================='.mswDefineNewline();
    }
	$string  = date('d/m/Y H:i:s').': '.$text.mswDefineNewline();
    $string .= '- - - - - - - - - - - - - - - - - - - - - '.mswDefineNewline();
    @file_put_contents(REL_PATH.'logs/upgrade-routine-log.txt',$header.$string,FILE_APPEND);
  }
}

function mswlogDBError($table,$error,$code,$line,$file,$type='Create') {
  $header  = '';
  if (!file_exists(REL_PATH.'logs/install-error-report.txt')) {
    $query    = @mysql_query("SELECT VERSION() AS v");
    $VERSION  = @mysql_fetch_object($query);
    $header   = 'Script: '.SCRIPT_NAME.mswDefineNewline();
    $header  .= 'Script Version: '.SCRIPT_VERSION. mswDefineNewline();
    $header  .= 'PHP Version: '.phpVersion().mswDefineNewline();
    $header  .= 'MySQL Version: '.(isset($VERSION->v) ? $VERSION->v : 'Unknown').mswDefineNewline();
    if (isset($_SERVER['SERVER_SOFTWARE'])) {
      $header  .= 'Server Software: '.$_SERVER['SERVER_SOFTWARE'].mswDefineNewline();
    }
    if (isset($_SERVER["HTTP_USER_AGENT"])) {
      if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'win')) {
        $platform = 'Windows';
      } else if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mac')) {
        $platform = 'Mac';
      } else {
        $platform = 'Other';
      }
      $header  .= 'Platform: '.$platform.mswDefineNewline();
    }
    $header  .= '================================================================================='.mswDefineNewline();
  }
  $string  = 'Table: '.$table.mswDefineNewline();
  $string .= 'Operation: '.$type.mswDefineNewline();
  $string .= 'Error Code: '.$code.mswDefineNewline();
  $string .= 'Error Msg: '.$error.mswDefineNewline();
  $string .= 'On Line: '.$line.mswDefineNewline();
  $string .= 'In File: '.$file.mswDefineNewline();
  $string .= '- - - - - - - - - - - - - - - - - - - - - '.mswDefineNewline();
  if (is_writeable(REL_PATH.'logs')) {
    $fp = fopen(REL_PATH.'logs/install-error-report.txt', 'ab');
    if ($fp) {
      fwrite($fp,$header.$string);
      fclose($fp);
    }
  }
}

// Generates 60 character product key..
$_SERVER['HTTP_HOST']    = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : uniqid(rand(),1));
$_SERVER['REMOTE_ADDR']  = (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : uniqid(rand(),1));

if (function_exists('sha1')) {
  $c1       = sha1($_SERVER['HTTP_HOST'].date('YmdHis').$_SERVER['REMOTE_ADDR'].time());
  $c2       = sha1(uniqid(rand(),1).time());
  $prodKey  = substr($c1.$c2,0,60);
} elseif (function_exists('md5')) {
  $c1       = md5($_SERVER['HTTP_POST'].date('YmdHis').$_SERVER['REMOTE_ADDR'].time());
  $c2       = md5(uniqid(rand(),1),time());
  $prodKey  = substr($c1.$c2,0,60);
} else {
  $c1       = str_replace('.','',uniqid(rand(),1));
  $c2       = str_replace('.','',uniqid(rand(),1));
  $c3       = str_replace('.','',uniqid(rand(),1));
  $prodKey  = substr($c1.$c2.$c3,0,60);
}

$prodKey = strtoupper($prodKey);

?>
