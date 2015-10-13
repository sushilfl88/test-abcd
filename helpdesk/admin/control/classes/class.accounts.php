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

class accounts {

public $settings;
public $timezones;

const ACC_EXP_FILENAME = 'accounts-{date}.csv';

public function purgeAccounts() {
  $days = (int)$_POST['days3'];
  if ($days>0) {
    $acc  = array();
    $q    = mysql_query("SELECT `".DB_PREFIX."portal`.`id` AS `accID`,`".DB_PREFIX."portal`.`language` AS `lang`,`name`,`email` FROM `".DB_PREFIX."portal` 
            WHERE DATEDIFF(NOW(),DATE(FROM_UNIXTIME(`ts`))) >= ".$days."
            HAVING(SELECT count(*) FROM `".DB_PREFIX."tickets` WHERE `".DB_PREFIX."portal`.`id` = `".DB_PREFIX."tickets`.`visitorID` AND `spamFlag` = 'no') = 0
            ");
    while ($A = mysql_fetch_object($q)) {
      $acc[$A->accID] = array(
	   'name'  => $A->name,
	   'email' => $A->email,
	   'lang'  => $A->lang
	  );
    }
    // Delete..
    if (!empty($acc)) {
      mysql_query("DELETE FROM `".DB_PREFIX."portal` WHERE `id` IN(".implode(',',array_keys($acc)).")");
    }
  }
  return $acc;
}

public function export($head,$dl) {
  $file          = PATH.'export/'.str_replace('{date}',date('dmY-his'),accounts::ACC_EXP_FILENAME);
  $sep           = ',';
  $csv           = array();
  $searchParams  = '';
  if (!isset($_GET['orderby'])) {
    $_GET['orderby'] = 'order_asc';
  }
  $orderBy = 'ORDER BY `name`';
  if (isset($_GET['orderby'])) {
    switch ($_GET['orderby']) {
      // Name (ascending)..
      case 'name_asc':
	  $orderBy = 'ORDER BY `name`';
	  break;
	  // Name (descending)..
      case 'name_desc':
	  $orderBy = 'ORDER BY `name` desc';
	  break;
	  // Email Address (ascending)..
      case 'email_asc':
	  $orderBy = 'ORDER BY `email`';
	  break;
	  // Email Address (descending)..
      case 'email_desc':
	  $orderBy = 'ORDER BY `email` desc';
	  break;
	  // Most tickets..
      case 'tickets_asc':
	  $orderBy = 'ORDER BY `tickCount` desc';
	  break;
	  // Least tickets..
      case 'tickets_desc':
	  $orderBy = 'ORDER BY `tickCount`';
	  break;
    }
  }
  // Filters..
  if ($_GET['keys']) {
    $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
    $filters[]     = "LOWER(`".DB_PREFIX."portal`.`name`) LIKE '%".$_GET['keys']."%' OR LOWER(`".DB_PREFIX."portal`.`email`) LIKE '%".$_GET['keys']."%' OR LOWER(`".DB_PREFIX."portal`.`notes`) LIKE '%".$_GET['keys']."%'";
  }
  if (isset($_GET['ip']) && $_GET['ip']) {
    $filters[]  = "`ip` = '".mswSafeImportString($_GET['ip'])."'";
  }
  if (isset($_GET['from'],$_GET['to']) && $_GET['from'] && $_GET['to']) {
    $from  = $MSDT->mswDatePickerFormat($_GET['from']);
    $to    = $MSDT->mswDatePickerFormat($_GET['to']);
    $filters[]     = "DATE(FROM_UNIXTIME(`ts`)) BETWEEN '{$from}' AND '{$to}'";
  }
  if (isset($_GET['timezone']) && $_GET['timezone']) {
    $filters[]  = "`timezone` = '".mswSafeImportString($_GET['timezone'])."'";
  }
  if (isset($_GET['status']) && in_array($_GET['status'],array('yes','no'))) {
    $filters[]  = "`enabled` = '{$_GET['status']}'";
  }
  if (isset($_GET['c1'],$_GET['c2']) && $_GET['c2']>0) {
    $_GET['c1'] = (int)$_GET['c1'];
	$_GET['c2'] = (int)$_GET['c2'];
    $filters[]  = "(SELECT count(*) FROM `".DB_PREFIX."tickets` WHERE `".DB_PREFIX."portal`.`email` = `".DB_PREFIX."tickets`.`email` AND `spamFlag` = 'no') BETWEEN '{$_GET['c1']}' AND '{$_GET['c2']}'";
  }
  // Build search string..
  if (!empty($filters)) {
    for ($i=0; $i<count($filters); $i++) {
      $searchParams .= ($i ? ' AND (' : 'WHERE (').$filters[$i].')';
    }
  }
  $q  = mysql_query("SELECT `name`,`email`,`ip`,`timezone` FROM `".DB_PREFIX."portal`
        $searchParams
		$orderBy
		") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  if (mysql_num_rows($q)>0) {
    while ($ACC = mysql_fetch_object($q)) {
      $csv[] = mswCleanCSV($ACC->name,$sep).$sep.mswCleanCSV($ACC->email,$sep).$sep.mswCleanCSV($ACC->ip,$sep).$sep.mswCleanCSV($ACC->timezone,$sep);
    }
    // Download...
    if (!empty($csv)) {
      // Save file to server and download..
	  $dl->write($file,$head.mswDefineNewline().implode(mswDefineNewline(),$csv));
      if (file_exists($file)) {
        $dl->dl($file,'text/csv');
	  }
    }
  }
  // If nothing found, just go back to search screen..
  header("Location: index.php?p=accountsearch");
  exit;
}

public function import($lines,$del,$enc) {
  $count = 0;
  $data  = array();
  // Upload CSV file..
  if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    $handle = fopen($_FILES['file']['tmp_name'],'r');
	if ($handle) {
      while (($CSV = fgetcsv($handle,$lines,$del,$enc))!==false) {
        // Clean array..
        $CSV               = array_map('trim',$CSV);
        // Add account..
	    $_POST['name']     = (isset($CSV[0]) && $CSV[0] ? $CSV[0] : '');
	    $_POST['email']    = (isset($CSV[1]) && mswIsValidEmail($CSV[1]) ? $CSV[1] : '');
        $_POST['userPass'] = (isset($CSV[2]) && $CSV[2] ? $CSV[2] : substr(md5(uniqid(rand(),1)),0,$this->settings->minPassValue));
	    $_POST['enabled']  = 'yes';
	    $_POST['timezone'] = (isset($CSV[3]) && in_array($CSV[3],array_keys($this->timezones)) ? $CSV[3] : $this->settings->timezone);
	    $_POST['ip']       = '';
		// If name and email are ok and email doesn`t exist, we can add user..
		if ($_POST['name'] && $_POST['email'] && accounts::check($_POST['email'])=='accept') {
	      ++$count;
		  // Add to db..
		  accounts::add(
		   array(
		    'name'      => $_POST['name'],
	        'email'     => $_POST['email'],
	        'userPass'  => $_POST['userPass'],
	        'enabled'   => 'yes',
	        'timezone'  => $_POST['timezone'],
	        'ip'        => $_POST['ip'],
	        'notes'     => '',
		    'language'  => $this->settings->language,
			'enableLog' => $this->settings->enableLog
		   )
		  );
	      // Add to array..
		  $data[$count] = array($_POST['name'],$_POST['email'],$_POST['userPass']);
		}
      }
      fclose($handle);
	}
    // Clear temp file..
    @unlink($_FILES['file']['tmp_name']);
  }
  return $data;
}

public function search() {
  $f   = (isset($_GET['field']) && in_array($_GET['field'],array('name','email','dest_email')) ? $_GET['field'] : 'name');
  $acc = array();
  if ($f=='dest_email') {
    $q   = mysql_query("SELECT `name`,`email` FROM `".DB_PREFIX."portal`
           WHERE (`name` LIKE '%".mswSafeImportString($_GET['term'])."%' OR 
		    `email` LIKE '%".mswSafeImportString($_GET['term'])."%')
           AND `enabled`  = 'yes'
		   AND `verified` = 'yes'
		   ".((int)$_GET['id']>0 ? 'AND `id` != \''.(int)$_GET['id'].'\'' : '')."
		   GROUP BY `email`
	       ORDER BY `name`,`email`
		   ");
  } else {
    $q   = mysql_query("SELECT `name`,`email` FROM `".DB_PREFIX."portal`
           WHERE `".$f."` LIKE '%".mswSafeImportString($_GET['term'])."%'
           AND `enabled` = 'yes'
		   AND `verified` = 'yes'
		   ".((int)$_GET['id']>0 ? 'AND `id` != \''.(int)$_GET['id'].'\'' : '')."
		   GROUP BY `email`
	       ORDER BY `name`,`email`
		   ");
  }
  while ($A = mysql_fetch_object($q)) {
    $n          = array();
	$n['name']  = mswCleanData($A->name);
	$n['email'] = mswCleanData($A->email);
	$acc[]      = $n;
  }
  return $acc;
}

public function enable() {
  $_GET['id'] = (int)$_GET['id'];
  mysql_query("UPDATE `".DB_PREFIX."portal` SET
  `enabled`  = '".($_GET['changeState']=='icon-flag' ? 'no' : 'yes')."'
  WHERE `id` = '{$_GET['id']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function add($add=array()) {
  // Add override..
  if (!empty($add)) {
    foreach ($add AS $k => $v) {
	  $_POST[$k] = $v;
	}
  }
  // Populate default password if blank..
  if ($_POST['userPass']=='') {
    $_POST['userPass'] = substr(md5(uniqid(rand(),1)),3,13);
  }
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
  `reason`,
  `language`,
  `enableLog`
  ) VALUES (
  '".mswSafeImportString($_POST['name'])."',
  UNIX_TIMESTAMP(UTC_TIMESTAMP),
  '".mswSafeImportString($_POST['email'])."',
  '".md5(SECRET_KEY.$_POST['userPass'])."',
  '".(isset($_POST['enabled']) ? 'yes' : 'no')."',
  'yes',
  '".mswSafeImportString($_POST['timezone'])."',
  '".mswSafeImportString($_POST['ip'])."',
  '".mswSafeImportString($_POST['notes'])."',
  '".(isset($_POST['reason']) ? mswSafeImportString($_POST['reason']) : '')."',
  '".(isset($_POST['language']) ? mswSafeImportString($_POST['language']) : 'english')."',
  '".(isset($_POST['enableLog']) ? 'yes' : 'no')."'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  return mysql_insert_id();
}

public function update() {
  $_GET['edit'] = (int)$_GET['edit'];
  mysql_query("UPDATE `".DB_PREFIX."portal` SET
  `name`      = '".mswSafeImportString($_POST['name'])."',
  `email`     = '".mswSafeImportString($_POST['email'])."',
  `userPass`  = '".($_POST['userPass'] ? md5(SECRET_KEY.$_POST['userPass']) : $_POST['old_pass'])."',
  `enabled`   = '".(isset($_POST['enabled']) ? 'yes' : 'no')."',
  `timezone`  = '".mswSafeImportString($_POST['timezone'])."',
  `ip`        = '".mswSafeImportString($_POST['ip'])."',
  `notes`     = '".mswSafeImportString($_POST['notes'])."',
  `reason`    = '".mswSafeImportString($_POST['reason'])."',
  `language`  = '".mswSafeImportString($_POST['language'])."',
  `enableLog` = '".(isset($_POST['enableLog']) ? 'yes' : 'no')."'
  WHERE `id`  = '{$_GET['edit']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function move($from,$to) {
  mysql_query("UPDATE `".DB_PREFIX."tickets` SET
  `lastrevision` = UNIX_TIMESTAMP(UTC_TIMESTAMP),
  `email`        = '{$to}'
  WHERE `email`  = '{$from}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $rows = mysql_affected_rows();
  return $rows;
}

public function delete($t_class) {
  if (!empty($_POST['del'])) {
    $uIDs = implode(',',$_POST['del']);
    // Get all tickets related to the users that are going to be deleted..
	$tickets = array();
    $q       = mysql_query("SELECT `id` FROM `".DB_PREFIX."tickets`
               WHERE `visitorID` IN({$uIDs})
		       ORDER BY `id`
		       ");
    while ($T = mysql_fetch_object($q)) {
	  $tickets[] = $T->id;
	}
	// If there are tickets, delete all information..
	// We can use the delete operation from the ticket class..
	if (!empty($tickets)) {
	  $_POST['ticket'] = $tickets;
	  $t_class->deleteTickets();
	}
	// Users info..
	mysql_query("DELETE FROM `".DB_PREFIX."portal` 
    WHERE `id` IN({$uIDs}) 
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	// Delete disputes..
    mysql_query("DELETE FROM `".DB_PREFIX."disputes` WHERE `visitorID` IN({$uIDs})") 
    or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	// Log entries..
    mysql_query("DELETE FROM `".DB_PREFIX."log`
    WHERE `userID` IN({$uIDs})
	AND `type`      = 'acc'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    // Truncate tables to start at 1..
    foreach (array('tickets','attachments','replies','cusfields','ticketfields',
	               'disputes','tickethistory','portal') AS $tables) {
	  if (mswRowCount($tables)==0) {
        @mysql_query("TRUNCATE TABLE `".DB_PREFIX.$tables."`");
      }
    }
	return count($uIDs);
  }
  return '0';
}

// Does data exist..
public function check($data='',$field='email') {
  $SQL = '';
  if (isset($_POST['currID']) && (int)$_POST['currID']>0) {
    $_POST['currID'] = (int)$_POST['currID'];
    $SQL = "AND `id` != '{$_POST['currID']}'";
  }
  $q = mysql_query("SELECT `id` FROM `".DB_PREFIX."portal`
       WHERE `".$field."` = '".mswSafeImportString(($data ? $data : $_POST['checkEntered']))."'
	   $SQL
       LIMIT 1
       ");
  $P = mysql_fetch_object($q);     
  return (isset($P->id) ? 'exists' : 'accept');
}

}

?>