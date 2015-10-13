<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: upgrade-routine.php
  Description: Upgrade File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

$upgradeOperations = true;

// Settings..
$SETTINGS = @mysql_fetch_object(
              mysql_query("SELECT * FROM `".DB_PREFIX."settings` LIMIT 1")
             );

$ops[] = 'Add New MySQL Tables';
$ops[] = 'Update Imap Settings';
$ops[] = 'Update Settings';
$ops[] = 'Update Tickets';
$ops[] = 'Update User Data';
$ops[] = 'Update Other Data';
$ops[] = 'Additional Updates and Finish';

//----------------------------------
// Perform actions via ajax..
//----------------------------------

if (isset($_GET['action'])) {
  switch ($_GET['action']) {
    //--------------------------------
    // Add New MySQL Tables
    //--------------------------------
    case 'start':
	
	mswUpgradeLog('Upgrade routine started');
	
    // imap table..
    if (mswCheckTable('imap')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."imap` (
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
	  mswUpgradeLog('`'.DB_PREFIX.'imap` table created');
    }
    // cusfields table..
    if (mswCheckTable('cusfields')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."cusfields` (
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
	  mswUpgradeLog('`'.DB_PREFIX.'cusfields` table created');
    }
    // ticketfields..
    if (mswCheckTable('ticketfields')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."ticketfields` (
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
	  mswUpgradeLog('`'.DB_PREFIX.'ticketfields` table created');
    }
    // disputes..
    if (mswCheckTable('disputes')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."disputes` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `ticketID` int(15) NOT NULL DEFAULT '0',
      `visitorID` int(8) NOT NULL DEFAULT '0',
      `postPrivileges` enum('yes','no') NOT NULL DEFAULT 'yes',
      PRIMARY KEY (`id`),
      INDEX `tickid_index` (`ticketID`)
      ) $tableType");
	  mswUpgradeLog('`'.DB_PREFIX.'disputes` table created');
    }
    // faqassign..
    if (mswCheckTable('faqassign')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."faqassign` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `question` int(7) NOT NULL DEFAULT '0',
      `itemID` int(7) NOT NULL DEFAULT '0',
      `desc` varchar(20) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`),
      INDEX `que_index` (`question`),
      INDEX `att_index` (`itemID`)
      ) $tableType");
	  mswUpgradeLog('`'.DB_PREFIX.'faqassign` table created');
    }
	// faqattach..
    if (mswCheckTable('faqattach')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."faqattach` (
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
	  mswUpgradeLog('`'.DB_PREFIX.'faqattach` table created');
    }
    // levels..
    if (mswCheckTable('levels')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."levels` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL DEFAULT '',
      `display` enum('yes','no') NOT NULL DEFAULT 'no',
      `marker` varchar(100) NOT NULL DEFAULT '',
      `orderBy` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
      ) $tableType");
	  mswUpgradeLog('`'.DB_PREFIX.'levels` table created');
      // Add defaults..
      @mysql_query("INSERT INTO `".DB_PREFIX."levels` VALUES (1, 'Low', 'yes', 'low', 1)");
      @mysql_query("INSERT INTO `".DB_PREFIX."levels` VALUES (2, 'Medium', 'yes', 'medium', 2)");
      @mysql_query("INSERT INTO `".DB_PREFIX."levels` VALUES (3, 'High', 'yes', 'high', 3)");
	  mswUpgradeLog('`'.DB_PREFIX.'levels` default entries created');
      // Now convert any new levels..
      if (file_exists(REL_PATH.'control/priority-levels.php')) {
        include(REL_PATH.'control/priority-levels.php');
        $morelevels = 3;
        if (!empty($priorityLevels)) {
          foreach ($priorityLevels AS $k => $v) {
            @mysql_query("INSERT INTO `".DB_PREFIX."levels` (
            `name`, `display`, `marker`, `orderBy`
            ) VALUES (
            '".mswSafeImportString($v)."', 'yes', '$k', '".(++$morelevels)."'
            )");
          }
		  mswUpgradeLog('`'.DB_PREFIX.'levels` additional entries created');
        }
      }
    }
	// ban..
	if (mswCheckTable('ban')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."ban` (
	  `id` int(5) NOT NULL AUTO_INCREMENT,
      `type` varchar(100) NOT NULL DEFAULT '',
      `ip` varchar(250) NOT NULL DEFAULT '',
      `count` int(5) NOT NULL DEFAULT '0',
      `banstamp` varchar(250) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
      ) $tableType");
	  mswUpgradeLog('`'.DB_PREFIX.'ban` table created');
    }
	// imap_b8..
	if (mswCheckTable('imap_b8')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."imap_b8` (
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
	  @mysql_query("insert into `".DB_PREFIX."imap_b8` (`id`, `tokens`, `min_size`, `max_size`, `min_dev`, `x_constant`, 
	  `s_constant`, `learning`, `num_parse`, `uri_parse`, `html_parse`, `multibyte`, `encoder`
	  ) VALUES (
	  1, 15, 3, 30, '0.5', '0.5', '0.3', 'yes', 'no', 'yes', 'yes', 'no', 'UTF-8')");
	  mswUpgradeLog('`'.DB_PREFIX.'imap_b8` table created and data inserted');
    }
	// imap_b8_filter..
	if (mswCheckTable('imap_b8_filter')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."imap_b8_filter` (
	  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
      `count_ham` int(10) unsigned NOT NULL DEFAULT '0',
      `count_spam` int(10) unsigned NOT NULL DEFAULT '0',
      `ts` int(30) NOT NULL DEFAULT '0',
      PRIMARY KEY (`token`)
      ) $tableType");
	  @mysql_query("insert into `".DB_PREFIX."imap_b8_filter` (`token`, `count_ham`) values ('b8*dbversion', '3')");
      @mysql_query("insert into `".DB_PREFIX."imap_b8_filter` (`token`, `count_ham`, `count_spam`) values ('b8*texts', '0', '0')");
      mswUpgradeLog('`'.DB_PREFIX.'imap_b8_filter` table created and data inserted');
    }
	// mailassoc..
	if (mswCheckTable('mailassoc')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."mailassoc` (
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
	  mswUpgradeLog('`'.DB_PREFIX.'mailassoc` table created');
    }
	// mailbox..
	if (mswCheckTable('mailbox')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."mailbox` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `ts` int(30) NOT NULL DEFAULT '0',
      `staffID` int(8) NOT NULL DEFAULT '0',
      `subject` varchar(250) NOT NULL DEFAULT '',
      `message` text default null,
      PRIMARY KEY (`id`),
      INDEX `staff_index` (`staffID`)
      ) $tableType");
	  mswUpgradeLog('`'.DB_PREFIX.'mailbox` table created');
    }
	// mailfolders..
	if (mswCheckTable('mailfolders')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."mailfolders` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `staffID` int(8) NOT NULL DEFAULT '0',
      `folder` varchar(50) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`),
      INDEX `staff_index` (`staffID`)
      ) $tableType");
	  mswUpgradeLog('`'.DB_PREFIX.'mailfolders` table created');
    }
	// mailreplies..
	if (mswCheckTable('mailreplies')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."mailreplies` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `ts` int(30) NOT NULL DEFAULT '0',
      `mailID` int(8) NOT NULL DEFAULT '0',
      `staffID` int(8) NOT NULL DEFAULT '0',
      `message` text default null,
      PRIMARY KEY (`id`),
      INDEX `mail_index` (`mailID`),
      INDEX `staff_index` (`staffID`)
      ) $tableType");
	  mswUpgradeLog('`'.DB_PREFIX.'mailreplies` table created');
    }
	// tickethistory..
	if (mswCheckTable('tickethistory')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."tickethistory` (
	  `id` int(5) NOT NULL AUTO_INCREMENT,
      `ts` int(30) NOT NULL DEFAULT '0',
      `ticketID` int(11) NOT NULL DEFAULT '0',
      `action` text default null,
      PRIMARY KEY (`id`),
      INDEX `ticket_index` (`ticketID`)
      ) $tableType");
	  mswUpgradeLog('`'.DB_PREFIX.'tickethistory` table created');
    }
	// usersaccess..
	if (mswCheckTable('usersaccess')=='no') {
      @mysql_query("CREATE TABLE `".DB_PREFIX."usersaccess` (
	  `id` int(5) NOT NULL AUTO_INCREMENT,
      `page` varchar(100) NOT NULL DEFAULT '',
      `userID` varchar(250) NOT NULL DEFAULT '',
      `type` varchar(32) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`),
      INDEX `user_index` (`userID`)
      ) $tableType");
	  mswUpgradeLog('`'.DB_PREFIX.'usersaccess` table created');
    }
    // Continue..
    if (count($ops)==1) {
      echo 'done';
      exit;
    }
    echo '1';
    break;
    case $_GET['action']:
    sleep(3);
    switch ($_GET['action']) {
      
	  //--------------------------------
      // Upgrade Imap Settings
      //--------------------------------
      
	  case '1':
      
	  mswUpgradeLog('Beginning imap updates..');
	  
	  if (isset($SETTINGS->im_piping) && $SETTINGS->im_protocol=='imap' && ($SETTINGS->im_piping=='yes' || $SETTINGS->im_host)) {
        @mysql_query("INSERT INTO `".DB_PREFIX."imap` (
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
        `im_email`
        ) VALUES (
        '{$SETTINGS->im_piping}',
        '{$SETTINGS->im_protocol}',
        '{$SETTINGS->im_host}',
        '{$SETTINGS->im_user}',
        '{$SETTINGS->im_pass}',
        '{$SETTINGS->im_port}',
        '{$SETTINGS->im_name}',
        '{$SETTINGS->im_flags}',
        '{$SETTINGS->im_attach}',
        '',
        '{$SETTINGS->im_messages}',
        '{$SETTINGS->im_ssl}',
        '{$SETTINGS->im_priority}',
        '{$SETTINGS->im_dept}',
        '{$SETTINGS->im_email}'
        )");
        @mysql_query("alter table `".DB_PREFIX."settings` drop `im_piping`,
		drop `im_protocol`, drop `im_host`, drop `im_user`, drop `im_pass`,
		drop `im_port`, drop `im_name`, drop `im_flags`, drop `im_attach`, 
		drop `im_delete`, drop `im_messages`, drop `im_ssl`,drop `im_priority`,
		drop `im_dept`, drop `im_email`");
		mswUpgradeLog('Imap data converted from older versions. Single entry added to new imap table.');
      }
	  
	  @mysql_query("alter table `".DB_PREFIX."imap` add column `im_spam` enum('yes','no') not null default 'no'");
      @mysql_query("alter table `".DB_PREFIX."imap` add column `im_spam_purge` enum('yes','no') not null default 'no'");
      @mysql_query("alter table `".DB_PREFIX."imap` add column `im_score` varchar(10) not null default '1.0'");
	  @mysql_query("update `".DB_PREFIX."imap` set `im_protocol` = 'imap', `im_piping` = 'no' where `im_protocol` = 'pop3'");
	  
      mswUpgradeLog('Imap updates completed');

      break;
      
	  //--------------------------------
      // Update Settings
      //--------------------------------
      
	  case '2':
	  
	  mswUpgradeLog('Beginning settings updates < v3.0');
      
	  if (!isset($SETTINGS->softwareVersion)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `softwareVersion` varchar(10) not null default '".SCRIPT_VERSION."'");
	  }
      if (!isset($SETTINGS->enableBBCode)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` change `autolinks` `enableBBCode` enum('yes','no') not null default 'yes'");
	  }
      if (!isset($SETTINGS->apiKey)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `apiKey` varchar(100) not null default ''");
      }
	  if (!isset($SETTINGS->enCapLogin)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `enCapLogin` enum('yes','no') not null default 'yes'");
      }
	  if (!isset($SETTINGS->quePerPage)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `quePerPage` int(3) not null default '10' after `popquestions`");
      }
	  if (isset($SETTINGS->enSpamSum)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` drop `enSpamSum`");
      }
	  if (!isset($SETTINGS->recaptchaPublicKey)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `recaptchaPublicKey` varchar(250) not null default ''");
      }
	  if (!isset($SETTINGS->recaptchaPrivateKey)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `recaptchaPrivateKey` varchar(250) not null default ''");
      }
	  if (!isset($SETTINGS->weekStart)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `weekStart` enum('mon','sun') not null default 'sun' after `dateformat`");
      }
	  if (!isset($SETTINGS->jsDateFormat)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `jsDateFormat` varchar(15) not null default 'DD/MM/YYYY' after `weekstart`");
	  }
	  if (!isset($SETTINGS->sysstatus)) {
        @mysql_query("alter table `".DB_PREFIX."settings` add column `sysstatus` enum('yes','no') not null default 'yes'");
      }
	  if (!isset($SETTINGS->autoenable)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `autoenable` date not null default '0000-00-00'");
      }
	  if (!isset($SETTINGS->autoCloseMail)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `autoCloseMail` enum('yes','no') not null default 'yes' after `autoClose`");
      }
	  if (!isset($SETTINGS->timeformat)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `timeformat` varchar(15) not null default 'H:iA' after `dateformat`");
		@mysql_query("update `".DB_PREFIX."settings` set `dateformat` = 'd M Y', `timeformat` = 'H:iA'");
      }
	  if (!isset($SETTINGS->timezone)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` change `timeoffset` `timezone` varchar(50) not null default 'Europe/London' after `timeformat`");
      }
	  if (!isset($SETTINGS->rename)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `rename` enum('yes','no') not null default 'no' after `attachment`");
      }
	  if (isset($SETTINGS->mysqldate)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` drop column `mysqldate`");
      }
	  // Older language versions adjustment..
	  if (substr($SETTINGS->language,-4)=='.php') {
        @mysql_query("update `".DB_PREFIX."settings` set `language` = 'english'");
	  }
	  if (!isset($SETTINGS->rename)) {
        @mysql_query("update `".DB_PREFIX."settings` set `rename` = 'no'");
      }
      if (isset($SETTINGS->timeOffset)) {
        $diff = substr($SETTINGS->timeOffset,0,-6);
		if (isset($timezones_php4)) {
          $flip = array_flip($timezones_php4);
          @mysql_query("update `".DB_PREFIX."settings` set `timezone` = '".(isset($flip[$diff]) ? $flip[$diff] : 'Europe/London')."'");
		} else {
		  @mysql_query("update `".DB_PREFIX."settings` set `timezone` = 'Europe/London'");
		}
      }
	  
      // v3.0 Changes..
	  mswUpgradeLog('< v3.0 updates completed...Starting settings updates for v3.0+');
	  
	  // HTTP Paths..
      $hdeskPath    = 'http://www.example.com/helpdesk';
      if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['PHP_SELF'])) {
        $hdeskPath   = 'http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],'install')-1);
      }  
      $hdeskPathAtt = $hdeskPath.'/content/attachments';
      $hdeskPathFaq = $hdeskPath.'/content/attachments-faq';
      // Server Paths..
      $attFaqPath   = mswSafeImportString(substr(PATH,0,strpos(PATH,'install')-1).'/content/attachments-faq');
	  
	  if (!isset($SETTINGS->disputes)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `disputes` enum('yes','no') not null default 'no'");
		if (mswRowCount('tickets WHERE `isDisputed` = \'yes\'')>0) {
		  @mysql_query("update `".DB_PREFIX."settings` set `disputes` = 'yes'");
		}
      }
	  if (isset($SETTINGS->smtp)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` drop column `smtp`");
      }
	  if (!isset($SETTINGS->smtp_security)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `smtp_security` varchar(10) not null default '' after `smtp_port`");
      }
	  if (!isset($SETTINGS->smtp_debug)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `smtp_debug` enum('yes','no') not null default 'no' after `smtp_security`");
      }
	  if (!isset($SETTINGS->offlineReason)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `offlineReason` text default null");
      }
	  if (!isset($SETTINGS->createPref)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `createPref` enum('yes','no') not null default 'yes'");
      }
	  if (!isset($SETTINGS->createAcc)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `createAcc` enum('yes','no') not null default 'yes'");
      }
	  if (!isset($SETTINGS->attachhref)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `attachhref` varchar(250) not null default '' after `attachpath`");
		@mysql_query("update `".DB_PREFIX."settings` set `attachhref` = '{$hdeskPathAtt}'");
      }
	  if (mswCheckColumnType('settings','maxsize',15)=='no') {
	    @mysql_query("alter table `".DB_PREFIX."settings` change column `maxsize` `maxsize` int(15) not null default '1048576' after `filetypes`");
      }
	  if (!isset($SETTINGS->attachpathfaq)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `attachpathfaq` varchar(250) not null default '' after `attachhref`");
		@mysql_query("update `".DB_PREFIX."settings` set `attachpathfaq` = '{$attFaqPath}'");
      }
	  if (!isset($SETTINGS->attachhreffaq)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `attachhreffaq` varchar(250) not null default '' after `attachpathfaq`");
		@mysql_query("update `".DB_PREFIX."settings` set `attachhreffaq` = '{$hdeskPathFaq}'");
      }
	  if (!isset($SETTINGS->renamefaq)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `renamefaq` enum('yes','no') not null default 'no' after `cookiedays`");
      }
	  if (!isset($SETTINGS->loginLimit)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `loginLimit` int(5) not null default '0'");
      }
	  if (!isset($SETTINGS->banTime)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `banTime` int(5) not null default '0'");
      }
	  if (!isset($SETTINGS->ticketHistory)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `ticketHistory` enum('yes','no') not null default 'yes'");
      }
	  if (!isset($SETTINGS->backupEmails)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `backupEmails` text default null");
      }
	  if (!isset($SETTINGS->closenotify)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `closenotify` enum('yes','no') not null default 'no'");
      }
	  if (!isset($SETTINGS->replyto)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `replyto` varchar(250) not null default '' after `email`");
      }
	  if (!isset($SETTINGS->langSets)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `langSets` text default null after language");
      }
	  if (!isset($SETTINGS->minPassValue)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `minPassValue` int(3) not null default '8'");
      }
	  if (!isset($SETTINGS->accProfNotify)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `accProfNotify` enum('yes','no') not null default 'yes'");
      }
	  if (!isset($SETTINGS->newAccNotify)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `newAccNotify` enum('yes','no') not null default 'yes'");
      }
	  if (!isset($SETTINGS->recaptchaTheme)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `recaptchaTheme` varchar(20) not null default 'white'");
      }
	  if (!isset($SETTINGS->recaptchaLang)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `recaptchaLang` char(2) not null default 'en'");
      }
	  if (!isset($SETTINGS->enableLog)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `enableLog` enum('yes','no') not null default 'yes'");
      }
	  if (!isset($SETTINGS->defKeepLogs)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `defKeepLogs` varchar(100) not null default ''");
      }
	  if (!isset($SETTINGS->minTickDigits)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `minTickDigits` int(2) not null default '5'");
      }
	  if (!isset($SETTINGS->enableMail)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `enableMail` enum('yes','no') not null default 'yes'");
      }
	  if (!isset($SETTINGS->imap_debug)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `imap_debug` enum('yes','no') not null default 'no'");
      }
	  if (!isset($SETTINGS->imap_param)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `imap_param` varchar(10) not null default 'pipe'");
      }
	  if (!isset($SETTINGS->imap_memory)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `imap_memory` varchar(3) not null default '10'");
      }
	  if (!isset($SETTINGS->imap_timeout)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `imap_timeout` varchar(3) not null default '120'");
      }
	  if (!isset($SETTINGS->apiHandlers)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `apiHandlers` varchar(100) not null default 'xml' after `apiKey`");
      }
	  if (!isset($SETTINGS->apiLog)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `apiLog` enum('yes','no') not null default 'no' after `apiKey`");
      }
	  if (!isset($SETTINGS->disputeAdminStop)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` add column `disputeAdminStop` enum('yes','no') not null default 'no'");
	  }
	  if (isset($SETTINGS->portalpages)) {
	    @mysql_query("alter table `".DB_PREFIX."settings` drop column `portalpages`");
      }
	  if (mswCheckColumnType('settings','language',250)=='no') {
	    @mysql_query("alter table `".DB_PREFIX."settings` change `language` `language` varchar(250) not null default 'english' after `attachhreffaq`");
	  }
	  
	  mswUpgradeLog('v3.0+ updates completed for settings');
	  
	  break;
      
	  //--------------------------------
      // Update tickets/replies
      //--------------------------------
      
	  case '3':
	  
	  mswUpgradeLog('Beginning ticket updates < v3.0');
	  
      @mysql_query("alter table `".DB_PREFIX."tickets` add column `ticketNotes` text default null");
      @mysql_query("alter table `".DB_PREFIX."tickets` add column `isDisputed` enum('yes','no') not null default 'no'");
      @mysql_query("alter table `".DB_PREFIX."tickets` add column `disPostPriv` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."tickets` add column `addTime` time not null default '00:00:00' after `addDate`");
      @mysql_query("alter table `".DB_PREFIX."tickets` add column `tickLang` varchar(100) not null default 'english'");
	  
	  if (mswCheckColumnType('tickets','priority',250)=='no') {
        @mysql_query("alter table `".DB_PREFIX."tickets` change `priority` `priority` varchar(250) not null default ''");
	  }
      
	  @mysql_query("alter table `".DB_PREFIX."tickets` add column `assignedto` varchar(200) not null default '' after `department`");
      @mysql_query("alter table `".DB_PREFIX."tickets` add column `ts` int(30) not null default '0' after `id`");
      @mysql_query("alter table `".DB_PREFIX."tickets` add column `lastrevision` int(30) not null default '0' after `ts`");
      
      if (mswCheckIndex('tickets','email_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."tickets` add index `email_index` (`email`)");
      }
      
	  if (mswCheckIndex('tickets','depid_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."tickets` add index `depid_index` (`department`)");
      }
      
	  if (mswCheckIndex('tickets','pry_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."tickets` add index `pry_index` (`priority`)");
      }
      
	  if (mswCheckIndex('tickets','isdis_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."tickets` add index `isdis_index` (`isDisputed`)");
      }
      
      // Timestamps..
      if (mswCheckColumn('tickets','addTime')=='yes') {
        @mysql_query("update `".DB_PREFIX."tickets` set `ts` = UNIX_TIMESTAMP(CONCAT(addDate,' ',addTime))");
        @mysql_query("update `".DB_PREFIX."tickets` set `lastrevision` = UNIX_TIMESTAMP(CONCAT(lastUpdate,' 00:00:00'))");
        @mysql_query("alter table `".DB_PREFIX."tickets` drop column `ticketStamp`,drop column `addDate`,drop column `addTime`,drop column `lastUpdate`");
      }
      
      @mysql_query("alter table `".DB_PREFIX."replies` add column `addTime` time not null default '00:00:00' after `addDate`");
      @mysql_query("alter table `".DB_PREFIX."replies` add column `disputeUser` int(6) not null default '0'");
      @mysql_query("alter table `".DB_PREFIX."replies` add column `ts` int(30) not null default '0' after `id`");
      
      if (mswCheckIndex('replies','tickid_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."replies` add index `tickid_index` (`ticketID`)");
      }
      
	  if (mswCheckIndex('replies','repuse_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."replies` add index `repuse_index` (`replyUser`)");
      }
      
	  if (mswCheckIndex('replies','disuse_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."replies` add index `disuse_index` (`disputeUser`)");
      }
      
      // Timestamps..
      if (mswCheckColumn('replies','addTime')=='yes') {
        @mysql_query("update `".DB_PREFIX."replies` set `ts` = UNIX_TIMESTAMP(CONCAT(addDate,' ',addTime))");
        @mysql_query("alter table `".DB_PREFIX."replies` drop column `replyStamp`,drop column `addDate`,drop column `addTime`");
      }
	  
	  mswUpgradeLog('< v3.0 updates completed...Starting ticket updates for v3.0+');
	  
	  @mysql_query("alter table `".DB_PREFIX."tickets` add column `visitorID` int(8) not null default '0' after `assignedto`");
      
	  if (mswCheckColumn('tickets','name')=='yes') {
	    @mysql_query("alter table `".DB_PREFIX."portal` add column `name` varchar(200) not null default '' after `id`");
	    @mysql_query("update `".DB_PREFIX."tickets`,`".DB_PREFIX."portal` set 
	    `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`,
	    `".DB_PREFIX."portal`.`name` = `".DB_PREFIX."tickets`.`name`
	    where `".DB_PREFIX."portal`.`email` = `".DB_PREFIX."tickets`.`email`
	    ");
        @mysql_query("alter table `".DB_PREFIX."tickets` drop column `name`, drop column `email`, drop column `tickLang`");
	  }
	  
      @mysql_query("alter table `".DB_PREFIX."tickets` add column `source` varchar(10) not null default 'standard'");
      @mysql_query("alter table `".DB_PREFIX."tickets` add column `spamFlag` enum('yes','no') not null default 'no'");
	  
	  if (mswCheckIndex('tickets','email_index')=='yes') {
	    @mysql_query("alter table `".DB_PREFIX."tickets` drop index `email_index`");
	  }
	  
	  if (mswCheckIndex('tickets','ts_index')=='no') {
	    @mysql_query("alter table `".DB_PREFIX."tickets` add index `ts_index` (`ts`)");
	  }
	  
	  if (mswCheckIndex('tickets','vis_index')=='no') {
	    @mysql_query("alter table `".DB_PREFIX."tickets` add index `vis_index` (`visitorID`)");
	  }
	  
	  if (mswCheckColumnType('tickets','ipAddresses','text')=='no') {
	    @mysql_query("alter table `".DB_PREFIX."replies` change column `ipAddresses` `ipAddresses` text default null after `ticketStatus`");
	  }
	  
	  if (mswCheckIndex('ticketfields','tickid_index')=='no') {
	    @mysql_query("alter table `".DB_PREFIX."ticketfields` add index `tickid_index` (`ticketID`)");
	  }
	  
	  if (mswCheckIndex('ticketfields','fldid_index')=='no') {
	    @mysql_query("alter table `".DB_PREFIX."ticketfields` add index `fldid_index` (`fieldID`)");
	  }
	  
	  if (mswCheckIndex('ticketfields','repid_index')=='no') {
	    @mysql_query("alter table `".DB_PREFIX."ticketfields` add index `repid_index` (`replyID`)");
	  }
	  
	  if (mswRowCount('imap')>0) {
	    @mysql_query("update `".DB_PREFIX."tickets` set `source` = 'imap' where locate('.',`ipaddresses`) = 0 and `source` = 'standard'");
	  }
      
	  if (mswCheckColumnType('replies','ipAddresses','text')=='no') {
	    @mysql_query("alter table `".DB_PREFIX."replies` change column `ipAddresses` `ipAddresses` text default null after `isMerged`");
	  }
	  
	  @mysql_query("alter table `".DB_PREFIX."disputes` add column `visitorID` int(8) not null default '0' after `ticketID`");
	  
	  if (mswCheckColumn('disputes','userName')=='yes') {
        @mysql_query("update `".DB_PREFIX."disputes`,`".DB_PREFIX."portal` set 
	    `".DB_PREFIX."disputes`.`visitorID` = `".DB_PREFIX."portal`.`id` 
	    WHERE `".DB_PREFIX."disputes`.`userEmail` = `".DB_PREFIX."portal`.`email`
	    ");
        @mysql_query("alter table `".DB_PREFIX."disputes` drop column `userName`, drop column `userEmail`");
	  }
	  
	  if (mswCheckIndex('disputes','tickid_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."disputes` add index `tickid_index` (`ticketID`)");
      }
	  
	  if (mswCheckIndex('disputes','vis_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."disputes` add index `vis_index` (`visitorID`)");
      }
	  
	  mswUpgradeLog('Ticket updates completed for v3.0+');
      
      break;
      
	  //--------------------------------
      // Update User Data
      //--------------------------------
      
	  case '4':
	  
	  mswUpgradeLog('Beginning user/visitor updates < v3.0');
	  
      @mysql_query("alter table `".DB_PREFIX."users` add column `emailSigs` enum('yes','no') not null default 'no'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `notePadEnable` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `delPriv` enum('yes','no') not null default 'no'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `nameFrom` varchar(250) not null default ''");
      @mysql_query("alter table `".DB_PREFIX."users` add column `emailFrom` varchar(250) not null default ''");
      @mysql_query("update `".DB_PREFIX."users` set `pageAccess` = replace(`pageAccess`,'kbase','faq')");
      @mysql_query("update `".DB_PREFIX."users` set `pageAccess` = replace(`pageAccess`,'kbase-cat','faq-cat')");
      @mysql_query("alter table `".DB_PREFIX."users` add column `ts` int(30) not null default '0' after `id`");
      @mysql_query("alter table `".DB_PREFIX."users` add column `assigned` enum('yes','no') not null default 'no'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `timezone` varchar(50) not null default 'Europe/London'");
      
      if (mswCheckIndex('users','email_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."users` add index `email_index` (`email`)");
      }
      
	  if (mswCheckIndex('users','nty_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."users` add index `nty_index` (`notify`)");
      }
      
      if (mswCheckColumn('users','addDate')=='yes') {
        @mysql_query("update `".DB_PREFIX."users` set `ts` = UNIX_TIMESTAMP(CONCAT(addDate,' 00:00:00'))");
        @mysql_query("alter table `".DB_PREFIX."users` drop column `addDate`");
      }
      
      @mysql_query("alter table `".DB_PREFIX."portal` add column `enabled` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."portal` add column `timezone` varchar(50) not null default 'Europe/London'");
      @mysql_query("alter table `".DB_PREFIX."portal` add column `ts` int(30) not null default '0' after `id`");
      
      if (mswCheckColumn('portal','addDate')=='yes') {
        @mysql_query("update `".DB_PREFIX."portal` set `ts` = UNIX_TIMESTAMP(CONCAT(addDate,' 00:00:00'))");
        @mysql_query("alter table `".DB_PREFIX."portal` drop column `addDate`");
      }
      
      if (mswCheckIndex('userdepts','userid_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."userdepts` add index `userid_index` (`userID`)");
      }
      
	  if (mswCheckIndex('userdepts','depid_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."userdepts` add index `depid_index` (`deptID`)");
      }
	  
	  mswUpgradeLog('< v3.0 updates completed...Starting user/visitor updates for v3.0+');
	  
	  @mysql_query("alter table `".DB_PREFIX."users` add column `enabled` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `notes` text default null");
      @mysql_query("alter table `".DB_PREFIX."users` add column `email2` text default null after `email`");
      @mysql_query("alter table `".DB_PREFIX."users` add column `ticketHistory` enum('yes','no') not null default 'yes'");
	  
	  if (mswCheckColumnType('users','pageAccess','text')=='no') {
        @mysql_query("alter table `".DB_PREFIX."users` change `pageAccess` `pageAccess` text default null");
	  }
      
	  @mysql_query("alter table `".DB_PREFIX."users` add column `enableLog` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `mailbox` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `mailFolders` int(3) not null default '5'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `mailDeletion` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `mailScreen` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `mailCopy` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `mailPurge` int(3) not null default '0'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `addpages` text default null");
      @mysql_query("alter table `".DB_PREFIX."users` add column `mergeperms` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `digest` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `digestasg` enum('yes','no') not null default 'no'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `profile` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."users` add column `helplink` enum('yes','no') not null default 'no'");
	  @mysql_query("alter table `".DB_PREFIX."users` add column `defDays` int(3) not null default '45'");
	  
	  @mysql_query("update `".DB_PREFIX."users` set `timezone` = 'Europe/London' where `timezone` = '0'");
	  @mysql_query("update `".DB_PREFIX."users` set `assigned` = 'yes',`helplink` = 'yes' where `id` = '1'");
	  
	  $q = mysql_query("select `id`,`pageAccess` from `".DB_PREFIX."users` where `id` > 1 order by `id`");
	  while ($U = mysql_fetch_object($q)) {
	    $pa = explode('|',$U->pageAccess);
	    if (!empty($pa)) {
		  foreach ($pa AS $uap) {
		    @mysql_query("insert into `".DB_PREFIX."usersaccess` (
		    `page`,`userID`,`type`
		    ) values (
		    '{$uap}','{$U->id}','pages'
		    )");
		  }
		}
	  }
	  
	  @mysql_query("alter table `".DB_PREFIX."portal` add column `ip` varchar(200) not null default ''");
      @mysql_query("alter table `".DB_PREFIX."portal` add column `notes` text default null");
	  @mysql_query("alter table `".DB_PREFIX."portal` add column `reason` text default null");
      @mysql_query("alter table `".DB_PREFIX."portal` add column `verified` enum('yes','no') not null default 'no' after `enabled`");
      @mysql_query("alter table `".DB_PREFIX."portal` add column `system1` varchar(250) not null default ''");
      @mysql_query("alter table `".DB_PREFIX."portal` add column `system2` varchar(250) not null default ''");
      @mysql_query("alter table `".DB_PREFIX."portal` add column `language` varchar(100) not null default 'english'");
      @mysql_query("alter table `".DB_PREFIX."portal` add column `enableLog` enum('yes','no') not null default 'yes'");
	  
	  if (mswCheckColumnType('portal','ip','text')=='no') {
        @mysql_query("alter table `".DB_PREFIX."portal` change column `ip` `ip` text default null after `timezone`");
	  }
	  
	  @mysql_query("update `".DB_PREFIX."portal` set `verified` = 'yes' where `enabled` = 'yes' and date(from_unixtime(`ts`)) < '2014-01-01'");
	  @mysql_query("update `".DB_PREFIX."portal` set `timezone` = 'Europe/London' where `timezone` = '0'");
	  
	  if (mswCheckIndex('portal','nme_index')=='no') {
	    @mysql_query("alter table `".DB_PREFIX."portal` add index `nme_index` (`name`)");
	  }
	  
	  if (mswCheckIndex('portal','em_index')=='no') {
	    @mysql_query("alter table `".DB_PREFIX."portal` add index `em_index` (`email`)");
	  }
	  
	  mswUpgradeLog('User/visitor updates done for v3.0+');
	  
      break;
      
	  //--------------------------------
      // Update Other Data
      //--------------------------------
      
	  case '5':
	  
	  mswUpgradeLog('Beginning attachments updates < v3.0');
	  
	  if (mswCheckColumnType('attachments','fileName',250)=='no') {
        @mysql_query("alter table `".DB_PREFIX."attachments` change `fileName` `fileName` varchar(250) not null default ''");
      }
	  
	  @mysql_query("alter table `".DB_PREFIX."attachments` add column `ts` int(30) not null default '0' after `id`");
      
      if (mswCheckIndex('attachments','tickid_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."attachments` add index `tickid_index` (`ticketID`)");
      }
      
	  if (mswCheckIndex('attachments','repid_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."attachments` add index `repid_index` (`replyID`)");
      }
      
      if (mswCheckColumn('attachments','addDate')=='yes') {
        @mysql_query("update `".DB_PREFIX."attachments` set `ts` = UNIX_TIMESTAMP(CONCAT(addDate,' 00:00:00'))");
        @mysql_query("alter table `".DB_PREFIX."attachments` drop column `addDate`");
      }
	  
	  mswUpgradeLog('< v3.0 updates completed...Starting attachment updates for v3.0+');
      
	  @mysql_query("alter table `".DB_PREFIX."attachments` add column `mimeType` varchar(100) not null default ''");
	  
	  mswUpgradeLog('Beginning department updates < v3.0');
	  
      @mysql_query("alter table `".DB_PREFIX."departments` add column `showDept` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."departments` add column `dept_subject` text default null");
      @mysql_query("alter table `".DB_PREFIX."departments` add column `dept_comments` text default null");
      @mysql_query("alter table `".DB_PREFIX."departments` add column `orderBy` int(5) not null default '0'");
      @mysql_query("alter table `".DB_PREFIX."departments` add column `manual_assign` enum('yes','no') not null default 'no'");
      
	  mswUpgradeLog('Beginning category updates < v3.0');
	  
      @mysql_query("alter table `".DB_PREFIX."categories` add column `enCat` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."categories` add column `orderBy` int(5) not null default '0'");
      @mysql_query("alter table `".DB_PREFIX."categories` add column `subcat` int(5) not null default '0'");
	  
	  mswUpgradeLog('Category updates completed < v3.0');
	  
	  mswUpgradeLog('Beginning custom field updates < v3.0');
	  
	  if (mswCheckColumn('responses','addDate')=='yes') {
        @mysql_query("alter table `".DB_PREFIX."cusfields` add column `departments` text default null");
        @mysql_query("update `".DB_PREFIX."cusfields` set `departments` = 'all'");
      }
	  
	  mswUpgradeLog('Beginning custom field updates v3.0+');
	  
	  $allDepts = array();
	  $q        = mysql_query("select `id` from `".DB_PREFIX."departments` order by `id`");
	  while ($D = mysql_fetch_object($q)) {
	    $allDepts[] = $D->id;
	  }
	  if (!empty($allDepts)) {
	    @mysql_query("update `".DB_PREFIX."cusfields` set `departments` = '".implode(',',$allDepts)."' where `departments` in('0','','all')");
	  }
	  
	  mswUpgradeLog('Beginning F.A.Q updates < v3.0');
	  
	  @mysql_query("alter table `".DB_PREFIX."kbase` rename to `".DB_PREFIX."faq`");
      @mysql_query("alter table `".DB_PREFIX."faq` add column `enFaq` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."faq` add column `orderBy` int(5) not null default '0'");
	  
	  $faq_ob   = 0;
	  $q        = mysql_query("select `id`,`orderBy` from `".DB_PREFIX."faq` order by `id`");
	  while ($FQ = mysql_fetch_object($q)) {
	    if ($FQ->orderBy==0) {
		  ++$faq_ob;
		  @mysql_query("update `".DB_PREFIX."faq` set `orderBy` = '{$faq_ob}' where `id` = '{$FQ->id}'");
		}
	  }
      
      if (mswCheckIndex('faq','question')=='yes') {
        @mysql_query("alter table `".DB_PREFIX."faq` drop index `question`");
        @mysql_query("alter table `".DB_PREFIX."faq` drop index `question_2`");
        @mysql_query("alter table `".DB_PREFIX."faq` drop index `answer`");
      }
      
      @mysql_query("alter table `".DB_PREFIX."faq` add column `ts` int(30) not null default '0' after `id`");
      
      if (mswCheckColumn('faq','addDate')=='yes') {
        @mysql_query("update `".DB_PREFIX."faq` set `ts` = UNIX_TIMESTAMP(CONCAT(addDate,' 00:00:00'))");
        @mysql_query("alter table `".DB_PREFIX."faq` drop column `addDate`");
      }
      
      if (mswCheckIndex('faq','catid_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."faq` add index `catid_index` (`category`)");
      }
	  
	  mswUpgradeLog('Beginning F.A.Q updates v3.0+');
	  
	  if (mswCheckTable('faqattassign')=='yes') {
	    @mysql_query("alter table `".DB_PREFIX."faqattassign` rename to `".DB_PREFIX."faqassign`");
		@mysql_query("alter table `".DB_PREFIX."faqassign` change column `item` `itemID` int(7) not null default '0' after `question`");
        @mysql_query("alter table `".DB_PREFIX."faqassign` add column `desc` varchar(20) not null default ''");
	    @mysql_query("update `".DB_PREFIX."faqassign` set `desc` = 'attachment' where `desc` = ''");
		@mysql_query("drop table `".DB_PREFIX."faqattassign`");
	  }
	  
	  if (mswCheckColumn('faq','category')=='yes') {
	    $q = mysql_query("select `id`,`category` from `".DB_PREFIX."faq` order by `id`");
	    while ($F = mysql_fetch_object($q)) {
		  // All categories..
		  if (in_array($F->category,array('','0',0,'all'))) {
		    $q2 = mysql_query("select `id` from `".DB_PREFIX."categories` order by `id`");
	        while ($C = mysql_fetch_object($q2)) {
			  @mysql_query("insert into `".DB_PREFIX."faqassign` (
		      `question`,`itemID`,`desc`
		      ) values (
		      '{$F->id}','{$C->id}','category'
		      )");
			}
		  } else {
		    $pa = explode(',',$F->category);
	        if (!empty($pa)) {
		      foreach ($pa AS $uap) {
		        @mysql_query("insert into `".DB_PREFIX."faqassign` (
		        `question`,`itemID`,`desc`
		        ) values (
		        '{$F->id}','{$uap}','category'
		        )");
		      }
		    }
		  }
	    }
		@mysql_query("alter table `".DB_PREFIX."faq` drop `category`");
		if (mswCheckIndex('faq','catid_index')=='yes') {
	      @mysql_query("alter table `".DB_PREFIX."faq` drop index `catid_index`");
		}
	  }
	  
	  @mysql_query("alter table `".DB_PREFIX."faqattach` add column `orderBy` int(8) not null default '0'");
      @mysql_query("update `".DB_PREFIX."faqattach` set `orderBy` = `id`");
      @mysql_query("alter table `".DB_PREFIX."faqattach` add column `enAtt` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."faqattach` add column `mimeType` varchar(100) not null default ''");
      
	  if (mswCheckIndex('faqassign','att_index')=='yes') {
	    @mysql_query("alter table `".DB_PREFIX."faqassign` drop index `att_index`");
		@mysql_query("alter table `".DB_PREFIX."faqassign` add index `att_index` (`itemID`)");
	  }
	  
	  mswUpgradeLog('Beginning standard responses updates < v3.0');
      
      @mysql_query("alter table `".DB_PREFIX."responses` add column `enResponse` enum('yes','no') not null default 'yes'");
      @mysql_query("alter table `".DB_PREFIX."responses` add column `ts` int(30) not null default '0' after `id`");
      
      if (mswCheckColumn('responses','addDate')=='yes') {
        @mysql_query("update `".DB_PREFIX."responses` set `ts` = UNIX_TIMESTAMP(CONCAT(addDate,' 00:00:00'))");
        @mysql_query("alter table `".DB_PREFIX."responses` drop column `addDate`");
      }
	  
	  mswUpgradeLog('Beginning standard response updates v3.0+');
	  
	  @mysql_query("alter table `".DB_PREFIX."responses` add column `orderBy` int(8) not null default '0'");
      @mysql_query("update `".DB_PREFIX."responses` set `orderBy` = `id`");
      @mysql_query("alter table `".DB_PREFIX."responses` add column `departments` text default null");
      @mysql_query("update `".DB_PREFIX."responses` set `departments` = `department`");
      @mysql_query("alter table `".DB_PREFIX."responses` drop `department`");
	  
	  if (!empty($allDepts)) {
	    @mysql_query("update `".DB_PREFIX."responses` set `departments` = '".implode(',',$allDepts)."' where `departments` in('0','','all')");
	  }
	  
	  if (mswCheckIndex('responses','depid_index')=='yes') {
	    @mysql_query("alter table `".DB_PREFIX."responses` drop index `depid_index`");
	  }
	  
	  mswUpgradeLog('Other data upgrades completed');
      
      break;
      
	  //--------------------------------
      // Other Updates And Finish
      //--------------------------------
      
	  case '6':
	  
	  mswUpgradeLog('Beginning other updates < v3.0');
	  
	  if (mswCheckColumn('log','loginDateTime')=='yes') {
        @mysql_query("alter table `".DB_PREFIX."log` add column `ts` int(30) not null default '0' after `id`");
        @mysql_query("alter table `".DB_PREFIX."log` drop column `loginDateTime`");
        @mysql_query("truncate table `".DB_PREFIX."log`");
      }
	  
      if (mswCheckIndex('log','useid_index')=='no') {
        @mysql_query("alter table `".DB_PREFIX."log` add index `useid_index` (`userID`)");
      }
      
	  mswUpgradeLog('Beginning other updates v3.0+');
	  
	  @mysql_query("alter table `".DB_PREFIX."log` add column `ip` varchar(250) not null default ''");
      @mysql_query("alter table `".DB_PREFIX."log` add column `type` enum('user','acc') not null default 'user'");
	  
	  mswUpgradeLog('Other updates completed');
	  
      break;
    }
    if ($_GET['action']==count($ops)-1) {
      include(PATH.'control/version.php');
      echo 'done';
    } else {
      echo ($_GET['action']+1);
    }
    break;
  }
  exit;
}

?>
