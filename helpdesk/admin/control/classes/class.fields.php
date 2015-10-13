<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.fields.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class fields {

public function orderSequence() {
  foreach ($_POST['order'] AS $k => $v) {
    mysql_query("UPDATE `".DB_PREFIX."cusfields` SET
    `orderBy`  = '{$v}'
    WHERE `id` = '{$k}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

public function rebuildSequence() {
  $seq = 0;
  $q   = mysql_query("SELECT `id` FROM `".DB_PREFIX."cusfields` ORDER BY IF(`orderBy`>0,`orderBy`,9999)");
  while ($RB = mysql_fetch_object($q)) {
    $n = (++$seq);
	mysql_query("UPDATE `".DB_PREFIX."cusfields` SET
	`orderBy`  = '{$n}'
	WHERE `id` = '{$RB->id}'
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

public function enableDisable() {
  $_GET['id'] = (int)$_GET['id'];
  mysql_query("UPDATE `".DB_PREFIX."cusfields` SET
  `enField`  = '".($_GET['changeState']=='icon-flag' ? 'no' : 'yes')."'
  WHERE `id` = '{$_GET['id']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function addCustomField() {
  // Defaults if not set..
  $_POST['fieldType']   = (isset($_POST['fieldType']) && in_array($_POST['fieldType'],array('textarea','input','select','checkbox')) ? $_POST['fieldType'] : 'input');
  $_POST['fieldReq']    = (isset($_POST['fieldReq']) ? 'yes' : 'no');
  $_POST['repeatPref']  = (isset($_POST['repeatPref']) ? 'yes' : 'no');
  $_POST['enField']     = (isset($_POST['enField']) ? 'yes' : 'no');
  $dept                 = (empty($_POST['dept']) ? implode(',',$_POST['deptall']) : implode(',',$_POST['dept']));
  if (empty($_POST['fieldLoc'])) {
    $_POST['fieldLoc'][] = 'ticket';
  }
  mysql_query("INSERT INTO `".DB_PREFIX."cusfields` (
  `fieldInstructions`,
  `fieldType`,
  `fieldReq`,
  `fieldOptions`,
  `fieldLoc`,
  `orderBy`,
  `repeatPref`,
  `enField`,
  `departments`
  ) VALUES (
  '".mswSafeImportString($_POST['fieldInstructions'])."',
  '{$_POST['fieldType']}',
  '{$_POST['fieldReq']}',
  '".mswSafeImportString($_POST['fieldOptions'])."',
  '".implode(',',$_POST['fieldLoc'])."',
  '0',
  '{$_POST['repeatPref']}',
  '{$_POST['enField']}',
  '{$dept}'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // Rebuild sequence..
  fields::rebuildSequence();
}

public function editCustomField() {
  // Defaults if not set..
  $_POST['fieldType']   = (isset($_POST['fieldType']) && in_array($_POST['fieldType'],array('textarea','input','select','checkbox')) ? $_POST['fieldType'] : 'input');
  $_POST['fieldReq']    = (isset($_POST['fieldReq']) ? 'yes' : 'no');
  $_POST['repeatPref']  = (isset($_POST['repeatPref']) ? 'yes' : 'no');
  $_POST['enField']     = (isset($_POST['enField']) ? 'yes' : 'no');
  $dept                 = (empty($_POST['dept']) ? implode(',',$_POST['deptall']) : implode(',',$_POST['dept']));
  if (empty($_POST['fieldLoc'])) {
    $_POST['fieldLoc'][] = 'ticket';
  }
  if ((int)$_GET['edit']>0) {
    mysql_query("UPDATE `".DB_PREFIX."cusfields` SET
    `fieldInstructions`  = '".mswSafeImportString($_POST['fieldInstructions'])."',
    `fieldType`          = '{$_POST['fieldType']}',
    `fieldReq`           = '{$_POST['fieldReq']}',
    `fieldOptions`       = '".mswSafeImportString($_POST['fieldOptions'])."',
    `fieldLoc`           = '".implode(',',$_POST['fieldLoc'])."',
    `repeatPref`         = '{$_POST['repeatPref']}',
    `enField`            = '{$_POST['enField']}',
    `departments`        = '{$dept}'
    WHERE `id`           = '{$_GET['edit']}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

public function deleteCustomFields() {
  if (!empty($_POST['del'])) {
    mysql_query("DELETE FROM `".DB_PREFIX."cusfields` 
    WHERE `id` IN(".implode(',',$_POST['del']).") 
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
	mysql_query("DELETE FROM `".DB_PREFIX."ticketfields` 
    WHERE `fieldID` IN(".implode(',',$_POST['del']).") 
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	if (mswRowCount('cusfields')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."cusfields`");
    }
    if (mswRowCount('ticketfields')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."ticketfields`");
    }
    // Rebuild sequence..
	fields::rebuildSequence();
	return $rows;
  }
  return '0';  
}

}

?>