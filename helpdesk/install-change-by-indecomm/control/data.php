<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: data.php
  Description: Installer File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  msw403();
}

$data = array();

//=========================
// INSTALL SETTINGS
//=========================
  
// HTTP Paths..
$hdeskPath    = 'http://www.example.com/helpdesk';
if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['PHP_SELF'])) {
  $hdeskPath   = 'http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],'install')-1);
}  
$hdeskPathAtt = $hdeskPath.'/content/attachments';
$hdeskPathFaq = $hdeskPath.'/content/attachments-faq';
// Server Paths..
$attachPath   = mswSafeImportString(substr(PATH,0,strpos(PATH,'install')-1).'/content/attachments');
$attFaqPath   = mswSafeImportString(substr(PATH,0,strpos(PATH,'install')-1).'/content/attachments-faq');
// Other..
$defKeepLogs  = mswSafeImportString('a:2:{s:4:"user";s:2:"50";s:3:"acc";s:2:"50";}');
$langSets     = mswSafeImportString('a:1:{s:7:"english";s:12:"_default_set";}');
$apiKey       = strtoupper(substr(md5(uniqid(rand(),1)),3,10).'-'.substr(md5(uniqid(rand(),1)),3,8));
mysql_query("TRUNCATE TABLE `".DB_PREFIX."settings`");
$q           = mysql_query("INSERT INTO `".DB_PREFIX."settings` (
`id`, `website`, `email`, `replyto`, `scriptpath`, `attachpath`, `attachhref`, `attachpathfaq`, `attachhreffaq`, 
`language`, `langSets`, `dateformat`, `timeformat`, `timezone`, `weekStart`, `jsDateFormat`, `kbase`, `enableVotes`, 
`multiplevotes`, `popquestions`, `quePerPage`, `cookiedays`, `renamefaq`, `attachment`, `rename`, `attachboxes`, 
`filetypes`, `maxsize`, `enableBBCode`, `afolder`, `autoClose`, `autoCloseMail`, `smtp_host`, `smtp_user`, `smtp_pass`, 
`smtp_port`, `smtp_security`, `smtp_debug`, `prodKey`, `publicFooter`, `adminFooter`, `encoderVersion`, `softwareVersion`, 
`apiKey`, `apiLog`, `apiHandlers`, `recaptchaPublicKey`, `recaptchaPrivateKey`, `enCapLogin`, `sysstatus`, `autoenable`, 
`disputes`, `offlineReason`, `createPref`, `createAcc`, `loginLimit`, `banTime`, `ticketHistory`, `backupEmails`, 
`closenotify`, `minPassValue`, `accProfNotify`, `newAccNotify`, `recaptchaTheme`, `recaptchaLang`, `enableLog`, 
`defKeepLogs`, `minTickDigits`, `enableMail`, `imap_debug`, `imap_param`, `imap_memory`, `imap_timeout`, 
`disputeAdminStop`
) VALUES (
1, '".mswSafeImportString($_POST['website'])."', '".mswSafeImportString($_POST['email'])."', '',
'{$hdeskPath}', '{$attachPath}', '{$hdeskPathAtt}', '{$attFaqPath}', '{$hdeskPathFaq}', 
'english', '{$langSets}', 'd M Y', 'H:iA', '".mswSafeImportString($_POST['timezone'])."', 'sun', 'DD-MM-YYYY', 'yes', 
'yes', 'yes', 10, 10, 360, 'no', 'yes', 'yes', 5, '.jpg|.zip|.gif|.rar|.png|.pdf', 1048576, 'yes', 
'admin', 0, 'yes', '', '', '', 587, '', 'no', '{$prodKey}', '', '', '".(function_exists('ioncube_loader_version') ? ioncube_loader_version() : 'XX')."', 
'".SCRIPT_VERSION."', '{$apiKey}', 'yes', 'json,xml', '', '', 'yes', 'yes', '0000-00-00', 'no', '', 'no', 'yes', 5, 5, 'yes', '', 'no', 8, 
'yes', 'yes', 'white', 'en', 'yes', '{$defKeepLogs}', 5, 'yes', 'yes', 'pipe', '0', '0', 'no'
)");
if (!$q) {
  $data[]  = DB_PREFIX.'settings';
  mswlogDBError(DB_PREFIX.'settings',mysql_error(),mysql_errno(),__LINE__,__FILE__,'Insert');
}

//=========================
// INSTALL DEPARTMENTS
//=========================

$depts = array('General Tickets','Sales and Billing','Technical Support');
mysql_query("TRUNCATE TABLE `".DB_PREFIX."departments`");
for ($i=0; $i<count($depts); $i++) {
  $deptID = ($i+1);
  $q      = mysql_query("INSERT INTO `".DB_PREFIX."departments` (
  `id`, `name`, `showDept`, `dept_subject`, `dept_comments`, `orderBy`, `manual_assign`
  ) VALUES (
  ".$deptID.", '".$depts[$i]."', 'yes', '', '', '".$deptID."', 'no'
  )");
  
  if (!$q) {
    $data[]  = DB_PREFIX.'departments';
    mswlogDBError(DB_PREFIX.'departments',mysql_error(),mysql_errno(),__LINE__,__FILE__,'Insert '.$deptID);
  }
}

//=========================
// INSTALL LEVELS
//=========================

$levels = array('Low','Medium','High');
mysql_query("TRUNCATE TABLE `".DB_PREFIX."levels`");
for ($i=0; $i<count($levels); $i++) {
  $levelID = ($i+1);
  $q       = mysql_query("INSERT INTO `".DB_PREFIX."levels` (`id`, `name`, `display`, `marker`, `orderBy`) VALUES (".$levelID.", '".$levels[$i]."', 'yes', '".strtolower($levels[$i])."', ".$levelID.")");
  if (!$q) {
    $data[]  = DB_PREFIX.'levels';
    mswlogDBError(DB_PREFIX.'levels',mysql_error(),mysql_errno(),__LINE__,__FILE__,'Insert '.$levelID);
  }
}

//=========================
// B8 Filters
//=========================

mysql_query("TRUNCATE TABLE `".DB_PREFIX."imap_b8`");
mysql_query("TRUNCATE TABLE `".DB_PREFIX."imap_b8_filter`");
$q = mysql_query("INSERT INTO `".DB_PREFIX."imap_b8` (`id`, `tokens`, `min_size`, `max_size`, `min_dev`, `x_constant`, `s_constant`, `learning`, 
`num_parse`, `uri_parse`, `html_parse`, `multibyte`, `encoder`, `skipFilters`
) VALUES (
1, 15, 3, 30, '0.5', '0.5', '0.3', 'yes', 'no', 'yes', 'yes', 'yes', 'UTF-8', 'mailer-daemon')");
if (!$q) {
  $data[]  = DB_PREFIX.'imap_b8';
  mswlogDBError(DB_PREFIX.'imap_b8',mysql_error(),mysql_errno(),__LINE__,__FILE__,'Insert 1');
}

$q = mysql_query("INSERT INTO `".DB_PREFIX."imap_b8_filter` (`token`, `count_ham`, `count_spam`, `ts`) VALUES ('b8*dbversion', 3, 0, 0)");
if (!$q) {
  $data[]  = DB_PREFIX.'imap_b8_filter';
  mswlogDBError(DB_PREFIX.'imap_b8_filter',mysql_error(),mysql_errno(),__LINE__,__FILE__,'Insert 1');
}

$q = mysql_query("INSERT INTO `".DB_PREFIX."imap_b8_filter` (`token`, `count_ham`, `count_spam`, `ts`) VALUES ('b8*texts', 0, 0, 0)");
if (!$q) {
  $data[]  = DB_PREFIX.'imap_b8_filter';
  mswlogDBError(DB_PREFIX.'imap_b8_filter',mysql_error(),mysql_errno(),__LINE__,__FILE__,'Insert 2');
}

?>
