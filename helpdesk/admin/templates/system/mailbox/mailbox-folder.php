<?php if (!defined('PARENT') || !isset($toLoad)) { exit; }
$orderBy   = 'ORDER BY `'.DB_PREFIX.'mailbox`.`ts` DESC';
$keys      = (isset($_GET['keys']) ? $_GET['keys'] : '');
$searchSQL = '';
// Are we searching?
if ($keys) {
  $searchSQL = 'AND (`'.DB_PREFIX.'mailbox`.`subject` LIKE \'%'.mswSafeImportString($keys).'%\' OR `'.DB_PREFIX.'mailbox`.`message` LIKE \'%'.mswSafeImportString($keys).'%\')';
}
$q = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
     `".DB_PREFIX."mailbox`.`staffID` AS `starter`,
	 `".DB_PREFIX."mailbox`.`ts` AS `mailStamp`,
	 `".DB_PREFIX."mailassoc`.`mailID` AS `messageID`
	 FROM `".DB_PREFIX."mailassoc`
	 LEFT JOIN `".DB_PREFIX."mailbox`
	 ON `".DB_PREFIX."mailassoc`.`mailID`   = `".DB_PREFIX."mailbox`.`id`
	 LEFT JOIN `".DB_PREFIX."users`
	 ON `".DB_PREFIX."users`.`id`           = `".DB_PREFIX."mailbox`.`staffID`
	 WHERE `folder`                         = '{$toLoad}' 
     AND `".DB_PREFIX."mailassoc`.`staffID` = '{$MSTEAM->id}'
	 ".($searchSQL ? $searchSQL.mswDefineNewline().'GROUP BY `'.DB_PREFIX.'mailassoc`.`mailID`' : '')."
	 ".$orderBy."
     LIMIT $limitvalue,$limit
     ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows  =  (isset($c->rows) ? $c->rows : '0');
?>
<div class="content">
        
  <div class="header">
    
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys','mailbox')"><i class="icon-search" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $msg_adheader61; ?> (<?php echo $boxName; ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader61; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $boxName; ?></li>
  </ul>
  
  <?php
  // Read..
  if (isset($OK)) {
    echo mswActionCompleted(str_replace('{count}',$cnt,$msg_mailbox25));
  }
  // Unread..
  if (isset($OK2)) {
    echo mswActionCompleted(str_replace('{count}',$cnt,$msg_mailbox26));
  }
  // Move..
  if (isset($OK3)) {
    switch ($_POST['moveto']) {
	  case 'inbox':
	  $movedTo = $msg_mailbox;
	  break;
	  case 'outbox':
	  $movedTo = $msg_mailbox2;
	  break;
	  case 'bin':
	  $movedTo = $msg_mailbox3;
	  break;
	  default:
	  $F       = mswGetTableData('mailfolders','id',(int)$_POST['moveto'],'AND `staffID` = \''.$MSTEAM->id.'\'');
	  $movedTo = (isset($F->folder) ? mswCleanData($F->folder) : 'N/A');
	  break;
	}
    echo mswActionCompleted(str_replace(array('{count}','{folder}'),array($cnt,$movedTo),$msg_mailbox27));
  }
  // Delete..
  if (isset($OK4)) {
    echo mswActionCompleted(str_replace('{count}',$cnt,$msg_mailbox28));
  }
  // Clear..
  if (isset($OK5)) {
    echo mswActionCompleted($msg_mailbox29);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
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
		 <div class="well" style="margin-bottom:10px;padding-bottom:0">
		  <table class="table table-striped table-hover">
          <thead>
           <tr>
            <th style="width:5%">
			 <input type="checkbox" onclick="checkBoxes(this.checked,'.well');ms_checkCount('well','delButton','mc_countVal');ms_checkCount('well','readButton','mc_countVal2');ms_checkCount('well','unreadButton','mc_countVal3')">
		    </th>
			<th style="width:55%"><?php echo $msg_viewticket25; ?></th>
		    <th style="width:20%"><?php echo $msg_mailbox17; ?></th>
            <th style="width:20%"><?php echo $msg_open37; ?></th>
           </tr>
          </thead>
          <tbody>
		  <?php
		  if (mysql_num_rows($q)>0) {
          while ($MSG = mysql_fetch_object($q)) {
		  $last = $MSMB->getLastReply($MSG->messageID);
		  $rec  = $MSMB->getRecipient($MSG->messageID,$MSTEAM->id);
		  ?>
		  <tr>
		   <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal');ms_checkCount('well','readButton','mc_countVal2');ms_checkCount('well','unreadButton','mc_countVal3')" name="id[]" value="<?php echo $MSG->messageID; ?>" id="mailmsg_<?php echo $MSG->messageID; ?>"></td>
		   <td class="mailBoxSubject"><a class="<?php echo $MSG->status; ?>" href="?p=mailbox&amp;msg=<?php echo $MSG->messageID; ?>" title="<?php echo mswSpecialChars($msg_mailbox18); ?>"><?php echo mswCleanData($MSG->subject); ?></a>
		   <span class="ticketDate">
		   <?php
		   // If person who sent message is logged in, its to, else its from..
		   if ($MSG->staffID==$MSTEAM->id) {
		     echo $msg_mailbox34.': '.$rec;
		   } else {
		     //echo $msg_mailbox33.': '.$rec;
		   }
		   ?>
		   </span>
		   </td>
		   <td><?php echo mswCleanData($MSG->name); ?>
		   <span class="ticketDate"><?php echo $MSDT->mswDateTimeDisplay($MSG->mailStamp,$SETTINGS->dateformat); ?> @ <?php echo $MSDT->mswDateTimeDisplay($MSG->mailStamp,$SETTINGS->timeformat); ?></span>
		   </td>
		   <td>
		   <?php 
		   if (isset($last[0]) && $last[0]!='0') {
		   echo mswCleanData($last[0]);
		   ?>
		   <span class="ticketDate"><?php echo $MSDT->mswDateTimeDisplay($last[1],$SETTINGS->dateformat); ?> @ <?php echo $MSDT->mswDateTimeDisplay($last[1],$SETTINGS->timeformat); ?></span>
		   <?php
		   } else {
		     echo '- - - -';
		   }
		   ?>
		   </td>
		  </tr>
		  <?php
		  }
		  } else {
		  ?>
		  <tr class="warning nothing_to_see">
		   <td colspan="4"><?php echo $msg_mailbox16; ?></td>
		  </tr>
		  <?php
		  }
		  ?>
          </tbody>
		  </table> 
		 </div>
		</div>
	  </div>
	  <?php
	  if ($countedRows>0) {
	  ?>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <?php
	   if ($toLoad=='bin' && ($MSTEAM->mailDeletion=='yes' || $MSTEAM->id=='1')) {
	   ?>
	   <div class="pull-right">
		<button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','clear');return false;" type="submit" name="export" class="btn btn-danger"><i class="icon-trash"></i> <?php echo mswSpecialChars($msg_mailbox23); ?></button>
	   </div>
	   <?php
	   }
	   ?>
       <div class="btn-group dropup">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
         <?php echo $msg_mailbox24; ?>
         <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
		 <?php
		 foreach ($moveToFolders AS $k => $v) {
		 // Don`t show current folder..
		 if ($k!=$toLoad) {
		 // Spacer..
		 if ($k=='-') {
		 ?>
		 <li><span style="padding-left:20px"><?php echo $v; ?></span></li>
		 <?php
		 } else {
		 ?>
		 <li><a href="#" onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','moveto','<?php echo $k; ?>');return false;"><?php echo $v; ?></a></li>
		 <?php
		 }
		 }
		 }
		 ?>
        </ul>
       </div>
	   <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','read');return false;" class="btn btn-primary" disabled="disabled" type="submit" id="readButton" title="<?php echo mswSpecialChars($msg_mailbox19); ?>"><i class="icon-flag-alt"></i>  <span id="mc_countVal2">(0)</span></button>
       <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','unread');return false;" class="btn btn-primary" disabled="disabled" type="submit" id="unreadButton" title="<?php echo mswSpecialChars($msg_mailbox20); ?>"><i class="icon-flag"></i> <span id="mc_countVal3">(0)</span></button>
       <?php
	   if ($toLoad=='bin' && ($MSTEAM->mailDeletion=='yes' || $MSTEAM->id=='1')) {
	   ?>
	   <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','delete');return false;" class="btn btn-danger" disabled="disabled" type="submit" id="delButton" title="<?php echo mswCleanData($msg_mailbox22); ?>"><i class="icon-trash"></i> <span id="mc_countVal">(0)</span></button>
	   <?php
	   }
	   ?>
	  </div>
	  <?php
	  }
	  
	  if ($countedRows>0 && $countedRows>$limit) {
	  ?>
	  <div class="pagination pagination-small pagination-right">
       <?php
	   define('PER_PAGE',$limit);
       $PGS = new pagination($countedRows,'?p='.$cmd.mswQueryParams(array('p','next')).'&amp;next=');
       echo $PGS->display();
	   ?>
      </div>
	  <?php
	  }
	  
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>

  </div>
  </form>
	
</div>