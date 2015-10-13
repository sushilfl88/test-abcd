<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.levels.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class levels {

// Re-order..
public function orderSequence() {
  foreach ($_POST['order'] AS $k => $v) {
    mysql_query("UPDATE `".DB_PREFIX."levels` SET
    `orderBy`  = '{$v}'
    WHERE `id` = '{$k}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Rebuild sequence..
public function rebuildSequence() {
  $seq = 0;
  $q   = mysql_query("SELECT `id` FROM `".DB_PREFIX."levels` ORDER BY IF(`orderBy`>0,`orderBy`,9999)");
  while ($RB = mysql_fetch_object($q)) {
    $n = (++$seq);
	mysql_query("UPDATE `".DB_PREFIX."levels` SET
	`orderBy`  = '{$n}'
	WHERE `id` = '{$RB->id}'
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Add level..
public function addLevel() {
  mysql_query("INSERT INTO `".DB_PREFIX."levels` (
  `name`,`display`,`orderBy`
  ) VALUES (
  '".mswSafeImportString($_POST['name'])."',
  '".(isset($_POST['display']) ? 'yes' : 'no')."',
  '0'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // Rebuild order sequence..
  levels::rebuildSequence();
}

// Update level..
public function updateLevel() {
  $_GET['edit'] = (int)$_GET['edit'];
  mysql_query("UPDATE `".DB_PREFIX."levels` SET
  `name`     = '".mswSafeImportString($_POST['name'])."',
  `display`  = '".(isset($_POST['display']) ? 'yes' : 'no')."'
  WHERE `id` = '{$_GET['edit']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

// Delete level..
public function deleteLevels() {
  if (!empty($_POST['del'])) {
    mysql_query("DELETE FROM `".DB_PREFIX."levels` 
    WHERE `id` IN(".implode(',',$_POST['del']).") 
	AND `id`   NOT IN(1,2,3)
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
	// Rebuild order sequence..
	levels::rebuildSequence();
    return $rows;
  }
  return '0';
}

}

?>