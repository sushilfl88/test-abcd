<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.responses.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class standardResponses {

public $settings;

public function rebuildSequence() {
  $seq = 0;
  $q   = mysql_query("SELECT `id` FROM `".DB_PREFIX."responses` ORDER BY IF(`orderBy`>0,`orderBy`,9999)");
  while ($RB = mysql_fetch_object($q)) {
    $n = (++$seq);
	mysql_query("UPDATE `".DB_PREFIX."responses` SET
	`orderBy`  = '{$n}'
	WHERE `id` = '{$RB->id}'
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

public function orderSequence() {
  foreach ($_POST['order'] AS $k => $v) {
    mysql_query("UPDATE `".DB_PREFIX."responses` SET
	`ts`       = UNIX_TIMESTAMP(UTC_TIMESTAMP),
    `orderBy`  = '{$v}'
    WHERE `id` = '{$k}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

public function enableDisable() {
  $_GET['id'] = (int)$_GET['id'];
  mysql_query("UPDATE `".DB_PREFIX."responses` SET
  `ts`         = UNIX_TIMESTAMP(UTC_TIMESTAMP),
  `enResponse` = '".($_GET['changeState']=='icon-flag' ? 'no' : 'yes')."'
  WHERE `id`   = '{$_GET['id']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function batchImportSR($lines,$del,$enc) {
  $count = 0;
  $dept  = (empty($_POST['dept']) ? implode(',',$_POST['deptall']) : implode(',',$_POST['dept']));
  // Clear current responses..
  if (isset($_POST['clear'])) {
    $SQL  = '';
	$chop = (empty($_POST['dept']) ? $_POST['deptall'] : $_POST['dept']);
	for ($i=0; $i<count($chop); $i++) {
	  $SQL .= ($i>0 ? ' OR ' : ' WHERE ')."FIND_IN_SET(".mswSafeImportString($chop[$i]).",`departments`) > 0";
	}
    mysql_query("DELETE FROM `".DB_PREFIX."responses`".$SQL) or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    if (mswRowCount('responses')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."responses`");
    }
  }
  // Upload CSV file..
  if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    $handle = fopen($_FILES['file']['tmp_name'],'r');
	if ($handle) {
      while (($CSV = fgetcsv($handle,$lines,$del,$enc))!==false) {
        // Clean array..
        $CSV  = array_map('trim',$CSV);
        mysql_query("INSERT INTO `".DB_PREFIX."responses` (
        `ts`,
        `title`,
        `answer`,
        `departments`
        ) VALUES (
        UNIX_TIMESTAMP(UTC_TIMESTAMP),
        '".mswSafeImportString($CSV[0])."',
        '".mswSafeImportString($CSV[1])."',
        '".mswSafeImportString($dept)."'
        )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	    ++$count;
      }
      fclose($handle);
	}
    // Clear temp file..
    @unlink($_FILES['file']['tmp_name']);
	// Rebuild sequence..
	standardResponses::rebuildSequence();
  }
  return $count;
}

public function addResponse() {
  $dept = (empty($_POST['dept']) ? implode(',',$_POST['deptall']) : implode(',',$_POST['dept']));
  mysql_query("INSERT INTO `".DB_PREFIX."responses` (
  `ts`,
  `title`,
  `answer`,
  `departments`,
  `enResponse`,
  `orderBy`
  ) VALUES (
  UNIX_TIMESTAMP(UTC_TIMESTAMP),
  '".mswSafeImportString($_POST['title'])."',
  '".mswSafeImportString($_POST['answer'])."',
  '".mswSafeImportString($dept)."',
  '".(isset($_POST['enResponse']) ? 'yes' : 'no')."',
  '0'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // Rebuild sequence..
  standardResponses::rebuildSequence();
}

public function updateResponse() {
  $ID   = (int)$_GET['edit'];
  $dept = (empty($_POST['dept']) ? implode(',',$_POST['deptall']) : implode(',',$_POST['dept']));
  mysql_query("UPDATE `".DB_PREFIX."responses` SET
  `ts`          = UNIX_TIMESTAMP(UTC_TIMESTAMP),
  `title`       = '".mswSafeImportString($_POST['title'])."',
  `answer`      = '".mswSafeImportString($_POST['answer'])."',
  `departments` = '".mswSafeImportString($dept)."',
  `enResponse`  = '".(isset($_POST['enResponse']) ? 'yes' : 'no')."'
  WHERE `id`    = '{$ID}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function deleteResponses() {
  if (!empty($_POST['del'])) {
    mysql_query("DELETE FROM `".DB_PREFIX."responses` 
    WHERE `id` IN(".implode(',',$_POST['del']).") 
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
	if (mswRowCount('responses')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."responses`");
    }
    // Rebuild sequence..
	standardResponses::rebuildSequence();
	return $rows;
  }
  return '0';
}

}

?>