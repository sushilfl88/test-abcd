<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: email-digest.php
  Description: Email Digest

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

//-------------------------
// Error Reporting
//-------------------------

include(dirname(__file__).'/control/classes/class.errors.php');
if (ERR_HANDLER_ENABLED) {
  set_error_handler('msErrorhandler');
}

//-------------------------------
// Paths, Vars, Files, Classes
//-------------------------------

define('PATH', dirname(__file__).'/');
define('PARENT',1);
define('EMAIL_DIGEST', 1);
define('CRON_RUN',1);

include(PATH.'control/system/core/init.php');
include(PATH.'control/mail-data.php');

$startTime  = $MSDT->mswDateTimeDisplay(0,$SETTINGS->dateformat).'/'.$MSDT->mswDateTimeDisplay(0,$SETTINGS->timeformat);

//-------------------------
// Run Auto Close First
//-------------------------

include(PATH.'close-tickets.php');

//-------------------------
// Loop staff members
//-------------------------

$qU = mysql_query("SELECT `id`,`name`,`email`,`assigned`,`digestasg`,`email2`,`timezone` FROM `".DB_PREFIX."users`
      WHERE `notify` = 'yes'
	  AND `digest`   = 'yes'
      ORDER BY `id`
      ");
while ($USERS = mysql_fetch_object($qU)) {

  //-------------------
  // Vars, Arrays
  //-------------------
  
  $emailDigest = array('','','','','','','','');
  $counts      = array(0,0,0,0,0,0,0,0);
  $dept        = array();

  //---------------------
  // User departments
  //---------------------
  
  if ($USERS->id!='1' && $USERS->assigned=='no') {
    $qUD = mysql_query("SELECT `deptID` FROM `".DB_PREFIX."userdepts` 
           WHERE `userID` = '{$USERS->id}'
           ");
    while ($UD = mysql_fetch_object($qUD)) {
      $dept[] = $UD->deptID;
    }
  }

  //----------------------------------
  // Tickets awaiting assignment
  // Sent Only to Admin = ID:1
  //----------------------------------
  
  if ($USERS->id=='1') {
    $q = mysql_query("SELECT `subject`,`priority`,`".DB_PREFIX."tickets`.`id` AS `ticketID`,
	     `".DB_PREFIX."portal`.`name` AS `ticketName`,
	     `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	     `".DB_PREFIX."departments`.`name` AS `deptName`,
	     `".DB_PREFIX."levels`.`name` AS `levelName`
	     FROM `".DB_PREFIX."tickets` 
         LEFT JOIN `".DB_PREFIX."departments`
	     ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	     LEFT JOIN `".DB_PREFIX."portal`
	     ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	     LEFT JOIN `".DB_PREFIX."levels`
	     ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	      OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
	     WHERE `assignedto` = 'waiting'
		 AND `spamFlag`     = 'no'
         ORDER BY FIELD(`".DB_PREFIX."tickets`.`priority`,'high','medium','low'),`levelName`
         ");
    if ($q && mysql_num_rows($q)>0) {
      while ($T = mysql_fetch_object($q)) {
        ++$counts[0];
        // Hyperlink..
	    $link  = mswDefineNewline();
        $link .= $SETTINGS->scriptpath.'/'.$SETTINGS->afolder.'/?ticket='.$T->ticketID;
        // Get last reply..
	    $last  = $MSTICKET->getLastReply($T->ticketID);
        $emailDigest[0] .= str_replace(
	     array('{priority}','{subject}','{ticket}'),
	     array(
	      strtoupper($MSYS->levels($T->priority)),
		  mswCleanData($T->subject),
		  mswTicketNumber($T->ticketID)
	     ),
	     $msg_edigest3
	    ).mswDefineNewline();
        $emailDigest[0] .= str_replace(
	     array('{name}','{updated}'),
	     array(
	      mswCleanData($T->ticketName).' ('.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->timeformat).')',
		  ($last[0]!='0' ? mswCleanData($last[0]).' ('.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->timeformat).')' : 'N/A')
          ),
		  $msg_edigest5
	     ).$link.mswDefineNewline().mswDefineNewline();
      }
    } else {
      $emailDigest[0] = $msg_edigest;
    }
  }
  
  //----------------------------------
  // Tickets flagged as spam
  // Sent Only to Admin = ID:1
  //----------------------------------
  
  if ($USERS->id=='1') {
    $q = mysql_query("SELECT `subject`,`priority`,`".DB_PREFIX."tickets`.`id` AS `ticketID`,
	     `".DB_PREFIX."portal`.`name` AS `ticketName`,
	     `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	     `".DB_PREFIX."departments`.`name` AS `deptName`,
	     `".DB_PREFIX."levels`.`name` AS `levelName`
	     FROM `".DB_PREFIX."tickets` 
         LEFT JOIN `".DB_PREFIX."departments`
	     ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	     LEFT JOIN `".DB_PREFIX."portal`
	     ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	     LEFT JOIN `".DB_PREFIX."levels`
	     ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	      OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
	     WHERE `spamFlag` = 'yes'
		 AND `source`     = 'imap'
         ORDER BY FIELD(`".DB_PREFIX."tickets`.`priority`,'high','medium','low'),`levelName`
         ");
    if ($q && mysql_num_rows($q)>0) {
      while ($T = mysql_fetch_object($q)) {
        ++$counts[7];
        // Hyperlink..
	    $link  = mswDefineNewline();
        $link .= $SETTINGS->scriptpath.'/'.$SETTINGS->afolder.'/?ticket='.$T->ticketID;
        // Get last reply..
	    $last  = $MSTICKET->getLastReply($T->ticketID);
        $emailDigest[7] .= str_replace(
	     array('{priority}','{subject}','{ticket}'),
	     array(
	      strtoupper($MSYS->levels($T->priority)),
		  mswCleanData($T->subject),
		  mswTicketNumber($T->ticketID)
	     ),
	     $msg_edigest3
	    ).mswDefineNewline();
        $emailDigest[7] .= str_replace(
	     array('{name}','{updated}'),
	     array(
	      mswCleanData($T->ticketName).' ('.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->timeformat).')',
		  ($last[0]!='0' ? mswCleanData($last[0]).' ('.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->timeformat).')' : 'N/A')
          ),
		  $msg_edigest5
	     ).$link.mswDefineNewline().mswDefineNewline();
      }
    } else {
      $emailDigest[7] = $msg_edigest;
    }
  }
  
  //------------------------------
  // New tickets, no replies
  //------------------------------
  
  $q = mysql_query("SELECT `subject`,`priority`,`".DB_PREFIX."tickets`.`id` AS `ticketID`,
	   `".DB_PREFIX."portal`.`name` AS `ticketName`,
	   `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	   `".DB_PREFIX."departments`.`name` AS `deptName`,
	   `".DB_PREFIX."levels`.`name` AS `levelName`
	   FROM `".DB_PREFIX."tickets` 
       LEFT JOIN `".DB_PREFIX."departments`
	   ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	   LEFT JOIN `".DB_PREFIX."portal`
	   ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	   LEFT JOIN `".DB_PREFIX."levels`
	   ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	    OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
	   WHERE `replyStatus` IN('start') 
       AND `ticketStatus`   = 'open' 
       AND `isDisputed`     = 'no'
       AND `assignedto`    != 'waiting'
	   AND `spamFlag`       = 'no'
       ".(!empty($dept) ? 'AND (`department` IN('.implode(',',$dept).') OR FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0)' : '')."
	   ".($USERS->id!='1' && empty($dept) && $USERS->assigned=='yes' ? 'AND FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0' : '')."
       ORDER BY FIELD(`".DB_PREFIX."tickets`.`priority`,'high','medium','low'),`levelName`
       ");
  if ($q && mysql_num_rows($q)>0) {
    while ($T = mysql_fetch_object($q)) {
      ++$counts[1];
      // Hyperlink..
	  $link  = mswDefineNewline();
      $link .= $SETTINGS->scriptpath.'/'.$SETTINGS->afolder.'/?ticket='.$T->ticketID;
      // Get last reply..
	  $last  = $MSTICKET->getLastReply($T->ticketID);
      $emailDigest[1] .= str_replace(
	   array('{priority}','{subject}','{ticket}'),
	   array(
	    strtoupper($MSYS->levels($T->priority)),
		mswCleanData($T->subject),
		mswTicketNumber($T->ticketID)
	   ),
	   $msg_edigest3
	  ).mswDefineNewline();
      $emailDigest[1] .= str_replace(
	   array('{name}','{updated}'),
	   array(
	    mswCleanData($T->ticketName).' ('.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->timeformat).')',
		($last[0]!='0' ? mswCleanData($last[0]).' ('.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->timeformat).')' : 'N/A')
        ),
		$msg_edigest5
	  ).$link.mswDefineNewline().mswDefineNewline();
    }
  } else {
    $emailDigest[1] = $msg_edigest;
  }
  
  //----------------------------------------
  // Tickets awaiting staff response
  //----------------------------------------
  
  $q = mysql_query("SELECT `subject`,`priority`,`".DB_PREFIX."tickets`.`id` AS `ticketID`,
	   `".DB_PREFIX."portal`.`name` AS `ticketName`,
	   `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	   `".DB_PREFIX."departments`.`name` AS `deptName`,
	   `".DB_PREFIX."levels`.`name` AS `levelName`
	   FROM `".DB_PREFIX."tickets` 
       LEFT JOIN `".DB_PREFIX."departments`
	   ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	   LEFT JOIN `".DB_PREFIX."portal`
	   ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	   LEFT JOIN `".DB_PREFIX."levels`
	   ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	    OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
	   WHERE `replyStatus` IN('admin') 
       AND `ticketStatus`   = 'open' 
       AND `isDisputed`     = 'no'
       AND `assignedto`    != 'waiting'
	   AND `spamFlag`       = 'no'
       ".(!empty($dept) ? 'AND (`department` IN('.implode(',',$dept).') OR FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0)' : '')."
	   ".($USERS->id!='1' && empty($dept) && $USERS->assigned=='yes' ? 'AND FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0' : '')."
       ORDER BY FIELD(`".DB_PREFIX."tickets`.`priority`,'high','medium','low'),`levelName`
       ");
  if ($q && mysql_num_rows($q)>0) {
    while ($T = mysql_fetch_object($q)) {
      ++$counts[2];
      // Hyperlink..
	  $link  = mswDefineNewline();
      $link .= $SETTINGS->scriptpath.'/'.$SETTINGS->afolder.'/?ticket='.$T->ticketID;
      // Get last reply..
	  $last  = $MSTICKET->getLastReply($T->ticketID);
      $emailDigest[2] .= str_replace(
	   array('{priority}','{subject}','{ticket}'),
	   array(
	    strtoupper($MSYS->levels($T->priority)),
		mswCleanData($T->subject),
		mswTicketNumber($T->ticketID)
	   ),
	   $msg_edigest3
	  ).mswDefineNewline();
      $emailDigest[2] .= str_replace(
	   array('{name}','{updated}'),
	   array(
	    mswCleanData($T->ticketName).' ('.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->timeformat).')',
		($last[0]!='0' ? mswCleanData($last[0]).' ('.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->timeformat).')' : 'N/A')
        ),
		$msg_edigest5
	  ).$link.mswDefineNewline().mswDefineNewline();
    }
  } else {
    $emailDigest[2] = $msg_edigest;
  }
  
  //-----------------------------------------
  // Tickets awaiting visitor response
  //-----------------------------------------
  
  $q = mysql_query("SELECT `subject`,`priority`,`".DB_PREFIX."tickets`.`id` AS `ticketID`,
	   `".DB_PREFIX."portal`.`name` AS `ticketName`,
	   `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	   `".DB_PREFIX."departments`.`name` AS `deptName`,
	   `".DB_PREFIX."levels`.`name` AS `levelName`
	   FROM `".DB_PREFIX."tickets` 
       LEFT JOIN `".DB_PREFIX."departments`
	   ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	   LEFT JOIN `".DB_PREFIX."portal`
	   ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	   LEFT JOIN `".DB_PREFIX."levels`
	   ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	    OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
	   WHERE `replyStatus` IN('visitor') 
       AND `ticketStatus`   = 'open' 
       AND `isDisputed`     = 'no'
       AND `assignedto`    != 'waiting'
	   AND `spamFlag`       = 'no'
       ".(!empty($dept) ? 'AND (`department` IN('.implode(',',$dept).') OR FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0)' : '')."
	   ".($USERS->id!='1' && empty($dept) && $USERS->assigned=='yes' ? 'AND FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0' : '')."
       ORDER BY FIELD(`".DB_PREFIX."tickets`.`priority`,'high','medium','low'),`levelName`
       ");
  if ($q && mysql_num_rows($q)>0) {
    while ($T = mysql_fetch_object($q)) {
      ++$counts[3];
      // Hyperlink..
	  $link  = mswDefineNewline();
      $link .= $SETTINGS->scriptpath.'/'.$SETTINGS->afolder.'/?ticket='.$T->ticketID;
      // Get last reply..
	  $last  = $MSTICKET->getLastReply($T->ticketID);
      $emailDigest[3] .= str_replace(
	   array('{priority}','{subject}','{ticket}'),
	   array(
	    strtoupper($MSYS->levels($T->priority)),
		mswCleanData($T->subject),
		mswTicketNumber($T->ticketID)
	   ),
	   $msg_edigest3
	  ).mswDefineNewline();
      $emailDigest[3] .= str_replace(
	   array('{name}','{updated}'),
	   array(
	    mswCleanData($T->ticketName).' ('.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->timeformat).')',
		($last[0]!='0' ? mswCleanData($last[0]).' ('.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->timeformat).')' : 'N/A')
        ),
		$msg_edigest5
	  ).$link.mswDefineNewline().mswDefineNewline();
    }
  } else {
    $emailDigest[3] = $msg_edigest;
  }
  
  //-----------------------------
  // New disputes, if enabled
  //-----------------------------
  
  if ($SETTINGS->disputes=='yes') {
    $q = mysql_query("SELECT `subject`,`priority`,`".DB_PREFIX."tickets`.`id` AS `ticketID`,
	     `".DB_PREFIX."portal`.`name` AS `ticketName`,
	     `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	     `".DB_PREFIX."departments`.`name` AS `deptName`,
	     `".DB_PREFIX."levels`.`name` AS `levelName`
	     FROM `".DB_PREFIX."tickets` 
         LEFT JOIN `".DB_PREFIX."departments`
	     ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	     LEFT JOIN `".DB_PREFIX."portal`
	     ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	     LEFT JOIN `".DB_PREFIX."levels`
	     ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	      OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
	     WHERE `replyStatus` IN('start') 
         AND `ticketStatus`   = 'open' 
         AND `isDisputed`     = 'yes'
         AND `assignedto`    != 'waiting'
		 AND `spamFlag`       = 'no'
         ".(!empty($dept) ? 'AND (`department` IN('.implode(',',$dept).') OR FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0)' : '')."
	     ".($USERS->id!='1' && empty($dept) && $USERS->assigned=='yes' ? 'AND FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0' : '')."
         ORDER BY FIELD(`".DB_PREFIX."tickets`.`priority`,'high','medium','low'),`levelName`
         ");
    if ($q && mysql_num_rows($q)>0) {
      while ($T = mysql_fetch_object($q)) {
        ++$counts[4];
        // Hyperlink..
	    $link  = mswDefineNewline();
        $link .= $SETTINGS->scriptpath.'/'.$SETTINGS->afolder.'/?ticket='.$T->ticketID;
        // Get last reply..
	    $last  = $MSTICKET->getLastReply($T->ticketID);
        $emailDigest[4] .= str_replace(
	     array('{priority}','{subject}','{ticket}'),
	     array(
	      strtoupper($MSYS->levels($T->priority)),
		  mswCleanData($T->subject),
		  mswTicketNumber($T->ticketID)
	     ),
	     $msg_edigest3
	    ).mswDefineNewline();
        $emailDigest[4] .= str_replace(
	     array('{name}','{updated}','{count}'),
	     array(
	      mswCleanData($T->ticketName).' ('.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->timeformat).')',
		  ($last[0]!='0' ? mswCleanData($last[0]).' ('.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->timeformat).')' : 'N/A'),
		  0
        ),
		$msg_edigest6
	    ).$link.mswDefineNewline().mswDefineNewline();
      }
    } else {
      $emailDigest[4] = $msg_edigest2;
    }
  }
	
  //--------------------------------------
  // Disputes awaiting staff response
  //--------------------------------------
  
  if ($SETTINGS->disputes=='yes') {
    $q = mysql_query("SELECT `subject`,`priority`,`".DB_PREFIX."tickets`.`id` AS `ticketID`,
	     `".DB_PREFIX."portal`.`name` AS `ticketName`,
	     `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	     `".DB_PREFIX."departments`.`name` AS `deptName`,
	     `".DB_PREFIX."levels`.`name` AS `levelName`
	     FROM `".DB_PREFIX."tickets` 
         LEFT JOIN `".DB_PREFIX."departments`
	     ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	     LEFT JOIN `".DB_PREFIX."portal`
	     ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	     LEFT JOIN `".DB_PREFIX."levels`
	     ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	      OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
	     WHERE `replyStatus` IN('admin') 
         AND `ticketStatus`   = 'open' 
         AND `isDisputed`     = 'yes'
         AND `assignedto`    != 'waiting'
		 AND `spamFlag`       = 'no'
         ".(!empty($dept) ? 'AND (`department` IN('.implode(',',$dept).') OR FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0)' : '')."
	     ".($USERS->id!='1' && empty($dept) && $USERS->assigned=='yes' ? 'AND FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0' : '')."
         ORDER BY FIELD(`".DB_PREFIX."tickets`.`priority`,'high','medium','low'),`levelName`
         ");
    if ($q && mysql_num_rows($q)>0) {
      while ($T = mysql_fetch_object($q)) {
        ++$counts[5];
        // Hyperlink..
	    $link  = mswDefineNewline();
        $link .= $SETTINGS->scriptpath.'/'.$SETTINGS->afolder.'/?ticket='.$T->ticketID;
        // Get last reply..
	    $last  = $MSTICKET->getLastReply($T->ticketID);
        $emailDigest[5] .= str_replace(
	     array('{priority}','{subject}','{ticket}'),
	     array(
	      strtoupper($MSYS->levels($T->priority)),
		  mswCleanData($T->subject),
		  mswTicketNumber($T->ticketID)
	     ),
	     $msg_edigest3
	    ).mswDefineNewline();
        $emailDigest[5] .= str_replace(
	     array('{name}','{updated}','{count}'),
	     array(
	      mswCleanData($T->ticketName).' ('.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->timeformat).')',
		  ($last[0]!='0' ? mswCleanData($last[0]).' ('.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->timeformat).')' : 'N/A'),
		  0
        ),
		$msg_edigest6
	    ).$link.mswDefineNewline().mswDefineNewline();
      }
    } else {
      $emailDigest[5] = $msg_edigest2;
    }
  }
  
  //------------------------------------
  // Disputes awaiting visitor response
  //------------------------------------
  
  if ($SETTINGS->disputes=='yes') {
    $q = mysql_query("SELECT `subject`,`priority`,`".DB_PREFIX."tickets`.`id` AS `ticketID`,
	     `".DB_PREFIX."portal`.`name` AS `ticketName`,
	     `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	     `".DB_PREFIX."departments`.`name` AS `deptName`,
	     `".DB_PREFIX."levels`.`name` AS `levelName`
	     FROM `".DB_PREFIX."tickets` 
         LEFT JOIN `".DB_PREFIX."departments`
	     ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	     LEFT JOIN `".DB_PREFIX."portal`
	     ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	     LEFT JOIN `".DB_PREFIX."levels`
	     ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	      OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
	     WHERE `replyStatus` IN('visitor') 
         AND `ticketStatus`   = 'open' 
         AND `isDisputed`     = 'yes'
         AND `assignedto`    != 'waiting'
		 AND `spamFlag`       = 'no'
         ".(!empty($dept) ? 'AND (`department` IN('.implode(',',$dept).') OR FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0)' : '')."
	     ".($USERS->id!='1' && empty($dept) && $USERS->assigned=='yes' ? 'AND FIND_IN_SET(\''.$USERS->id.'\',`assignedto`)>0' : '')."
         ORDER BY FIELD(`".DB_PREFIX."tickets`.`priority`,'high','medium','low'),`levelName`
         ");
    if ($q && mysql_num_rows($q)>0) {
      while ($T = mysql_fetch_object($q)) {
        ++$counts[6];
        // Hyperlink..
	    $link  = mswDefineNewline();
        $link .= $SETTINGS->scriptpath.'/'.$SETTINGS->afolder.'/?ticket='.$T->ticketID;
        // Get last reply..
	    $last  = $MSTICKET->getLastReply($T->ticketID);
        $emailDigest[6] .= str_replace(
	     array('{priority}','{subject}','{ticket}'),
	     array(
	      strtoupper($MSYS->levels($T->priority)),
		  mswCleanData($T->subject),
		  mswTicketNumber($T->ticketID)
	     ),
	     $msg_edigest3
	    ).mswDefineNewline();
        $emailDigest[6] .= str_replace(
	     array('{name}','{updated}','{count}'),
	     array(
	      mswCleanData($T->ticketName).' ('.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($T->ticketStamp,$SETTINGS->timeformat).')',
		  ($last[0]!='0' ? mswCleanData($last[0]).' ('.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($last[1],$SETTINGS->timeformat).')' : 'N/A'),
		  0
        ),
		$msg_edigest6
	    ).$link.mswDefineNewline().mswDefineNewline();
      }
    } else {
      $emailDigest[6] = $msg_edigest2;
    }
  }
  
  //-------------------------------------------------------------
  // Send Mail, but only if there is something to report about
  //-------------------------------------------------------------
  
  if (array_sum($counts)>0) {
  
    for ($i=0; $i<count($counts); $i++) {
      $MSMAIL->addTag('{C'.($i+1).'}', @number_format($counts[$i]));
	  $MSMAIL->addTag('{DATA_'.($i+1).'}', rtrim($emailDigest[$i]));
	}
    
	// Additional tags..
	if ($USERS->timezone!='0') {
	  $MSMAIL->addTag('{DATE}', $MSDT->mswDateTimeDisplay(0,$SETTINGS->dateformat,$USERS->timezone));
      $MSMAIL->addTag('{TIME}', $MSDT->mswDateTimeDisplay(0,$SETTINGS->timeformat,$USERS->timezone));
	}
	
	$mailT = 'email-digest'.($USERS->id=='1' || $USERS->digestasg=='yes' ? '-admin' : '-user').($SETTINGS->disputes=='yes' ? '-dis' : '').'.txt';
    
	if (file_exists(PATH.'content/language/'.$SETTINGS->language.'/mail-templates/'.$mailT)) {
	  $MSMAIL->addTag('{NAME}', $USERS->name);
	  $MSMAIL->sendMSMail(
	   array(
	    'from_email' => $SETTINGS->email,
	    'from_name'  => $SETTINGS->website,
	    'to_email'   => $USERS->email,
	    'to_name'    => $USERS->name,
	    'subject'    => str_replace(
	     array('{website}'),
	     array($SETTINGS->website),
	     $emailSubjects['email-digest']
	    ),
	    'replyto'    => array(
	     'name'      => $SETTINGS->website,
	     'email'     => ($SETTINGS->replyto ? $SETTINGS->replyto : $SETTINGS->email) 
	    ),
	    'template'   => PATH.'content/language/'.$SETTINGS->language.'/mail-templates/'.$mailT,
	    'language'   => $SETTINGS->language,
	    'alive'      => 'yes',
	    'add-emails' => $USERS->email2
	   )
      );
	}
  
  }
}

//------------------
// The End
//------------------

echo str_replace(
 array('{started}','{finished}'),
 array(
  $startTime,
  $MSDT->mswDateTimeDisplay(0,$SETTINGS->dateformat).'/'.$MSDT->mswDateTimeDisplay(0,$SETTINGS->timeformat)
 ),
 $msg_edigest4
);

?>