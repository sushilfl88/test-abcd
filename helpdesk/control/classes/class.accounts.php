<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  This File: class.accounts.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class accountSystem {

public $settings;

public function updateIP($id) {
  mysql_query("UPDATE `".DB_PREFIX."portal` SET
  `ip`       = '".mswIPAddresses()."'
  WHERE `id` = '{$id}'
  ");
}

public function log($user) {
  $defLogs  = ($this->settings->defKeepLogs ? unserialize($this->settings->defKeepLogs) : array());
  mysql_query("INSERT INTO `".DB_PREFIX."log` (
  `ts`,`userID`,`ip`,`type`
  ) VALUES (
  UNIX_TIMESTAMP(UTC_TIMESTAMP),'{$user}','".mswIPAddresses()."','acc'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // Clear previous..
  if (isset($defLogs['acc']) && $defLogs['acc']>0) {
    mysql_query("DELETE FROM `".DB_PREFIX."log` WHERE `userID` = '{$user}' AND `id` <
	(SELECT min(`id`) FROM
     (SELECT `id` FROM `".DB_PREFIX."log`
	   WHERE `userID` = '{$user}'
	   AND `type`     = 'acc'
	   ORDER BY `id` DESC LIMIT ".$defLogs['acc']."
	) AS `".DB_PREFIX."log`)")
	or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

public function activate($data=array()) {
  mysql_query("UPDATE `".DB_PREFIX."portal` SET
  `userPass` = '".md5(SECRET_KEY.$data['pass'])."',
  `verified` = 'yes',
  `enabled`  = 'yes',
  `system1`  = ''
  WHERE `id` = '{$data['id']}'
  ");
  return mysql_affected_rows();
}

public function add($add=array()) {
  mysql_query("INSERT INTO `".DB_PREFIX."portal` (
  `name`,
  `ts`,
  `email`,
  `userPass`,
  `enabled`,
  `verified`,
  `timezone`,
  `ip`,
  `notes`,
  `system1`,
  `system2`,
  `language`,
  `enableLog`
  ) VALUES (
  '".mswSafeImportString($add['name'])."',
  UNIX_TIMESTAMP(UTC_TIMESTAMP),
  '".mswSafeImportString($add['email'])."',
  '".md5(SECRET_KEY.$add['pass'])."',
  '{$add['enabled']}',
  '{$add['verified']}',
  '".mswSafeImportString($add['timezone'])."',
  '".mswSafeImportString($add['ip'])."',
  '".mswSafeImportString($add['notes'])."',
  '".(isset($add['system1']) ? mswSafeImportString($add['system1']) : '')."',
  '".(isset($add['system2']) ? mswSafeImportString($add['system2']) : '')."',
  '".mswSafeImportString($add['language'])."',
  '{$this->settings->enableLog}'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  return mysql_insert_id();
}

public function ban() {
  $q = mysql_query("SELECT `id` FROM `".DB_PREFIX."ban`
       WHERE `type` = 'login'
	   AND `ip`     = '".mswIPAddresses()."'
       LIMIT 1
       ");
  $B = mysql_fetch_object($q);
  // If entry found, increment count, else create new entry..
  if (isset($B->id)) {
    mysql_query("UPDATE `".DB_PREFIX."ban` SET
	`count`      = (`count`+1)
	WHERE `type` = 'login'
	AND `ip`     = '".mswIPAddresses()."'
	LIMIT 1
	");
  } else {
    mysql_query("INSERT INTO `".DB_PREFIX."ban` (
	`type`,
	`ip`,
	`count`,
	`banstamp`
	) VALUES (
	'login',
	'".mswIPAddresses()."',
	'1',
	UNIX_TIMESTAMP(UTC_TIMESTAMP)
	)");
  }
}

public function clearban() {
  mysql_query("DELETE FROM `".DB_PREFIX."ban`
  WHERE `type` = 'login'
  AND `ip`     = '".mswIPAddresses()."'
  ");
}

public function checkban($s,$dt) {
  $q = mysql_query("SELECT `id`,`banstamp` FROM `".DB_PREFIX."ban`
       WHERE `type` = 'login'
	   AND `ip`     = '".mswIPAddresses()."'
	   AND `count`  = '{$s->loginLimit}'
       LIMIT 1
       ");
  $B = mysql_fetch_object($q);
  // If found, check ban time against current timestamp..
  if (isset($B->id)) {
    $now     = $dt->mswUTC();
	$bantime = $B->banstamp;
	$elapsed = (int)($now-$bantime)/60;
	if ($s->banTime>0 && $elapsed>=$s->banTime) {
	  // Remove..
	  mysql_query("DELETE FROM `".DB_PREFIX."ban`
      WHERE `type` = 'login'
	  AND `ip`     = '".mswIPAddresses()."'
	  ");
	  return 'ok';
	}
	return 'fail';
  }
  return 'ok';
}

public function ms_generate() {
  $pass   = '';
  // Check min password isn`t zero by mistake..
  // If it is, set a default..
  if ($this->settings->minPassValue==0) {
    $this->settings->minPassValue = 8;
  }
  $sec    = array(
   'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
   'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
   '0','1','2','3','4','5','6','7','8','9','[',']','&','*','(',')','#','!','%'
  );
  for ($i=0; $i<count($sec); $i++) {
    $rand  = rand(0,(count($sec)-1));
	$char  = $sec[$rand];
    $pass .= $char;
	if ($this->settings->minPassValue==($i+1)) {
	  return $pass;
	}
  }
  return $pass;
}

public function ms_user() {
  $q = mysql_query("SELECT * FROM `".DB_PREFIX."portal`
       WHERE `email`  = '".MS_PERMISSIONS."'
	   AND `verified` = 'yes'
       LIMIT 1
       ");
  $P = mysql_fetch_object($q);
  return $P;
}

public function ms_update($data=array()) {
  // Update portal..
  $ID = (int)$data['id'];
  mysql_query("UPDATE `".DB_PREFIX."portal` SET
  `name`      = '".mswSafeImportString($data['name'])."',
  `email`     = '".mswSafeImportString($data['email'])."',
  `userPass`  = '{$data['pass']}',
  `timezone`  = '".mswSafeImportString($data['timezone'])."',
  `language`  = '".mswSafeImportString($data['language'])."'
  WHERE `id`  = '{$ID}'
  ");
  // Update login so we don`t log visitor out..
  $_SESSION[md5(SECRET_KEY).'_msw_support'] = $data['email'];
  return mysql_affected_rows();
}

public function ms_password($email,$password='') {
  $pass = ($password ? $password : accountSystem::ms_generate());
  mysql_query("UPDATE `".DB_PREFIX."portal` SET
  `userPass`     = '".md5(SECRET_KEY.$pass)."'
  WHERE `email`  = '{$email}'
  LIMIT 1
  ");
  return $pass;
}

}

?>