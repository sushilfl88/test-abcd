<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.imap.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class imap {

public function updateB8() {
  $_POST['tokens']     = (int)$_POST['tokens'];
  $_POST['min_size']   = (int)$_POST['min_size'];
  $_POST['max_size']   = (int)$_POST['max_size'];
  $_POST['learning']   = (isset($_POST['learning']) ? 'yes' : 'no');
  $_POST['num_parse']  = (isset($_POST['num_parse']) ? 'yes' : 'no');
  $_POST['uri_parse']  = (isset($_POST['uri_parse']) ? 'yes' : 'no');
  $_POST['html_parse'] = (isset($_POST['html_parse']) ? 'yes' : 'no');
  $_POST['multibyte']  = (isset($_POST['multibyte']) ? 'yes' : 'no');
  mysql_query("UPDATE `".DB_PREFIX."imap_b8` SET
  `tokens`      = '{$_POST['tokens']}',
  `min_size`    = '{$_POST['min_size']}',
  `max_size`    = '{$_POST['max_size']}',
  `min_dev`     = '".mswSafeImportString($_POST['min_dev'])."',
  `x_constant`  = '".mswSafeImportString($_POST['x_constant'])."',
  `s_constant`  = '".mswSafeImportString($_POST['s_constant'])."',
  `learning`    = '{$_POST['learning']}',
  `num_parse`   = '{$_POST['num_parse']}',
  `uri_parse`   = '{$_POST['uri_parse']}',
  `html_parse`  = '{$_POST['html_parse']}',
  `multibyte`   = '{$_POST['multibyte']}',
  `encoder`     = '".mswSafeImportString($_POST['encoder'])."',
  `skipFilters` = '".mswSafeImportString($_POST['skipFilters'])."'
  ");
  // Are we clearing the learning filters?
  if (isset($_POST['reset'])) {
    // Reset older than X days or truncate all?
    if (isset($_POST['reset_days']) && (int)$_POST['reset_days']>0) {
	  $days = (int)$_POST['reset_days'];
	  mysql_query("DELETE FROM `".DB_PREFIX."imap_b8_filter` 
	  WHERE DATEDIFF(NOW(),DATE(FROM_UNIXTIME(`ts`))) >= ".$days."
	  AND `token` NOT IN('b8*dbversion','b8*texts')
	  ");
	} else {
      mysql_query("TRUNCATE TABLE `".DB_PREFIX."imap_b8_filter`");
	  mysql_query("INSERT INTO `".DB_PREFIX."imap_b8_filter` (`token`,`count_ham`,`ts`) values ('b8*dbversion', '".B8_VERSION."','0')");
      mysql_query("INSERT INTO `".DB_PREFIX."imap_b8_filter` (`token`,`count_ham`,`count_spam`,`ts`) values ('b8*texts', '0', '0','0')");
	}
  } else {
    // Anything to classify?
	if ($_POST['add-to']) {
	  // Load the b8 class..
	  include(REL_PATH.'control/lib/b8/call_b8.php');
	  switch ($_POST['classify']) {
	    case 'spam':
		$MSB8->learn(htmlspecialchars($_POST['add-to']),b8::SPAM);
		break;
		case 'ham':
		$MSB8->learn(htmlspecialchars($_POST['add-to']),b8::HAM);
		break;
	  }
	}
  }
}

public function enableDisable() {
  $_GET['id'] = (int)$_GET['id'];
  mysql_query("UPDATE `".DB_PREFIX."imap` SET
  `im_piping` = '".($_GET['changeState']=='icon-flag' ? 'no' : 'yes')."'
  WHERE `id`  = '{$_GET['id']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function addImapAccount() {
  $_POST                    = mswMultiDimensionalArrayMap('mswSafeImportString',$_POST);
  // Defaults if not set..
  $_POST['im_piping']       = (isset($_POST['im_piping']) ? 'yes' : 'no');
  $_POST['im_flags']        = (isset($_POST['im_flags']) ? imap::filterImapFlag($_POST['im_flags']) : '');
  $_POST['im_attach']       = (isset($_POST['im_attach']) ? 'yes' : 'no');
  $_POST['im_ssl']          = (isset($_POST['im_ssl']) ? 'yes' : 'no');
  $_POST['im_port']         = (int)$_POST['im_port'];
  $_POST['im_messages']     = (int)$_POST['im_messages'];
  $_POST['im_move']         = (isset($_POST['im_move']) ? $_POST['im_move'] : '');
  $_POST['im_spam']         = (isset($_POST['im_spam']) ? 'yes' : 'no');
  $_POST['im_spam_purge']   = (isset($_POST['im_spam_purge']) ? 'yes' : 'no');
  mysql_query("INSERT INTO `".DB_PREFIX."imap` (
  `im_piping`,
  `im_protocol`,
  `im_host`,
  `im_user`,
  `im_pass`,
  `im_port`,
  `im_name`,
  `im_flags`,
  `im_attach`,
  `im_move`,
  `im_messages`,
  `im_ssl`,
  `im_priority`,
  `im_dept`,
  `im_email`,
  `im_spam`,
  `im_spam_purge`,
  `im_score`
  ) VALUES (
  '{$_POST['im_piping']}',
  'imap',
  '{$_POST['im_host']}',
  '{$_POST['im_user']}',
  '{$_POST['im_pass']}',
  '{$_POST['im_port']}',
  '{$_POST['im_name']}',
  '{$_POST['im_flags']}',
  '{$_POST['im_attach']}',
  '{$_POST['im_move']}',
  '{$_POST['im_messages']}',
  '{$_POST['im_ssl']}',
  '{$_POST['im_priority']}',
  '{$_POST['im_dept']}',
  '{$_POST['im_email']}',
  '{$_POST['im_spam']}',
  '{$_POST['im_spam_purge']}',
  '{$_POST['im_score']}'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function editImapAccount() {
  $_POST                    = mswMultiDimensionalArrayMap('mswSafeImportString',$_POST);
  // Defaults if not set..
  $_POST['im_piping']       = (isset($_POST['im_piping']) ? 'yes' : 'no');
  $_POST['im_flags']        = (isset($_POST['im_flags']) ? imap::filterImapFlag($_POST['im_flags']) : '');
  $_POST['im_attach']       = (isset($_POST['im_attach']) ? 'yes' : 'no');
  $_POST['im_ssl']          = (isset($_POST['im_ssl']) ? 'yes' : 'no');
  $_POST['im_port']         = (int)$_POST['im_port'];
  $_POST['im_messages']     = (int)$_POST['im_messages'];
  $_POST['im_move']         = (isset($_POST['im_move']) ? $_POST['im_move'] : '');
  $_POST['im_spam']         = (isset($_POST['im_spam']) ? 'yes' : 'no');
  $_POST['im_spam_purge']   = (isset($_POST['im_spam_purge']) ? 'yes' : 'no');
  $_GET['edit']             = (int)$_GET['edit'];
  mysql_query("UPDATE `".DB_PREFIX."imap` SET
  `im_piping`      = '{$_POST['im_piping']}',
  `im_protocol`    = 'imap',
  `im_host`        = '{$_POST['im_host']}',
  `im_user`        = '{$_POST['im_user']}',
  `im_pass`        = '{$_POST['im_pass']}',
  `im_port`        = '{$_POST['im_port']}',
  `im_name`        = '{$_POST['im_name']}',
  `im_flags`       = '{$_POST['im_flags']}',
  `im_attach`      = '{$_POST['im_attach']}',
  `im_move`        = '{$_POST['im_move']}',
  `im_messages`    = '{$_POST['im_messages']}',
  `im_ssl`         = '{$_POST['im_ssl']}',
  `im_priority`    = '{$_POST['im_priority']}',
  `im_dept`        = '{$_POST['im_dept']}',
  `im_email`       = '{$_POST['im_email']}',
  `im_spam`        = '{$_POST['im_spam']}',
  `im_spam_purge`  = '{$_POST['im_spam_purge']}',
  `im_score`       = '{$_POST['im_score']}'
  WHERE `id`       = '{$_GET['edit']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

public function deleteImapAccounts() {
  if (!empty($_POST['del'])) {
    mysql_query("DELETE FROM `".DB_PREFIX."imap` 
    WHERE `id` IN(".implode(',',$_POST['del']).") 
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
	if (mswRowCount('imap')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."imap`");
    }
	return $rows;
  }
  return '0';
}

public function filterImapFlag($path) {
  if (substr($path,0,1)!='/') {
    $path = '/'.$path;
  }
  if (substr($path,-1)=='\\') {
    $path = substr_replace($path,'',-2);
  }
  return $path;
}

}

?>