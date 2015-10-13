<?php if (!defined('PARENT') || !isset($MMSG->id)) { exit; } 
// Who started this message?
if ($MMSG->staffID==$MSTEAM->id) {
  $msgPoster = mswCleanData($MSTEAM->name);
} else {
  $PST       = mswGetTableData('users','id',$MMSG->staffID);
  $msgPoster = (isset($PST->name) ? mswCleanData($PST->name) : 'N/A');
}
?>
<div class="content">
  <?php
  // Load the print friendly plugin..
  include(PATH.'templates/print-friendly.php');
  ?>
  <div class="header">
    
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<button class="btn search-bar-button" type="button" onclick="window.print()" title="<?php echo mswSpecialChars($msg_script13); ?>"><i class="icon-print"></i></button>
	<h1 class="page-title"><?php echo $msg_adheader61; ?> (<?php echo $msg_mailbox7; ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader61; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_mailbox7; ?></li>
  </ul>
  
  <?php
  // Add reply..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_mailbox31);
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('message','none')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <?php
	  // Search..
	  include(PATH.'templates/system/bootstrap/search-box.php');
	  // Mailbox menu..
	  include(PATH.'templates/system/mailbox/mailbox-nav.php');
	  ?>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well" style="padding-bottom:10px">
		  <?php
		  echo $MSPARSER->mswTxtParsingEngine($MMSG->message);
		  ?>
		  <p class="mailBoxMsgBar">
		  <?php echo mswSpecialChars($msgPoster); ?> &#8226; <?php echo $MSDT->mswDateTimeDisplay($MMSG->ts,$SETTINGS->dateformat).' &#8226; '.$MSDT->mswDateTimeDisplay($MMSG->ts,$SETTINGS->timeformat); ?>
		  </p>
		 </div>
		 <?php
		 // Replies
		 $reps = 0;
         $qPMR = mysql_query("SELECT *,`".DB_PREFIX."mailreplies`.`ts` AS `repStamp` FROM `".DB_PREFIX."mailreplies`
		         LEFT JOIN `".DB_PREFIX."users`
				 ON `".DB_PREFIX."mailreplies`.`staffID` = `".DB_PREFIX."users`.`id`
                 WHERE `mailID` = '{$MMSG->id}'
                 ORDER BY `".DB_PREFIX."mailreplies`.`id`
				 ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         if (mysql_num_rows($qPMR)>0) {
		 while ($REPLIES = mysql_fetch_object($qPMR)) {
		 ?>
		 <div class="well mailBoxReply<?php echo (is_int(++$reps/2) ? 'Even' : 'Odd'); ?>">
		  <?php
		  echo $MSPARSER->mswTxtParsingEngine($REPLIES->message);
		  ?>
		  <p class="mailBoxMsgBar">
		  <?php echo mswSpecialChars($REPLIES->name); ?> &#8226; <?php echo $MSDT->mswDateTimeDisplay($REPLIES->repStamp,$SETTINGS->dateformat).' &#8226; '.$MSDT->mswDateTimeDisplay($REPLIES->repStamp,$SETTINGS->timeformat); ?>
		  </p>
		 </div>
		 <?php
         }
		 }
		 $BF = mswGetTableData('mailassoc','mailID',(int)$_GET['msg'],'AND `staffID` = \''.$MSTEAM->id.'\'');
		 ?>
		 <div class="well" style="padding-bottom:10px">
		  <textarea name="message" rows="5" cols="20" tabindex="<?php echo (++$tabIndex); ?>" style="width:97%"></textarea><br>
		  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
		   <input type="hidden" name="add-reply" value="yes">
		   <input type="hidden" name="msgStaff" value="<?php echo $MMSG->staffID; ?>">
		   <input type="hidden" name="subject" value="<?php echo mswSpecialChars($MMSG->subject); ?>">
           <button class="btn btn-primary" type="submit"><i class="icon-plus"></i> <?php echo $msg_mailbox30; ?></button>
		   <?php
		   if (isset($BF->folder)) {
		   ?>
           <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=mailbox&amp;f=<?php echo $BF->folder; ?>')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
           <?php
		   }
		   ?>
		  </div>
		 </div>
		</div>
	  </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>

  </div>
  </form>
	
</div>