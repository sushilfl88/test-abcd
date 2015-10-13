<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.users.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class systemUsers {

public $settings;

public function updateDefDays($id) {
  $_GET['dd'] = (int)$_GET['dd'];
  if ($_GET['dd']>999) {
    $_GET['dd'] = 45;
  }
  mysql_query("UPDATE `".DB_PREFIX."users` SET
  `defDays`  = '{$_GET['dd']}'
  WHERE `id` = '{$id}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function enable() {
  $_GET['id'] = (int)$_GET['id'];
  mysql_query("UPDATE `".DB_PREFIX."users` SET
  `enabled`  = '".($_GET['changeState']=='icon-flag' ? 'no' : 'yes')."'
  WHERE `id` = '{$_GET['id']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function reset() {
  $changed = array();
  for ($i=0; $i<count($_POST['id']); $i++) {
    $e   = $_POST['mail'][$i];
	$n   = $_POST['name'][$i];
    $p   = ($_POST['password'][$i] ? md5(SECRET_KEY.$_POST['password'][$i]) : $_POST['password2'][$i]);
    $id  = $_POST['id'][$i];
    if ($e && $p) {
      mysql_query("UPDATE `".DB_PREFIX."users` SET
      `email`     = '{$e}',
      `accpass`   = '{$p}'
      WHERE `id`  = '{$id}'
      ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	  // Was anything updated?
	  if (mysql_affected_rows()>0 && $_POST['password'][$i]) {
	    $changed[] = array(
		 'id'   => $id,
		 'pass' => $_POST['password'][$i]
		);
	  }
    }
  }
  return $changed;
}

public function log($user) {
  $defLogs  = ($this->settings->defKeepLogs ? unserialize($this->settings->defKeepLogs) : array());
  mysql_query("INSERT INTO `".DB_PREFIX."log` (
  `ts`,`userID`,`ip`,`type`
  ) VALUES (
  UNIX_TIMESTAMP(UTC_TIMESTAMP),'{$user->id}','".mswIPAddresses()."','user'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // Clear previous..
  if (isset($defLogs['user']) && $defLogs['user']>0) {
    mysql_query("DELETE FROM `".DB_PREFIX."log` WHERE `userID` = '{$user->id}' AND `id` < 
	(SELECT min(`id`) FROM
     (SELECT `id` FROM `".DB_PREFIX."log` 
	   WHERE `userID` = '{$user->id}' 
	   AND `type`     = 'user' 
	   ORDER BY `id` DESC LIMIT ".$defLogs['user']."
	) AS `".DB_PREFIX."log`)")
	or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

public function add() {
  mysql_query("INSERT INTO `".DB_PREFIX."users` (
  `ts`,
  `name`,
  `email`,
  `email2`,
  `accpass`,
  `signature`,
  `notify`,
  `pageAccess`,
  `emailSigs`,
  `notePadEnable`,
  `delPriv`,
  `nameFrom`,
  `emailFrom`,
  `assigned`,
  `timezone`,
  `ticketHistory`,
  `enableLog`,
  `mailbox`,
  `mailFolders`,
  `mailDeletion`,
  `mailScreen`,
  `mailCopy`,
  `mailPurge`,
  `addpages`,
  `mergeperms`,
  `digest`,
  `digestasg`,
  `profile`,
  `helplink`
  ) VALUES (
  UNIX_TIMESTAMP(UTC_TIMESTAMP),
  '".mswSafeImportString($_POST['name'])."',
  '{$_POST['email']}',
  '".mswSafeImportString($_POST['email2'])."',
  '".md5(SECRET_KEY.$_POST['accpass'])."',
  '".mswSafeImportString(strip_tags($_POST['signature']))."',
  '".(isset($_POST['notify']) ? 'yes' : 'no')."',
  '".(!empty($_POST['accessPages']) ? implode('|',$_POST['accessPages']) : '')."',
  '".(isset($_POST['emailSigs']) ? 'yes' : 'no')."',
  '".(isset($_POST['notePadEnable']) ? 'yes' : 'no')."',
  '".(isset($_POST['delPriv']) ? 'yes' : 'no')."',
  '".mswSafeImportString($_POST['nameFrom'])."',
  '".mswSafeImportString($_POST['emailFrom'])."',
  '".(isset($_POST['assigned']) ? 'yes' : 'no')."',
  '".mswSafeImportString($_POST['timezone'])."',
  '".(isset($_POST['ticketHistory']) ? 'yes' : 'no')."',
  '".(isset($_POST['enableLog']) ? 'yes' : 'no')."',
  '".(isset($_POST['mailbox']) ? 'yes' : 'no')."',
  '".(int)$_POST['mailFolders']."',
  '".(isset($_POST['mailDeletion']) ? 'yes' : 'no')."',
  '".(isset($_POST['mailScreen']) ? 'yes' : 'no')."',
  '".(isset($_POST['mailCopy']) ? 'yes' : 'no')."',
  '".(int)$_POST['mailPurge']."',
  '".mswSafeImportString($_POST['addpages'])."',
  '".(isset($_POST['mergeperms']) ? 'yes' : 'no')."',
  '".(isset($_POST['digest']) ? 'yes' : 'no')."',
  '".(isset($_POST['digestasg']) ? 'yes' : 'no')."',
  '".(isset($_POST['profile']) ? 'yes' : 'no')."',
  '".(isset($_POST['helplink']) ? 'yes' : 'no')."'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $id = mysql_insert_id();
  // Add to user departments..
  if (!empty($_POST['dept']) && !isset($_POST['assigned'])) {
    foreach ($_POST['dept'] AS $dID) {
      mysql_query("INSERT INTO `".DB_PREFIX."userdepts` (
      `userID`,`deptID`
      ) VALUES (
      '{$id}','{$dID}'
      )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  } else {
    // If no departments were set, add user to all as default..
    $d = mysql_query("SELECT `id` FROM `".DB_PREFIX."departments` ORDER BY `id`")
         or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    while ($D = mysql_fetch_object($d)) {
      mysql_query("INSERT INTO `".DB_PREFIX."userdepts` (
      `userID`,`deptID`
      ) VALUES (
      '{$id}','{$D->id}'
      )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  }
  // Determine access pages..
  if (!empty($_POST['accessPages'])) {
    foreach ($_POST['accessPages'] AS $aPage) {
      mysql_query("INSERT INTO `".DB_PREFIX."usersaccess` (
      `page`,`userID`,`type`
      ) VALUES (
      '{$aPage}','{$id}','pages'
      )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  }	
}

public function profile($user) {
  $rows = 0;
  $pass = ($_POST['accpass'] ? md5(SECRET_KEY.$_POST['accpass']) : $_POST['old_pass']);
  // This is a security check. Make sure details don`t match someone else`s account..
  if (mswRowCount('users WHERE `email` = \''.mswSafeImportString($_POST['email']).'\' AND `id` != \''.$user->id.'\'')==0) {
    mysql_query("UPDATE `".DB_PREFIX."users` SET
    `name`           = '".mswSafeImportString($_POST['name'])."',
    `email`          = '".mswSafeImportString($_POST['email'])."',
    `email2`         = '".mswSafeImportString($_POST['email2'])."',
    `accpass`        = '{$pass}',
    `signature`      = '".mswSafeImportString(strip_tags($_POST['signature']))."',
    `nameFrom`       = '".mswSafeImportString($_POST['nameFrom'])."',
    `emailFrom`      = '".mswSafeImportString($_POST['emailFrom'])."',
    `timezone`       = '".mswSafeImportString($_POST['timezone'])."'
    WHERE `id`       = '{$user->id}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
    // Update session vars..
    $_SESSION[md5(SECRET_KEY).'_ms_mail'] = $_POST['email'];
    if ($_POST['accpass']) {
      $_SESSION[md5(SECRET_KEY).'_ms_key']  = $pass;
    }// Clear cookies..
    if (isset($_COOKIE[md5(SECRET_KEY).'_msc_mail'])) {
      @setcookie(md5(SECRET_KEY).'_msc_mail', '');
      @setcookie(md5(SECRET_KEY).'_msc_key', '');
      unset($_COOKIE[md5(SECRET_KEY).'_msc_mail'],$_COOKIE[md5(SECRET_KEY).'_msc_key']);
    }
  }
  return $rows;
}

public function update($user) {
  $_GET['edit'] = (int)$_GET['edit'];
  $pass         = ($_POST['accpass'] ? md5(SECRET_KEY.$_POST['accpass']) : $_POST['old_pass']);
  mysql_query("UPDATE `".DB_PREFIX."users` SET
  `name`           = '".mswSafeImportString($_POST['name'])."',
  `email`          = '{$_POST['email']}',
  `email2`         = '".mswSafeImportString($_POST['email2'])."',
  `accpass`        = '{$pass}',
  `signature`      = '".mswSafeImportString(strip_tags($_POST['signature']))."',
  `notify`         = '".(isset($_POST['notify']) ? 'yes' : 'no')."',
  `pageAccess`     = '".(!empty($_POST['accessPages']) ? implode('|',$_POST['accessPages']) : '')."',
  `emailSigs`      = '".(isset($_POST['emailSigs']) ? 'yes' : 'no')."',
  `notePadEnable`  = '".(isset($_POST['notePadEnable']) ? 'yes' : 'no')."',
  `delPriv`        = '".(isset($_POST['delPriv']) ? 'yes' : 'no')."',
  `nameFrom`       = '".mswSafeImportString($_POST['nameFrom'])."',
  `emailFrom`      = '".mswSafeImportString($_POST['emailFrom'])."',
  `assigned`       = '".(isset($_POST['assigned']) ? 'yes' : 'no')."',
  `timezone`       = '".mswSafeImportString($_POST['timezone'])."',
  `enabled`        = '".(isset($_POST['enabled']) ? 'yes' : 'no')."',
  `ticketHistory`  = '".(isset($_POST['ticketHistory']) ? 'yes' : 'no')."',
  `enableLog`      = '".(isset($_POST['enableLog']) ? 'yes' : 'no')."',
  `mailbox`        = '".(isset($_POST['mailbox']) ? 'yes' : 'no')."',
  `mailFolders`    = '".(int)$_POST['mailFolders']."',
  `mailDeletion`   = '".(isset($_POST['mailDeletion']) ? 'yes' : 'no')."',
  `mailScreen`     = '".(isset($_POST['mailScreen']) ? 'yes' : 'no')."',
  `mailCopy`       = '".(isset($_POST['mailCopy']) ? 'yes' : 'no')."',
  `mailPurge`      = '".(int)$_POST['mailPurge']."',
  `addpages`       = '".(isset($_POST['addpages']) ? mswSafeImportString($_POST['addpages']) : '')."',
  `mergeperms`     = '".(isset($_POST['mergeperms']) ? 'yes' : 'no')."',
  `digest`         = '".(isset($_POST['digest']) ? 'yes' : 'no')."',
  `digestasg`      = '".(isset($_POST['digestasg']) ? 'yes' : 'no')."',
  `profile`        = '".(isset($_POST['profile']) ? 'yes' : 'no')."',
  `helplink`       = '".(isset($_POST['helplink']) ? 'yes' : 'no')."'
  WHERE `id`       = '{$_GET['edit']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // Add to user departments..
  if (!empty($_POST['dept']) && !isset($_POST['assigned'])) {
    mysql_query("DELETE FROM `".DB_PREFIX."userdepts`
    WHERE `userID` = '{$_GET['edit']}' 
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    if (mswRowCount('userdepts')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."userdepts`");
    }
    foreach ($_POST['dept'] AS $dID) {
      mysql_query("INSERT INTO `".DB_PREFIX."userdepts` (
      `userID`,`deptID`
      ) VALUES (
      '{$_GET['edit']}','{$dID}'
      )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  } else {
    // If not global user, add to all departments if none set..
    if ($_GET['edit']>1) {
      mysql_query("DELETE FROM `".DB_PREFIX."userdepts`
      WHERE `userID` = '{$_GET['edit']}' 
      ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
      // If no departments were set, add user to all as default..
      $d = mysql_query("SELECT `id` FROM `".DB_PREFIX."departments` ORDER BY `id`")
           or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
      while ($D = mysql_fetch_object($d)) {
        mysql_query("INSERT INTO `".DB_PREFIX."userdepts` (
        `userID`,`deptID`
        ) VALUES (
        '{$_GET['edit']}','{$D->id}'
        )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
      }
    }
  }
  // Determine access pages..
  if (!empty($_POST['accessPages']) && $_GET['edit']>1) {
    mysql_query("DELETE FROM `".DB_PREFIX."usersaccess`
    WHERE `userID` = '{$_GET['edit']}' 
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    if (mswRowCount('usersaccess')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."usersaccess`");
    }
	foreach ($_POST['accessPages'] AS $aPage) {
      mysql_query("INSERT INTO `".DB_PREFIX."usersaccess` (
      `page`,`userID`,`type`
      ) VALUES (
      '{$aPage}','{$_GET['edit']}','pages'
      )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  }
  // If password was set and the person logged in has changed their details, change session vars..
  // We`ll update password and e-mail session vars and reset cookies..
  if ($user==$_GET['edit']) {
    $_SESSION[md5(SECRET_KEY).'_ms_mail'] = $_POST['email'];
    if ($_POST['accpass']) {
      $_SESSION[md5(SECRET_KEY).'_ms_key']  = $pass;
    }// Clear cookies..
    if (isset($_COOKIE[md5(SECRET_KEY).'_msc_mail'])) {
      @setcookie(md5(SECRET_KEY).'_msc_mail', '');
      @setcookie(md5(SECRET_KEY).'_msc_key', '');
      unset($_COOKIE[md5(SECRET_KEY).'_msc_mail'],$_COOKIE[md5(SECRET_KEY).'_msc_key']);
    }
  }
}

public function delete() {
  if (!empty($_POST['del'])) {
    $uID = implode(',',$_POST['del']);
    // Users info..
    mysql_query("DELETE FROM `".DB_PREFIX."users` 
    WHERE `id` IN({$uID}) 
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
	// Departments assigned..
    mysql_query("DELETE FROM `".DB_PREFIX."userdepts`
    WHERE `userID` IN({$uID})
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	// Access assigned..
    mysql_query("DELETE FROM `".DB_PREFIX."usersaccess`
    WHERE `userID` IN({$uID})
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	// Log entries..
    mysql_query("DELETE FROM `".DB_PREFIX."log`
    WHERE `userID` IN({$uID})
	AND `type`      = 'user'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	// Truncate tables to start at 1..
    foreach (array('users','userdepts','usersaccess','log') AS $tables) {
	  if (mswRowCount($tables)==0) {
        @mysql_query("TRUNCATE TABLE `".DB_PREFIX.$tables."`");
      }
    }
    return $rows;
  }
}

// Does email exist..
public function check() {
  $SQL = '';
  if (isset($_POST['currID']) && (int)$_POST['currID']>0) {
    $_POST['currID'] = (int)$_POST['currID'];
    $SQL = "AND `id` != '{$_POST['currID']}'";
  }
  $q = mysql_query("SELECT `id` FROM `".DB_PREFIX."users`
       WHERE `email` = '".mswSafeImportString($_POST['checkEntered'])."'
	   $SQL
       LIMIT 1
       ");
  $P = mysql_fetch_object($q);     
  return (isset($P->id) ? 'exists' : 'accept');
}

// Reset password..
public function password($id,$password) {
  mysql_query("UPDATE `".DB_PREFIX."users` SET
  `accpass`  = '".md5(SECRET_KEY.$password)."'
  WHERE `id` = '{$id}'
  ");
  return $password;
}

}

?>