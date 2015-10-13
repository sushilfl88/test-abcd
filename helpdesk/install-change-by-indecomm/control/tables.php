<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: tables.php
  Description: Installer File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  msw403();
}

$v       = (isset($_POST['mysql_version']) ? $_POST['mysql_version'] : 'MySQL4');
$c       = $_POST['charset'];
$tableD  = array();

switch($v) {
  case 'MySQL4':
  if ($c) {
    $split = explode('_',$c);
    $tableType = 'DEFAULT CHARACTER SET '.$split[0].PHP_EOL;
    $tableType.= 'COLLATE '.$c.PHP_EOL;
  }
  $tableType .= 'TYPE = MyISAM';
  break;
  case 'MySQL5':
  if ($c) {
    $split = explode('_',$c);
    $tableType = 'DEFAULT CHARACTER SET '.$split[0].PHP_EOL;
    $tableType.= 'COLLATE '.$c.PHP_EOL;
  }
  $tableType .= 'ENGINE = MyISAM';
  break;
}
 
//============================================================
// INSTALL TABLE...ATTACHMENTS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."attachments`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."attachments` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `ticketID` varchar(20) NOT NULL DEFAULT '',
  `replyID` int(11) NOT NULL DEFAULT '0',
  `department` int(5) NOT NULL DEFAULT '0',
  `fileName` varchar(250) NOT NULL DEFAULT '',
  `fileSize` varchar(20) NOT NULL DEFAULT '',
  `mimeType` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX `tickid_index` (`ticketID`),
  INDEX `repid_index` (`replyID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'attachments';
  mswlogDBError(DB_PREFIX.'attachments',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...BAN..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."ban`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ban` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(250) NOT NULL DEFAULT '',
  `count` int(5) NOT NULL DEFAULT '0',
  `banstamp` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'ban';
  mswlogDBError(DB_PREFIX.'ban',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...CATEGORIES..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."categories`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."categories` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `summary` varchar(250) NOT NULL DEFAULT '',
  `enCat` enum('yes','no') NOT NULL DEFAULT 'yes',
  `orderBy` int(5) NOT NULL DEFAULT '0',
  `subcat` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'categories';
  mswlogDBError(DB_PREFIX.'categories',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...CUSFIELDS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."cusfields`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."cusfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fieldInstructions` varchar(250) NOT NULL DEFAULT '',
  `fieldType` enum('textarea','input','select','checkbox') NOT NULL DEFAULT 'input',
  `fieldReq` enum('yes','no') NOT NULL DEFAULT 'no',
  `fieldOptions` text default null,
  `fieldLoc` varchar(25) NOT NULL DEFAULT '',
  `orderBy` int(5) NOT NULL DEFAULT '0',
  `repeatPref` enum('yes','no') NOT NULL DEFAULT 'yes',
  `enField` enum('yes','no') NOT NULL DEFAULT 'yes',
  `departments` text default null,
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'cusfields';
  mswlogDBError(DB_PREFIX.'cusfields',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...DEPARTMENTS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."departments`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."departments` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `showDept` enum('yes','no') NOT NULL DEFAULT 'no',
  `dept_subject` text default null,
  `dept_comments` text default null,
  `orderBy` int(5) NOT NULL DEFAULT '0',
  `manual_assign` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'departments';
  mswlogDBError(DB_PREFIX.'departments',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...DISPUTES..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."disputes`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."disputes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticketID` int(15) NOT NULL DEFAULT '0',
  `visitorID` int(8) NOT NULL DEFAULT '0',
  `postPrivileges` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  INDEX `tickid_index` (`ticketID`),
  INDEX `vis_index` (`visitorID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'disputes';
  mswlogDBError(DB_PREFIX.'disputes',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...FAQ..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."faq`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."faq` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `question` text default null,
  `answer` text default null,
  `kviews` int(10) NOT NULL DEFAULT '0',
  `kuseful` int(10) NOT NULL DEFAULT '0',
  `knotuseful` int(10) NOT NULL DEFAULT '0',
  `enFaq` enum('yes','no') NOT NULL DEFAULT 'yes',
  `orderBy` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'faq';
  mswlogDBError(DB_PREFIX.'faq',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...FAQASSIGN..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."faqassign`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."faqassign` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `question` int(7) NOT NULL DEFAULT '0',
  `itemID` int(7) NOT NULL DEFAULT '0',
  `desc` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX `que_index` (`question`),
  INDEX `att_index` (`itemID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'faqassign';
  mswlogDBError(DB_PREFIX.'faqassign',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...FAQATTACH..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."faqattach`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."faqattach` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '',
  `remote` varchar(250) NOT NULL DEFAULT '',
  `path` varchar(250) NOT NULL DEFAULT '',
  `size` varchar(30) NOT NULL DEFAULT '',
  `orderBy` int(8) NOT NULL DEFAULT '0',
  `enAtt` enum('yes','no') NOT NULL DEFAULT 'yes',
  `mimeType` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'faqattach';
  mswlogDBError(DB_PREFIX.'faqattach',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...IMAP..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."imap`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."imap` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `im_piping` enum('yes','no') NOT NULL DEFAULT 'no',
  `im_protocol` enum('pop3','imap') NOT NULL DEFAULT 'imap',
  `im_host` varchar(100) NOT NULL DEFAULT '',
  `im_user` varchar(250) NOT NULL DEFAULT '',
  `im_pass` varchar(100) NOT NULL DEFAULT '',
  `im_port` int(5) NOT NULL DEFAULT '110',
  `im_name` varchar(50) NOT NULL DEFAULT '',
  `im_flags` varchar(250) NOT NULL DEFAULT '',
  `im_attach` enum('yes','no') NOT NULL DEFAULT 'no',
  `im_move` varchar(50) NOT NULL DEFAULT '',
  `im_messages` int(3) NOT NULL DEFAULT '20',
  `im_ssl` enum('yes','no') NOT NULL DEFAULT 'no',
  `im_priority` varchar(250) NOT NULL DEFAULT '',
  `im_dept` int(5) NOT NULL DEFAULT '0',
  `im_email` varchar(250) NOT NULL DEFAULT '',
  `im_spam` enum('yes','no') NOT NULL DEFAULT 'no',
  `im_spam_purge` enum('yes','no') NOT NULL DEFAULT 'no',
  `im_score` varchar(10) NOT NULL DEFAULT '1.0',
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'imap';
  mswlogDBError(DB_PREFIX.'imap',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...IMAP_B8..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."imap_b8`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."imap_b8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tokens` int(5) NOT NULL DEFAULT '0',
  `min_size` int(5) NOT NULL DEFAULT '0',
  `max_size` int(5) NOT NULL DEFAULT '0',
  `min_dev` varchar(5) NOT NULL DEFAULT '0.5',
  `x_constant` varchar(5) NOT NULL DEFAULT '0.5',
  `s_constant` varchar(5) NOT NULL DEFAULT '0.3',
  `learning` enum('yes','no') NOT NULL DEFAULT 'yes',
  `num_parse` enum('yes','no') NOT NULL DEFAULT 'no',
  `uri_parse` enum('yes','no') NOT NULL DEFAULT 'yes',
  `html_parse` enum('yes','no') NOT NULL DEFAULT 'yes',
  `multibyte` enum('yes','no') NOT NULL DEFAULT 'no',
  `encoder` varchar(50) NOT NULL DEFAULT 'utf-8',
  `skipFilters` text default null,
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'imap_b8';
  mswlogDBError(DB_PREFIX.'imap_b8',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...IMAP_B8_FILTER..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."imap_b8_filter`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."imap_b8_filter` (
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `count_ham` int(10) unsigned NOT NULL DEFAULT '0',
  `count_spam` int(10) unsigned NOT NULL DEFAULT '0',
  `ts` int(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`token`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'imap_b8_filter';
  mswlogDBError(DB_PREFIX.'imap_b8_filter',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...LEVELS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."levels`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."levels` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `display` enum('yes','no') NOT NULL DEFAULT 'no',
  `marker` varchar(100) NOT NULL DEFAULT '',
  `orderBy` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'levels';
  mswlogDBError(DB_PREFIX.'levels',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...LOG..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."log`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `userID` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(250) NOT NULL DEFAULT '',
  `type` enum('user','acc') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  INDEX `useid_index` (`userID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'log';
  mswlogDBError(DB_PREFIX.'log',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...MAILASSOC..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."mailassoc`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."mailassoc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staffID` int(8) NOT NULL DEFAULT '0',
  `mailID` int(8) NOT NULL DEFAULT '0',
  `folder` varchar(10) NOT NULL DEFAULT 'inbox',
  `status` enum('read','unread') NOT NULL DEFAULT 'unread',
  `lastUpdate` int(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `staff_index` (`staffID`),
  INDEX `mail_index` (`mailID`),
  INDEX `status_index` (`status`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'mailassoc';
  mswlogDBError(DB_PREFIX.'mailassoc',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...MAILBOX..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."mailbox`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."mailbox` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `staffID` int(8) NOT NULL DEFAULT '0',
  `subject` varchar(250) NOT NULL DEFAULT '',
  `message` text default null,
  PRIMARY KEY (`id`),
  INDEX `staff_index` (`staffID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'mailbox';
  mswlogDBError(DB_PREFIX.'mailbox',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...MAILFOLDERS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."mailfolders`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."mailfolders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staffID` int(8) NOT NULL DEFAULT '0',
  `folder` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX `staff_index` (`staffID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'mailfolders';
  mswlogDBError(DB_PREFIX.'mailfolders',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...MAILREPLIES..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."mailreplies`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."mailreplies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `mailID` int(8) NOT NULL DEFAULT '0',
  `staffID` int(8) NOT NULL DEFAULT '0',
  `message` text default null,
  PRIMARY KEY (`id`),
  INDEX `mail_index` (`mailID`),
  INDEX `staff_index` (`staffID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'mailreplies';
  mswlogDBError(DB_PREFIX.'mailreplies',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...PORTAL..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."portal`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."portal` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `ts` int(30) NOT NULL DEFAULT '0',
  `email` varchar(250) NOT NULL DEFAULT '',
  `userPass` varchar(40) NOT NULL DEFAULT '',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  `verified` enum('yes','no') NOT NULL DEFAULT 'no',
  `timezone` varchar(50) NOT NULL DEFAULT 'Europe/London',
  `ip` text default null,
  `notes` text default null,
  `reason` text default null,
  `system1` varchar(250) NOT NULL DEFAULT '',
  `system2` varchar(250) NOT NULL DEFAULT '',
  `language` varchar(100) NOT NULL DEFAULT '',
  `enableLog` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  INDEX `nme_index` (`name`),
  INDEX `em_index` (`email`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'portal';
  mswlogDBError(DB_PREFIX.'portal',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...REPLIES..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."replies`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."replies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `ticketID` int(15) NOT NULL DEFAULT '0',
  `comments` text default null,
  `mailBodyFilter` varchar(30) NOT NULL DEFAULT '',
  `replyType` enum('none','visitor','admin') NOT NULL DEFAULT 'none',
  `replyUser` int(8) NOT NULL DEFAULT '0',
  `isMerged` enum('yes','no') NOT NULL DEFAULT 'no',
  `ipAddresses` text default null,
  `disputeUser` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `tickid_index` (`ticketID`),
  INDEX `repuse_index` (`replyUser`),
  INDEX `disuse_index` (`disputeUser`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'replies';
  mswlogDBError(DB_PREFIX.'replies',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...RESPONSES..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."responses`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."responses` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `title` text default null,
  `answer` text default null,
  `enResponse` enum('yes','no') NOT NULL DEFAULT 'yes',
  `orderBy` int(8) NOT NULL DEFAULT '0',
  `departments` text default null,
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'responses';
  mswlogDBError(DB_PREFIX.'responses',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...SETTINGS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."settings`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."settings` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `website` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(250) NOT NULL DEFAULT '',
  `replyto` varchar(250) NOT NULL DEFAULT '',
  `scriptpath` varchar(250) NOT NULL DEFAULT '',
  `attachpath` varchar(250) NOT NULL DEFAULT '',
  `attachhref` varchar(250) NOT NULL DEFAULT '',
  `attachpathfaq` varchar(250) NOT NULL DEFAULT '',
  `attachhreffaq` varchar(250) NOT NULL DEFAULT '',
  `language` varchar(250) not null default 'english',
  `langSets` text default null,
  `dateformat` varchar(20) NOT NULL DEFAULT 'd M Y',
  `timeformat` varchar(15) NOT NULL DEFAULT 'H:iA',
  `timezone` varchar(50) NOT NULL DEFAULT 'Europe/London',
  `weekStart` enum('mon','sun') NOT NULL DEFAULT 'sun',
  `jsDateFormat` varchar(15) NOT NULL DEFAULT 'DD/MM/YYYY',
  `kbase` enum('yes','no') NOT NULL DEFAULT 'yes',
  `enableVotes` enum('yes','no') NOT NULL DEFAULT 'yes',
  `multiplevotes` enum('yes','no') NOT NULL DEFAULT 'no',
  `popquestions` int(5) NOT NULL DEFAULT '0',
  `quePerPage` int(3) NOT NULL DEFAULT '10',
  `cookiedays` int(5) NOT NULL DEFAULT '0',
  `renamefaq` enum('yes','no') NOT NULL DEFAULT 'no',
  `attachment` enum('yes','no') NOT NULL DEFAULT 'no',
  `rename` enum('yes','no') NOT NULL DEFAULT 'no',
  `attachboxes` int(3) NOT NULL DEFAULT '2',
  `filetypes` text default null,
  `maxsize` int(15) NOT NULL DEFAULT '1048576',
  `enableBBCode` enum('yes','no') NOT NULL DEFAULT 'yes',
  `afolder` varchar(50) NOT NULL DEFAULT '',
  `autoClose` int(5) NOT NULL DEFAULT '0',
  `autoCloseMail` enum('yes','no') NOT NULL DEFAULT 'yes',
  `smtp_host` varchar(100) NOT NULL DEFAULT 'localhost',
  `smtp_user` varchar(100) NOT NULL DEFAULT '',
  `smtp_pass` varchar(100) NOT NULL DEFAULT '',
  `smtp_port` int(4) NOT NULL DEFAULT '25',
  `smtp_security` varchar(10) NOT NULL DEFAULT '',
  `smtp_debug` enum('yes','no') NOT NULL DEFAULT 'no',
  `prodKey` char(60) NOT NULL DEFAULT '',
  `publicFooter` text default null,
  `adminFooter` text default null,
  `encoderVersion` varchar(5) NOT NULL DEFAULT '',
  `softwareVersion` varchar(10) NOT NULL DEFAULT '',
  `apiKey` varchar(100) NOT NULL DEFAULT '',
  `apiLog` enum('yes','no') NOT NULL DEFAULT 'no',
  `apiHandlers` varchar(100) NOT NULL DEFAULT '',
  `recaptchaPublicKey` varchar(250) NOT NULL DEFAULT '',
  `recaptchaPrivateKey` varchar(250) NOT NULL DEFAULT '',
  `enCapLogin` enum('yes','no') NOT NULL DEFAULT 'yes',
  `sysstatus` enum('yes','no') NOT NULL DEFAULT 'yes',
  `autoenable` date NOT NULL DEFAULT '0000-00-00',
  `disputes` enum('yes','no') NOT NULL DEFAULT 'no',
  `offlineReason` text default null,
  `createPref` enum('yes','no') NOT NULL DEFAULT 'yes',
  `createAcc` enum('yes','no') NOT NULL DEFAULT 'yes',
  `loginLimit` int(5) NOT NULL DEFAULT '0',
  `banTime` int(5) NOT NULL DEFAULT '0',
  `ticketHistory` enum('yes','no') NOT NULL DEFAULT 'yes',
  `backupEmails` text default null,
  `closenotify` enum('yes','no') NOT NULL DEFAULT 'no',
  `minPassValue` int(3) NOT NULL DEFAULT '8',
  `accProfNotify` enum('yes','no') NOT NULL DEFAULT 'yes',
  `newAccNotify` enum('yes','no') NOT NULL DEFAULT 'yes',
  `recaptchaTheme` varchar(20) NOT NULL DEFAULT 'white',
  `recaptchaLang` char(2) NOT NULL DEFAULT 'en',
  `enableLog` enum('yes','no') NOT NULL DEFAULT 'yes',
  `defKeepLogs` varchar(100) NOT NULL DEFAULT '',
  `minTickDigits` int(2) NOT NULL DEFAULT '5',
  `enableMail` enum('yes','no') NOT NULL DEFAULT 'yes',
  `imap_debug` enum('yes','no') NOT NULL DEFAULT 'no',
  `imap_param` varchar(10) NOT NULL DEFAULT 'pipe',
  `imap_memory` varchar(3) NOT NULL DEFAULT '10',
  `imap_timeout` varchar(3) NOT NULL DEFAULT '120',
  `disputeAdminStop` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'settings';
  mswlogDBError(DB_PREFIX.'settings',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...TICKETFIELDS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."ticketfields`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ticketfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticketID` varchar(20) NOT NULL DEFAULT '',
  `fieldID` int(15) NOT NULL DEFAULT '0',
  `replyID` int(15) NOT NULL DEFAULT '0',
  `fieldData` text default null,
  PRIMARY KEY (`id`),
  INDEX `tickid_index` (`ticketID`),
  INDEX `fldid_index` (`fieldID`),
  INDEX `repid_index` (`replyID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'ticketfields';
  mswlogDBError(DB_PREFIX.'ticketfields',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...TICKETHISTORY..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."tickethistory`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."tickethistory` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `ticketID` int(11) NOT NULL DEFAULT '0',
  `action` text default null,
  PRIMARY KEY (`id`),
  INDEX `ticket_index` (`ticketID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'tickethistory';
  mswlogDBError(DB_PREFIX.'tickethistory',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...TICKETS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."tickets`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `lastrevision` int(30) NOT NULL DEFAULT '0',
  `department` int(8) NOT NULL DEFAULT '0',
  `assignedto` varchar(200) NOT NULL DEFAULT '',
  `visitorID` int(8) NOT NULL DEFAULT '0',
  `subject` varchar(250) NOT NULL DEFAULT '',
  `mailBodyFilter` varchar(30) NOT NULL DEFAULT '',
  `comments` text default null,
  `priority` varchar(250) NOT NULL DEFAULT '',
  `replyStatus` enum('start','visitor','admin') NOT NULL DEFAULT 'start',
  `ticketStatus` enum('open','close','closed','submit_report') NOT NULL DEFAULT 'open',
  `ipAddresses` text default null,
  `ticketNotes` text default null,
  `isDisputed` enum('yes','no') NOT NULL DEFAULT 'no',
  `disPostPriv` enum('yes','no') NOT NULL DEFAULT 'yes',
  `source` varchar(10) NOT NULL DEFAULT 'standard',
  `spamFlag` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  INDEX `depid_index` (`department`),
  INDEX `pry_index` (`priority`),
  INDEX `isdis_index` (`isDisputed`),
  INDEX `ts_index` (`ts`),
  INDEX `vis_index` (`visitorID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'tickets';
  mswlogDBError(DB_PREFIX.'tickets',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...USERDEPTS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."userdepts`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."userdepts` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `userID` int(5) NOT NULL DEFAULT '0',
  `deptID` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `userid_index` (`userID`),
  INDEX `depid_index` (`deptID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'userdepts';
  mswlogDBError(DB_PREFIX.'userdepts',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...USERS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."users`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(250) NOT NULL DEFAULT '',
  `email2` text default null,
  `accpass` varchar(32) NOT NULL DEFAULT '',
  `signature` text default null,
  `notify` enum('yes','no') NOT NULL DEFAULT 'yes',
  `pageAccess` text default null,
  `emailSigs` enum('yes','no') NOT NULL DEFAULT 'no',
  `notePadEnable` enum('yes','no') NOT NULL DEFAULT 'yes',
  `delPriv` enum('yes','no') NOT NULL DEFAULT 'no',
  `nameFrom` varchar(250) NOT NULL DEFAULT '',
  `emailFrom` varchar(250) NOT NULL DEFAULT '',
  `assigned` enum('yes','no') NOT NULL DEFAULT 'no',
  `timezone` varchar(50) NOT NULL DEFAULT 'Europe/London',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  `notes` text default null,
  `ticketHistory` enum('yes','no') NOT NULL DEFAULT 'yes',
  `enableLog` enum('yes','no') NOT NULL DEFAULT 'yes',
  `mailbox` enum('yes','no') NOT NULL DEFAULT 'yes',
  `mailFolders` int(3) NOT NULL DEFAULT '5',
  `mailDeletion` enum('yes','no') NOT NULL DEFAULT 'yes',
  `mailScreen` enum('yes','no') NOT NULL DEFAULT 'yes',
  `mailCopy` enum('yes','no') NOT NULL DEFAULT 'yes',
  `mailPurge` int(3) NOT NULL DEFAULT '0',
  `addpages` text default null,
  `mergeperms` enum('yes','no') NOT NULL DEFAULT 'yes',
  `digest` enum('yes','no') NOT NULL DEFAULT 'yes',
  `digestasg` enum('yes','no') NOT NULL DEFAULT 'no',
  `profile` enum('yes','no') NOT NULL DEFAULT 'yes',
  `helplink` enum('yes','no') NOT NULL DEFAULT 'no',
  `defDays` int(3) NOT NULL DEFAULT '45',
  PRIMARY KEY (`id`),
  INDEX `email_index` (`email`),
  INDEX `nty_index` (`notify`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'users';
  mswlogDBError(DB_PREFIX.'users',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

//============================================================
// INSTALL TABLE...USERSACCESS..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."usersaccess`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."usersaccess` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `page` varchar(100) NOT NULL DEFAULT '',
  `userID` varchar(250) NOT NULL DEFAULT '',
  `type` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX `user_index` (`userID`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'tickets';
  mswlogDBError(DB_PREFIX.'tickets',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}


//============================================================
// INSTALL TABLE...ROLES..
//============================================================

mysql_query("DROP TABLE IF EXISTS `".DB_PREFIX."roles`");
$query = mysql_query("
CREATE TABLE IF NOT EXISTS `".DB_PREFIX."roles` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) $tableType");

if (!$query) {
  $tableD[]  = DB_PREFIX.'roles';
  mswlogDBError(DB_PREFIX.'roles',mysql_error(),mysql_errno(),__LINE__,__FILE__);
}

?>