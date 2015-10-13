--
-- DB Dump for Maian Support
-- MySQL5 Only
--

-- Dumping structure for table msp_attachments
DROP TABLE IF EXISTS `msp_attachments`;
CREATE TABLE IF NOT EXISTS `msp_attachments` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_ban
DROP TABLE IF EXISTS `msp_ban`;
CREATE TABLE IF NOT EXISTS `msp_ban` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(250) NOT NULL DEFAULT '',
  `count` int(5) NOT NULL DEFAULT '0',
  `banstamp` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_categories
DROP TABLE IF EXISTS `msp_categories`;
CREATE TABLE IF NOT EXISTS `msp_categories` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `summary` varchar(250) NOT NULL DEFAULT '',
  `enCat` enum('yes','no') NOT NULL DEFAULT 'yes',
  `orderBy` int(5) NOT NULL DEFAULT '0',
  `subcat` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_cusfields
DROP TABLE IF EXISTS `msp_cusfields`;
CREATE TABLE IF NOT EXISTS `msp_cusfields` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_departments
DROP TABLE IF EXISTS `msp_departments`;
CREATE TABLE IF NOT EXISTS `msp_departments` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `showDept` enum('yes','no') NOT NULL DEFAULT 'no',
  `dept_subject` text default null,
  `dept_comments` text default null,
  `orderBy` int(5) NOT NULL DEFAULT '0',
  `manual_assign` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table msp_departments: 3 rows
INSERT INTO `msp_departments` (`id`, `name`, `showDept`, `dept_subject`, `dept_comments`, `orderBy`, `manual_assign`) VALUES
(1, 'General Tickets', 'yes', '', '', 1, 'no'),
(2, 'Sales and Billing', 'yes', '', '', 2, 'no'),
(3, 'Technical Support', 'yes', '', '', 3, 'no');

-- Dumping structure for table msp_disputes
DROP TABLE IF EXISTS `msp_disputes`;
CREATE TABLE IF NOT EXISTS `msp_disputes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticketID` int(15) NOT NULL DEFAULT '0',
  `visitorID` int(8) NOT NULL DEFAULT '0',
  `postPrivileges` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  INDEX `tickid_index` (`ticketID`),
  INDEX `vis_index` (`visitorID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_faq
DROP TABLE IF EXISTS `msp_faq`;
CREATE TABLE IF NOT EXISTS `msp_faq` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_faqassign
DROP TABLE IF EXISTS `msp_faqassign`;
CREATE TABLE IF NOT EXISTS `msp_faqassign` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `question` int(7) NOT NULL DEFAULT '0',
  `itemID` int(7) NOT NULL DEFAULT '0',
  `desc` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX `que_index` (`question`),
  INDEX `att_index` (`itemID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_faqattach
DROP TABLE IF EXISTS `msp_faqattach`;
CREATE TABLE IF NOT EXISTS `msp_faqattach` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_imap
DROP TABLE IF EXISTS `msp_imap`;
CREATE TABLE IF NOT EXISTS `msp_imap` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_imap_b8
DROP TABLE IF EXISTS `msp_imap_b8`;
CREATE TABLE IF NOT EXISTS `msp_imap_b8` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table msp_imap_b8: 1 row
INSERT INTO `msp_imap_b8` (`id`, `tokens`, `min_size`, `max_size`, `min_dev`, `x_constant`, `s_constant`, `learning`, `num_parse`, `uri_parse`, 
`html_parse`, `multibyte`, `encoder`, `skipFilters`) VALUES (1, 15, 3, 30, '0.5', '0.5', '0.3', 'yes', 'no', 'yes', 'yes', 'yes', 'UTF-8', 'mailer-daemon');

-- Dumping structure for table msp_imap_b8_filter
DROP TABLE IF EXISTS `msp_imap_b8_filter`;
CREATE TABLE IF NOT EXISTS `msp_imap_b8_filter` (
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `count_ham` int(10) unsigned NOT NULL DEFAULT '0',
  `count_spam` int(10) unsigned NOT NULL DEFAULT '0',
  `ts` int(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table msp_imap_b8_filter: 2 rows
INSERT INTO `msp_imap_b8_filter` (`token`, `count_ham`, `count_spam`, `ts`) VALUES ('b8*dbversion', 3, 0, 0);
INSERT INTO `msp_imap_b8_filter` (`token`, `count_ham`, `count_spam`, `ts`) VALUES ('b8*texts', 0, 0, 0);

-- Dumping structure for table msp_levels
DROP TABLE IF EXISTS `msp_levels`;
CREATE TABLE IF NOT EXISTS `msp_levels` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `display` enum('yes','no') NOT NULL DEFAULT 'no',
  `marker` varchar(100) NOT NULL DEFAULT '',
  `orderBy` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table msp_levels: 3 rows
INSERT INTO `msp_levels` (`id`, `name`, `display`, `marker`, `orderBy`) VALUES
(1, 'Low', 'yes', 'low', 1),
(2, 'Medium', 'yes', 'medium', 2),
(3, 'High', 'yes', 'high', 3);

-- Dumping structure for table msp_log
DROP TABLE IF EXISTS `msp_log`;
CREATE TABLE IF NOT EXISTS `msp_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `userID` int(5) NOT NULL DEFAULT '0',
  `ip` varchar(250) NOT NULL DEFAULT '',
  `type` enum('user','acc') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  INDEX `useid_index` (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_mailassoc
DROP TABLE IF EXISTS `msp_mailassoc`;
CREATE TABLE IF NOT EXISTS `msp_mailassoc` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_mailbox
DROP TABLE IF EXISTS `msp_mailbox`;
CREATE TABLE IF NOT EXISTS `msp_mailbox` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `staffID` int(8) NOT NULL DEFAULT '0',
  `subject` varchar(250) NOT NULL DEFAULT '',
  `message` text default null,
  PRIMARY KEY (`id`),
  INDEX `staff_index` (`staffID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_mailfolders
DROP TABLE IF EXISTS `msp_mailfolders`;
CREATE TABLE IF NOT EXISTS `msp_mailfolders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staffID` int(8) NOT NULL DEFAULT '0',
  `folder` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX `staff_index` (`staffID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_mailreplies
DROP TABLE IF EXISTS `msp_mailreplies`;
CREATE TABLE IF NOT EXISTS `msp_mailreplies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `mailID` int(8) NOT NULL DEFAULT '0',
  `staffID` int(8) NOT NULL DEFAULT '0',
  `message` text default null,
  PRIMARY KEY (`id`),
  INDEX `mail_index` (`mailID`),
  INDEX `staff_index` (`staffID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_portal
DROP TABLE IF EXISTS `msp_portal`;
CREATE TABLE IF NOT EXISTS `msp_portal` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_replies
DROP TABLE IF EXISTS `msp_replies`;
CREATE TABLE IF NOT EXISTS `msp_replies` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_responses
DROP TABLE IF EXISTS `msp_responses`;
CREATE TABLE IF NOT EXISTS `msp_responses` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `title` text default null,
  `answer` text default null,
  `enResponse` enum('yes','no') NOT NULL DEFAULT 'yes',
  `orderBy` int(8) NOT NULL DEFAULT '0',
  `departments` text default null,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_settings
DROP TABLE IF EXISTS `msp_settings`;
CREATE TABLE IF NOT EXISTS `msp_settings` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table msp_settings: 1 rows
INSERT INTO `msp_settings` (`id`, `website`, `email`, `replyto`, `scriptpath`, `attachpath`, `attachhref`, `attachpathfaq`, `attachhreffaq`, `language`, 
`langSets`, `dateformat`, `timeformat`, `timezone`, `weekStart`, `jsDateFormat`, `kbase`, `enableVotes`, `multiplevotes`, `popquestions`, `quePerPage`, 
`cookiedays`, `renamefaq`, `attachment`, `rename`, `attachboxes`, `filetypes`, `maxsize`, `enableBBCode`, `afolder`, `autoClose`, `autoCloseMail`, 
`smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `smtp_security`, `smtp_debug`, `prodKey`, `publicFooter`, `adminFooter`, `encoderVersion`, 
`softwareVersion`, `apiKey`, `apiLog`, `apiHandlers`, `recaptchaPublicKey`, `recaptchaPrivateKey`, `enCapLogin`, `sysstatus`, `autoenable`, `disputes`, 
`offlineReason`, `createPref`, `createAcc`, `loginLimit`, `banTime`, `ticketHistory`, `backupEmails`, `closenotify`, `minPassValue`, `accProfNotify`, 
`newAccNotify`, `recaptchaTheme`, `recaptchaLang`, `enableLog`, `defKeepLogs`, `minTickDigits`, `enableMail`, `imap_debug`, `imap_param`, `imap_memory`, 
`imap_timeout`, `disputeAdminStop`) VALUES (
1, 'HelpDesk', '', '', '', '', '', '', '', 'english', 'a:1:{s:7:"english";s:12:"_default_set";}', 'd M Y', 'H:iA', 'Europe/London', 
'sun', 'DD-MM-YYYY', 'yes', 'yes', 'yes', 10, 10, 360, 'no', 'yes', 'yes', 5, '.jpg|.zip|.gif|.rar|.png|.pdf', 1048576, 'yes', 'admin', 0, 'yes', '', '', 
'', 587, '', 'no', '', '', '', '', '', '', 'yes', 'json,xml', '', '', 'yes', 'yes', '0000-00-00', 'no', '', 'no', 'yes', 5, 5, 'yes', '', 'no', 8, 
'yes', 'yes', 'red', 'en', 'yes', 'a:2:{s:4:"user";s:2:"50";s:3:"acc";s:2:"50";}', 5, 'yes', 'yes', 'pipe', '0', '0', 'no');

-- Dumping structure for table msp_ticketfields
DROP TABLE IF EXISTS `msp_ticketfields`;
CREATE TABLE IF NOT EXISTS `msp_ticketfields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticketID` varchar(20) NOT NULL DEFAULT '',
  `fieldID` int(15) NOT NULL DEFAULT '0',
  `replyID` int(15) NOT NULL DEFAULT '0',
  `fieldData` text default null,
  PRIMARY KEY (`id`),
  INDEX `tickid_index` (`ticketID`),
  INDEX `fldid_index` (`fieldID`),
  INDEX `repid_index` (`replyID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_tickethistory
DROP TABLE IF EXISTS `msp_tickethistory`;
CREATE TABLE IF NOT EXISTS `msp_tickethistory` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ts` int(30) NOT NULL DEFAULT '0',
  `ticketID` int(11) NOT NULL DEFAULT '0',
  `action` text default null,
  PRIMARY KEY (`id`),
  INDEX `ticket_index` (`ticketID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_tickets
DROP TABLE IF EXISTS `msp_tickets`;
CREATE TABLE IF NOT EXISTS `msp_tickets` (
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
  INDEX `vis_index` (`visitorID`),
  INDEX `pry_index` (`priority`),
  INDEX `isdis_index` (`isDisputed`),
  INDEX `ts_index` (`ts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_userdepts
DROP TABLE IF EXISTS `msp_userdepts`;
CREATE TABLE IF NOT EXISTS `msp_userdepts` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `userID` int(5) NOT NULL DEFAULT '0',
  `deptID` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `userid_index` (`userID`),
  INDEX `depid_index` (`deptID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table msp_users
DROP TABLE IF EXISTS `msp_users`;
CREATE TABLE IF NOT EXISTS `msp_users` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table msp_users: 1 rows
INSERT INTO `msp_users` (`id`, `ts`, `name`, `email`, `email2`, `accpass`, `signature`, `notify`, `pageAccess`, `emailSigs`, `notePadEnable`, `delPriv`, `nameFrom`, `emailFrom`, `assigned`, `timezone`, `enabled`, `notes`, `ticketHistory`, `enableLog`, `mailbox`, `mailFolders`, `mailDeletion`, `mailScreen`, `mailCopy`, `mailPurge`, `addpages`, `mergeperms`, `digest`, `digestasg`, `profile`, `helplink`, `defDays`) VALUES
(1, UNIX_TIMESTAMP(UTC_TIMESTAMP), 'admin', 'admin@example.com', NULL, '', '', 'yes', '', 'no', 'yes', 'yes', '', '', 'no', '0', 'yes', NULL, 'yes', 'yes', 'yes', 5, 'yes', 'yes', 'yes', 0, NULL, 'yes', 'yes', 'no', 'yes', 'yes', 45);

-- Dumping structure for table msp_usersaccess
DROP TABLE IF EXISTS `msp_usersaccess`;
CREATE TABLE IF NOT EXISTS `msp_usersaccess` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `page` varchar(100) NOT NULL DEFAULT '',
  `userID` varchar(250) NOT NULL DEFAULT '',
  `type` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX `user_index` (`userID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;