<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  This File: class.departments.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class roles {

// Re-order..
public function order() {
  foreach ($_POST['order'] AS $k => $v) {
    mysql_query("UPDATE `".DB_PREFIX."roles` SET
    `orderBy`  = '{$v}'
    WHERE `id` = '{$k}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Add department..
public function add($userID) {
  // Next order sequence..
  $nextOrder = (mswRowCount('roles')+1);
  mysql_query("INSERT INTO `".DB_PREFIX."roles` (
  `name`
  ) VALUES (
  '".mswSafeImportString($_POST['name'])."'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $last = mysql_insert_id();
  // If user isn`t global user, let this user see departments added..
  if ($userID>1) {
    mysql_query("INSERT INTO `".DB_PREFIX."userdepts` (
    `userID`,`deptID`
    ) VALUES (
    '{$userID}','$last'
    )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Update department..
public function update() {
  $_GET['edit'] = (int)$_GET['edit'];
  mysql_query("UPDATE `".DB_PREFIX."roles` SET
  `name`          = '".mswSafeImportString($_POST['name'])."',
  `showDept`      = '".(isset($_POST['showDept']) ? 'yes' : 'no')."',
  `dept_subject`  = '".mswSafeImportString($_POST['dept_subject'])."',
  `dept_comments` = '".mswSafeImportString($_POST['dept_comments'])."',
  `manual_assign` = '".(isset($_POST['manual_assign']) ? 'yes' : 'no')."'
  WHERE `id`      = '{$_GET['edit']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // If manual assign is not set, remove from any tickets..
  if (isset($_POST['manual_assign']) && $_POST['manual_assign']=='no') {
    mysql_query("UPDATE `".DB_PREFIX."tickets` SET
    `assignedto`       = ''
    WHERE `department` = '{$_GET['edit']}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Delete department..
public function delete() {
  if (!empty($_POST['del'])) {
    // Nuke departments..
    mysql_query("DELETE FROM `".DB_PREFIX."roles`
    WHERE `id` IN(".implode(',',$_POST['del']).")
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
	// Nuke user department association..
    mysql_query("DELETE FROM `".DB_PREFIX."userdepts`
    WHERE `deptID` IN(".implode(',',$_POST['del']).")
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    if (mswRowCount('departments')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."departments`");
    }
    if (mswRowCount('userdepts')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."userdepts`");
    }
	// Rebuild order sequence..
	$seq = 0;
	$q   = mysql_query("SELECT `id` FROM `".DB_PREFIX."departments` ORDER BY `orderBy`");
	while ($RB = mysql_fetch_object($q)) {
	  $n = (++$seq);
	  mysql_query("UPDATE `".DB_PREFIX."departments` SET
	  `orderBy`  = '{$n}'
    WHERE `id` = '{$RB->id}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	}
    return $rows;
  }
  return '0';
}

}

?>