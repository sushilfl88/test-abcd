<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  This File: class.mailbox.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class mailBox {

public $settings;
public $datetime;

public function getRecipient($id,$user) {
  $q  = mysql_query("SELECT `staffID` FROM `".DB_PREFIX."mailassoc`
        WHERE `mailID` = '{$id}'
		AND `staffID` != '{$user}'
		LIMIT 1
		") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $MA  = mysql_fetch_object($q);
  $U   = mswGetTableData('users','id',(isset($MA->staffID) ? $MA->staffID : '0'));
  return (isset($U->name) ? $U->name : 'N/A');
}

public function autoPurge($staff,$days) {
  mysql_query("DELETE FROM `".DB_PREFIX."mailassoc`
  WHERE `staffID` = '{$staff}'
  AND `folder`    = 'bin'
  AND DATEDIFF(NOW(),DATE(FROM_UNIXTIME(`lastUpdate`))) >= {$days}
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // Any messages now deleted by all users can be deleted..
  mailBox::assocChecker();
}

public function getLastReply($id) {
  $q  = mysql_query("SELECT `ts`,`staffID` FROM `".DB_PREFIX."mailreplies`
        WHERE `mailID` = '{$id}'
		ORDER BY `id` DESC
		") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $R  = mysql_fetch_object($q);
  if (isset($R->ts)) {
    $A    = mswGetTableData('users','id',$R->staffID);
	$info = array((isset($A->name) ? $A->name : 'N/A'),$R->ts);
	return $info;
  }
  return array('0','0');
}

public function add($data) {
  mysql_query("INSERT INTO `".DB_PREFIX."mailbox` (
  `ts`,
  `staffID`,
  `subject`,
  `message`
  ) VALUES (
  UNIX_TIMESTAMP(UTC_TIMESTAMP),
  '{$data['staff']}',
  '".mswSafeImportString($data['subject'])."',
  '".mswSafeImportString($data['message'])."'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $id = mysql_insert_id();
  // Association..
  mailBox::assoc(
   array(
    'staff'  => $data['staff'],
    'id'     => $id,
	'folder' => 'outbox',
	'status' => 'read'
   )
  );
  mailBox::assoc(
   array(
    'staff'  => $data['to'],
    'id'     => $id,
	'folder' => 'inbox',
	'status' => 'unread'
   )
  );
  return $id;
}

public function reply($data) {
  mysql_query("INSERT INTO `".DB_PREFIX."mailreplies` (
  `ts`,
  `mailID`,
  `staffID`,
  `message`
  ) VALUES (
  UNIX_TIMESTAMP(UTC_TIMESTAMP),
  '{$data['id']}',
  '{$data['staff']}',
  '".mswSafeImportString($data['message'])."'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $id = mysql_insert_id();
  // Association..
  mailBox::assoc(
   array(
    'staff'  => $data['staff'],
    'id'     => $data['id'],
	'folder' => 'outbox',
	'status' => 'read'
   )
  );
  mailBox::assoc(
   array(
    'staff'  => $data['to'],
    'id'     => $data['id'],
	'folder' => 'inbox',
	'status' => 'unread'
   )
  );
  return $id;
}

public function assoc($data) {
  if (mswRowCount('mailassoc WHERE `staffID` = \''.$data['staff'].'\' AND `mailID` = \''.$data['id'].'\'')==0) {
    mysql_query("INSERT INTO `".DB_PREFIX."mailassoc` (
    `staffID`,
    `mailID`,
    `folder`,
    `status`,
	`lastUpdate`
    ) VALUES (
    '{$data['staff']}',
    '{$data['id']}',
    '{$data['folder']}',
    '{$data['status']}',
	UNIX_TIMESTAMP(UTC_TIMESTAMP)
    )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  } else {
    mysql_query("UPDATE `".DB_PREFIX."mailassoc` SET
    `folder`        = '{$data['folder']}',
    `status`        = '{$data['status']}',
	`lastUpdate`    = UNIX_TIMESTAMP(UTC_TIMESTAMP)
    WHERE `staffID` = '{$data['staff']}'
    AND `mailID`    = '{$data['id']}'
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

public function folders($staff) {
  $deleted = 0;
  $folders = array("'inbox'","'outbox'","'bin'");
  // Existing..
  if (!empty($_POST['folder'])) {
    // Update..
	foreach ($_POST['folder'] AS $fK => $fV) {
	  mysql_query("UPDATE `".DB_PREFIX."mailfolders` SET
      `folder`      = '".mswSafeImportString($fV)."'
      WHERE `id`    = '{$fK}'
	  AND `staffID` = '{$staff}'
      ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	  $folders[] = "'".$fK."'";
	}
	// Delete messages if folder no longer exists..
	if (!empty($folders)) {
	  mysql_query("DELETE FROM `".DB_PREFIX."mailassoc`
	  WHERE `staffID`   = '{$staff}'
	  AND `folder` NOT IN(".implode(',',$folders).")
      ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
      $deleted = mysql_affected_rows();
	  if (mswRowCount('mailassoc')==0) {
        @mysql_query("TRUNCATE TABLE `".DB_PREFIX."mailassoc`");
      }
	  // Now delete folders not in array..
	  mysql_query("DELETE FROM `".DB_PREFIX."mailfolders`
	  WHERE `staffID`   = '{$staff}'
	  AND `id`     NOT IN(".implode(',',$folders).")
      ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
      if (mswRowCount('mailfolders')==0) {
        @mysql_query("TRUNCATE TABLE `".DB_PREFIX."mailfolders`");
      }
	}
  }
  // New..
  if (!empty($_POST['new'])) {
    foreach ($_POST['new'] AS $fV) {
	  if ($fV) {
	    mysql_query("INSERT INTO `".DB_PREFIX."mailfolders` (
        `staffID`,
        `folder`
        ) VALUES (
        '{$staff}',
        '".mswSafeImportString($fV)."'
        )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	  }
	}
  }
  return $deleted;
}

public function mark($mark,$staff,$ids) {
  mysql_query("UPDATE `".DB_PREFIX."mailassoc` SET
  `status`        = '{$mark}'
  WHERE `mailID` IN(".implode(',',$ids).")
  AND `staffID`   = '{$staff}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  return mysql_affected_rows();
}

public function moveTo($folder,$staff,$ids) {
  mysql_query("UPDATE `".DB_PREFIX."mailassoc` SET
  `folder`        = '{$folder}'
  WHERE `mailID` IN(".implode(',',$ids).")
  AND `staffID`   = '{$staff}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  return mysql_affected_rows();
}

public function delete($staff,$ids) {
  mysql_query("DELETE FROM `".DB_PREFIX."mailassoc`
  WHERE `mailID` IN(".implode(',',$ids).")
  AND `staffID`   = '{$staff}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $rows = mysql_affected_rows();
  // Any messages now deleted by all users can be deleted..
  mailBox::assocChecker();
  return $rows;
}

public function emptyBin($staff) {
  mysql_query("DELETE FROM `".DB_PREFIX."mailassoc`
  WHERE `staffID` = '{$staff}'
  AND `folder` = 'bin'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // Any messages now deleted by all users can be deleted..
  mailBox::assocChecker();
}

public function assocChecker() {
  mysql_query("DELETE FROM `".DB_PREFIX."mailbox`
  WHERE (SELECT count(*) FROM `".DB_PREFIX."mailassoc`
   WHERE `".DB_PREFIX."mailassoc`.`mailID` = `".DB_PREFIX."mailbox`.`id`
  ) = 0
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  if (mswRowCount('mailbox')==0) {
    @mysql_query("TRUNCATE TABLE `".DB_PREFIX."mailbox`");
	@mysql_query("TRUNCATE TABLE `".DB_PREFIX."mailassoc`");
  }
}

public function perms() {
  $users  = array();
  $ID     = (int)$_GET['msg'];
  $qAs    = mysql_query("SELECT `staffID` FROM `".DB_PREFIX."mailassoc`
            WHERE `mailID` = '{$ID}'
            ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($MA = mysql_fetch_object($qAs)) {
    $users[] = $MA->staffID;
  }
  return $users;
}

}

?>