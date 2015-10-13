<?php if (!defined('PARENT')) { exit; } 
$cutOff = 100;
?>
    <div class="row-fluid">
     <div class="span8">
	
	  <?php
	  // Graph overview..
	  include(PATH.'templates/system/home/graph.php');
	  
	  // Anything to be assigned?
	  if (mswRowCount('tickets WHERE `ticketStatus` = \'open\'
	    AND `replyStatus` IN(\'start\',\'admin\')
		AND `assignedto`   = \'waiting\'
		AND `spamFlag`     = \'no\'')>0) {
	  ?>
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading uppercase"><i class="icon-chevron-right"></i> <?php echo $msg_home52; ?></p>
        <div class="block-body">
		 <?php
		 $lp  = 0;
         $qTA = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                `".DB_PREFIX."tickets`.`id` AS `ticketID`,
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
                WHERE `ticketStatus` = 'open'
	            AND `replyStatus`   IN('start','admin') 
                AND `isDisputed`     = 'no'
                AND `assignedto`     = 'waiting'
	            AND `spamFlag`       = 'no'
                ".mswSQLDepartmentFilter($ticketFilterAccess)."
                ORDER BY `".DB_PREFIX."tickets`.`id` DESC
                ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		 $TARows = mysql_num_rows($qTA);
         if ($TARows>0) {
          while ($TICKETS = mysql_fetch_object($qTA)) {
		  $date = '';
          ?>
          <div class="row-fluid homeTicketWrapper<?php echo ((++$lp)==$TARows ? ' nobottomborder' : ''); ?>">
           <a href="?p=view-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>">
		   <?php 
			echo ($cutOff>0 && strlen($TICKETS->subject)>$cutOff ? substr(mswSpecialChars($TICKETS->subject),0,($cutOff-2)).'..' : mswSpecialChars($TICKETS->subject)); 
		   ?>
		   </a>
		   <span class="bar">
			<?php echo str_replace(
			 array('{name}','{priority}','{date}','{ticket}'),
			 array(
			  mswSpecialChars($TICKETS->ticketName),
			  mswCleanData($TICKETS->levelName),
			  $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->dateformat),
			  mswTicketNumber($TICKETS->ticketID)
			 ),
			 $msg_home44
			 ); 
			?>
		   </span>
		  </div>
          <?php
          }
         } else {
         ?>
         <p class="nothing_to_see smalltxt"><?php echo $msg_home41; ?></p>
         <?php
         }
         ?>
        </div>
       </div>
	  </div>
	  <?php
	  }
	  ?>
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading uppercase"><i class="icon-chevron-right"></i> <?php echo $msg_home31; ?></p>
        <div class="block-body">
		 <?php
		 $lp  = 0;
         $qT1 = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                `".DB_PREFIX."tickets`.`id` AS `ticketID`,
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
                WHERE `ticketStatus` = 'open'
	            AND `replyStatus`   IN('start','admin') 
                AND `isDisputed`     = 'no'
                AND `assignedto`    != 'waiting'
	            AND `spamFlag`       = 'no'
                ".mswSQLDepartmentFilter($ticketFilterAccess)."
                ORDER BY `".DB_PREFIX."tickets`.`id` DESC
                ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		 $T1Rows = mysql_num_rows($qT1);
         if ($T1Rows>0) {
          while ($TICKETS = mysql_fetch_object($qT1)) {
		  $date = '';
          ?>
          <div class="row-fluid homeTicketWrapper<?php echo ((++$lp)==$T1Rows ? ' nobottomborder' : ''); ?>">
           <a href="?p=view-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>">
		   <?php 
			echo ($cutOff>0 && strlen($TICKETS->subject)>$cutOff ? substr(mswSpecialChars($TICKETS->subject),0,($cutOff-2)).'..' : mswSpecialChars($TICKETS->subject)); 
		   ?>
		   </a>
		   <span class="bar">
			<?php echo str_replace(
			 array('{name}','{priority}','{date}','{ticket}'),
			 array(
			  mswSpecialChars($TICKETS->ticketName),
			  mswCleanData($TICKETS->levelName),
			  $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->dateformat),
			  mswTicketNumber($TICKETS->ticketID)
			 ),
			 $msg_home44
			 ); 
			?>
		   </span>
		  </div>
          <?php
          }
         } else {
         ?>
         <p class="nothing_to_see smalltxt"><?php echo $msg_home41; ?></p>
         <?php
         }
         ?>
        </div>
       </div>
	  </div>
	  
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading uppercase"><i class="icon-chevron-right"></i> <?php echo $msg_home39; ?></p>
        <div class="block-body">
		 <?php
		 $lp  = 0;
         $qT2 = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                `".DB_PREFIX."tickets`.`id` AS `ticketID`,
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
                WHERE `ticketStatus` = 'open'
	            AND `replyStatus`   IN('visitor')
                AND `isDisputed`     = 'no'
                AND `assignedto`    != 'waiting'
	            AND `spamFlag`       = 'no'
                ".mswSQLDepartmentFilter($ticketFilterAccess)."
                ORDER BY `".DB_PREFIX."tickets`.`id` DESC
                ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		 $T2Rows = mysql_num_rows($qT2);
         if ($T2Rows>0) {
          while ($TICKETS = mysql_fetch_object($qT2)) {
		  $date = '';
          ?>
          <div class="row-fluid homeTicketWrapper<?php echo ((++$lp)==$T2Rows ? ' nobottomborder' : ''); ?>">
           <a href="?p=view-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>">
		   <?php 
			echo ($cutOff>0 && strlen($TICKETS->subject)>$cutOff ? substr(mswSpecialChars($TICKETS->subject),0,($cutOff-2)).'..' : mswSpecialChars($TICKETS->subject)); 
		   ?>
		   </a>
		   <span class="bar">
			<?php echo str_replace(
			 array('{name}','{priority}','{date}','{ticket}'),
			 array(
			  mswSpecialChars($TICKETS->ticketName),
			  mswCleanData($TICKETS->levelName),
			  $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->dateformat),
			  mswTicketNumber($TICKETS->ticketID)
			 ),
			 $msg_home44
			 ); 
			?>
		   </span>
		  </div>
          <?php
          }
         } else {
         ?>
         <p class="nothing_to_see smalltxt"><?php echo $msg_home41; ?></p>
         <?php
         }
         ?>
        </div>
       </div>
	  </div>
	  
	  <?php
	  if ($SETTINGS->disputes=='yes') {
	  ?>
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading uppercase"><i class="icon-chevron-right"></i> <?php echo $msg_home32; ?></p>
        <div class="block-body">
		 <?php
		 $lp  = 0;
         $qT3 = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                `".DB_PREFIX."tickets`.`id` AS `ticketID`,
	            `".DB_PREFIX."portal`.`name` AS `ticketName`,
	            `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	            `".DB_PREFIX."departments`.`name` AS `deptName`,
	            `".DB_PREFIX."levels`.`name` AS `levelName`,
	            (SELECT count(*) FROM `".DB_PREFIX."disputes` 
	             WHERE `".DB_PREFIX."disputes`.`ticketID` = `".DB_PREFIX."tickets`.`id`
	            ) AS `disputeCount`
	            FROM `".DB_PREFIX."tickets` 
                LEFT JOIN `".DB_PREFIX."departments`
	            ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	            LEFT JOIN `".DB_PREFIX."portal`
	            ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	            LEFT JOIN `".DB_PREFIX."levels`
	            ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	             OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
                WHERE `ticketStatus` = 'open'
	            AND `replyStatus`   IN('start','admin') 
                AND `isDisputed`     = 'yes'
                AND `assignedto`    != 'waiting'
	            AND `spamFlag`       = 'no'
                ".mswSQLDepartmentFilter($ticketFilterAccess)."
                ORDER BY `".DB_PREFIX."tickets`.`id` DESC
                ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		 $T3Rows = mysql_num_rows($qT3);
         if ($T3Rows>0) {
          while ($TICKETS = mysql_fetch_object($qT3)) {
		  $date = '';
          ?>
          <div class="row-fluid homeTicketWrapper<?php echo ((++$lp)==$T3Rows ? ' nobottomborder' : ''); ?>">
           <a href="?p=view-dispute&amp;id=<?php echo $TICKETS->ticketID; ?>">
		   <?php 
			echo ($cutOff>0 && strlen($TICKETS->subject)>$cutOff ? substr(mswSpecialChars($TICKETS->subject),0,($cutOff-2)).'..' : mswSpecialChars($TICKETS->subject)); 
		   ?>
		   </a>
		   <span class="bar">
			<?php echo str_replace(
			 array('{name}','{priority}','{date}','{ticket}','{count}'),
			 array(
			  mswSpecialChars($TICKETS->ticketName),
			  mswCleanData($TICKETS->levelName),
			  $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->dateformat),
			  mswTicketNumber($TICKETS->ticketID),
			  ($TICKETS->disputeCount+1)
			 ),
			 $msg_home45
			 ); 
			?>
		   </span>
		  </div>
          <?php
          }
         } else {
         ?>
         <p class="nothing_to_see smalltxt"><?php echo $msg_home41; ?></p>
         <?php
         }
         ?>
        </div>
       </div>
	  </div>
	  
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading uppercase"><i class="icon-chevron-right"></i> <?php echo $msg_home40; ?></p>
        <div class="block-body">
		 <?php
		 $lp  = 0;
         $qT4 = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                `".DB_PREFIX."tickets`.`id` AS `ticketID`,
	            `".DB_PREFIX."portal`.`name` AS `ticketName`,
	            `".DB_PREFIX."tickets`.`ts` AS `ticketStamp`,
	            `".DB_PREFIX."departments`.`name` AS `deptName`,
	            `".DB_PREFIX."levels`.`name` AS `levelName`,
	            (SELECT count(*) FROM `".DB_PREFIX."disputes` 
	             WHERE `".DB_PREFIX."disputes`.`ticketID` = `".DB_PREFIX."tickets`.`id`
	            ) AS `disputeCount`
	            FROM `".DB_PREFIX."tickets` 
                LEFT JOIN `".DB_PREFIX."departments`
	            ON `".DB_PREFIX."tickets`.`department` = `".DB_PREFIX."departments`.`id`
	            LEFT JOIN `".DB_PREFIX."portal`
	            ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	            LEFT JOIN `".DB_PREFIX."levels`
	            ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	             OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
                WHERE `ticketStatus` = 'open'
	            AND `replyStatus`   IN('visitor') 
                AND `isDisputed`     = 'yes'
                AND `assignedto`    != 'waiting'
	            AND `spamFlag`       = 'no'
                ".mswSQLDepartmentFilter($ticketFilterAccess)."
                ORDER BY `".DB_PREFIX."tickets`.`id` DESC
                ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		 $T4Rows = mysql_num_rows($qT4);
         if ($T4Rows>0) {
          while ($TICKETS = mysql_fetch_object($qT4)) {
		  $date = '';
          ?>
          <div class="row-fluid homeTicketWrapper<?php echo ((++$lp)==$T4Rows ? ' nobottomborder' : ''); ?>">
           <a href="?p=view-dispute&amp;id=<?php echo $TICKETS->ticketID; ?>">
		   <?php 
			echo ($cutOff>0 && strlen($TICKETS->subject)>$cutOff ? substr(mswSpecialChars($TICKETS->subject),0,($cutOff-2)).'..' : mswSpecialChars($TICKETS->subject)); 
		   ?>
		   </a>
		   <span class="bar">
			<?php echo str_replace(
			 array('{name}','{priority}','{date}','{ticket}','{count}'),
			 array(
			  mswSpecialChars($TICKETS->ticketName),
			  mswCleanData($TICKETS->levelName),
			  $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->dateformat),
			  mswTicketNumber($TICKETS->ticketID),
			  ($TICKETS->disputeCount+1)
			 ),
			 $msg_home45
			 ); 
			?>
		   </span>
		  </div>
          <?php
          }
         } else {
         ?>
         <p class="nothing_to_see smalltxt"><?php echo $msg_home41; ?></p>
         <?php
         }
         ?>
        </div>
       </div>
	  </div>
	  <?php
	  }
	  ?>
	  
	 </div>
     <div class="span4">
	 
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading uppercase"><i class="icon-ticket"></i> <?php echo $msg_home3; ?></p>
        <div class="block-body" style="line-height:30px">
		 <?php
		 $arrTickOverview = array(
		  mswRowCount('tickets WHERE `replyStatus` = \'start\' AND `ticketStatus` = \'open\' AND `assignedto` = \'waiting\' AND `spamFlag` = \'no\' AND `isDisputed` = \'no\' '.mswSQLDepartmentFilter($ticketFilterAccess)),
		  mswRowCount('tickets WHERE `replyStatus` = \'start\' AND `ticketStatus` = \'open\' AND `assignedto` != \'waiting\' AND `spamFlag` = \'no\' AND `isDisputed` = \'no\' '.mswSQLDepartmentFilter($ticketFilterAccess)),
		  mswRowCount('tickets WHERE `replyStatus` = \'admin\' AND `ticketStatus` = \'open\' AND `assignedto` != \'waiting\' AND `spamFlag` = \'no\' AND `isDisputed` = \'no\' '.mswSQLDepartmentFilter($ticketFilterAccess)),
		  mswRowCount('tickets WHERE `replyStatus` = \'visitor\' AND `ticketStatus` = \'open\' AND `assignedto` != \'waiting\' AND `spamFlag` = \'no\' AND `isDisputed` = \'no\' '.mswSQLDepartmentFilter($ticketFilterAccess)),
		  mswRowCount('tickets WHERE `ticketStatus` != \'open\' AND `assignedto` != \'waiting\' AND `spamFlag` = \'no\' AND `isDisputed` = \'no\' '.mswSQLDepartmentFilter($ticketFilterAccess)),
		  mswRowCount('tickets WHERE `spamFlag` = \'yes\' '.mswSQLDepartmentFilter($ticketFilterAccess))
		 );
		 ?>
		 <a href="?p=assign"><span class="label label-important"><?php echo ($arrTickOverview[0]<10 ? '&nbsp;&nbsp;'.$arrTickOverview[0] : $arrTickOverview[0]); ?></span></a> - <?php echo $msg_home46; ?><br>
         <a href="?p=open&amp;status=start"><span class="label label-info"><?php echo ($arrTickOverview[1]<10 ? '&nbsp;&nbsp;'.$arrTickOverview[1] : $arrTickOverview[1]); ?></span></a> - <?php echo $msg_home4; ?><br>
         <a href="?p=open&amp;status=adminonly"><span class="label label-info"><?php echo ($arrTickOverview[2]<10 ? '&nbsp;&nbsp;'.$arrTickOverview[2] : $arrTickOverview[2]); ?></span></a> - <?php echo $msg_home5; ?><br>
         <a href="?p=open&amp;status=visitor"><span class="label label-info"><?php echo ($arrTickOverview[3]<10 ? '&nbsp;&nbsp;'.$arrTickOverview[3] : $arrTickOverview[3]); ?></span></a> - <?php echo $msg_home6; ?><br>
         <a href="?p=close"><span class="label label-info"><?php echo ($arrTickOverview[4]<10 ? '&nbsp;&nbsp;'.$arrTickOverview[4] : $arrTickOverview[4]); ?></span></a> - <?php echo $msg_home7; ?><br>
         <?php
		 if (mswRowCount('imap WHERE `im_piping` = \'yes\'')>0) {
		 ?>
		 <a href="?p=spam"><span class="label label-inverse"><?php echo ($arrTickOverview[5]<10 ? '&nbsp;&nbsp;'.$arrTickOverview[5] : $arrTickOverview[5]); ?></span></a> - <?php echo $msg_adheader63; ?>
         <?php
		 }
		 ?>
		</div>
       </div>
	  </div>
	  
	  <?php
	  if ($SETTINGS->disputes=='yes') {
	  ?>
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading uppercase"><i class="icon-bullhorn"></i> <?php echo $msg_home29; ?></p>
        <div class="block-body" style="line-height:30px">
		 <?php
		 $arrDispOverview = array(
		  mswRowCount('tickets WHERE `replyStatus` = \'start\' AND `ticketStatus` = \'open\' AND `assignedto` != \'waiting\' AND `spamFlag` = \'no\' AND `isDisputed` = \'yes\' '.mswSQLDepartmentFilter($ticketFilterAccess)),
		  mswRowCount('tickets WHERE `replyStatus` IN(\'admin\',\'start\') AND `ticketStatus` = \'open\' AND `assignedto` != \'waiting\' AND `spamFlag` = \'no\' AND `isDisputed` = \'yes\' '.mswSQLDepartmentFilter($ticketFilterAccess)),
		  mswRowCount('tickets WHERE `replyStatus` = \'visitor\' AND `ticketStatus` = \'open\' AND `assignedto` != \'waiting\' AND `spamFlag` = \'no\' AND `isDisputed` = \'yes\' '.mswSQLDepartmentFilter($ticketFilterAccess)),
		  mswRowCount('tickets WHERE `ticketStatus` != \'open\' AND `assignedto` != \'waiting\' AND `spamFlag` = \'no\' AND `isDisputed` = \'yes\' '.mswSQLDepartmentFilter($ticketFilterAccess))
		 );
		 ?>
		 <a href="?p=disputes&amp;status=start"><span class="label label-info"><?php echo ($arrDispOverview[0]<10 ? '&nbsp;&nbsp;'.$arrDispOverview[0] : $arrDispOverview[0]); ?></span></a> - <?php echo $msg_home43; ?><br>
         <a href="?p=disputes&amp;status=adminonly"><span class="label label-info"><?php echo ($arrDispOverview[1]<10 ? '&nbsp;&nbsp;'.$arrDispOverview[1] : $arrDispOverview[1]); ?></span></a> - <?php echo $msg_home26; ?><br>
         <a href="?p=disputes&amp;status=visitor"><span class="label label-info"><?php echo ($arrDispOverview[2]<10 ? '&nbsp;&nbsp;'.$arrDispOverview[2] : $arrDispOverview[2]); ?></span></a> - <?php echo $msg_home27; ?><br>
         <a href="?p=cdisputes"><span class="label label-info"><?php echo ($arrDispOverview[3]<10 ? '&nbsp;&nbsp;'.$arrDispOverview[3] : $arrDispOverview[3]); ?></span></a> - <?php echo $msg_home28; ?>
        </div>
       </div>
	  </div>
	  <?php
	  }
	  ?>
	  
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading uppercase"><i class="icon-gears"></i> <?php echo $msg_home2; ?></p>
        <div class="block-body" style="line-height:25px">
		  <?php
		  $arrSysOverview = array(
		   mswRowCount('users'),
		   mswRowCount('departments'),
		   mswRowCount('imap'),
		   mswRowCount('cusfields'),
		   mswRowCount('responses'),
		   mswRowCount('faq'),
		   mswRowCount('categories'),
		   mswRowCount('faqattach'),
		   count($ticketLevelSel),
		   mswRowCount('portal WHERE `enabled` = \'yes\' AND `verified` = \'yes\'')
		  );
		  ?>
		  <i class="icon-caret-right"></i> <?php echo str_replace(array('{visitors}'),array($arrSysOverview[9]),$msg_home50); ?><br>
          <i class="icon-caret-right"></i> <?php echo str_replace(array('{users}'),array($arrSysOverview[0]),$msg_home8); ?><br>
          <i class="icon-caret-right"></i> <?php echo str_replace(array('{levels}','{dept}'),array($arrSysOverview[8],$arrSysOverview[1]),$msg_home51); ?><br>
          <i class="icon-caret-right"></i> <?php echo str_replace(array('{imap}'),array($arrSysOverview[2]),$msg_home48); ?><br>
          <i class="icon-caret-right"></i> <?php echo str_replace(array('{fields}'),array($arrSysOverview[3]),$msg_home49); ?><br>
          <i class="icon-caret-right"></i> <?php echo str_replace(array('{responses}'),array($arrSysOverview[4]),$msg_home9); ?><br>
          <i class="icon-caret-right"></i> <?php echo str_replace(array('{questions}','{cats}','{attachments}'),array($arrSysOverview[5],$arrSysOverview[6],$arrSysOverview[7]),$msg_home10); ?>
        </div>
       </div>
	  </div>
	  
	  <div class="row-fluid">
       <div class="block" style="padding:0;margin:0;margin-top:10px">
	    <p class="block-heading uppercase"><i class="icon-link"></i> <?php echo $msg_home42; ?></p>
        <div class="block-body" style="line-height:25px">
		 <?php
		 // Quick links..
		 if (file_exists(PATH.'templates/system/home/quick-links-1.php')) {
		   include(PATH.'templates/system/home/quick-links-1.php');
		 } else {
		   include(PATH.'templates/system/home/quick-links.php');
		 }
		 ?>
        </div>
       </div>
	  </div>
	 
	 </div>
    </div>