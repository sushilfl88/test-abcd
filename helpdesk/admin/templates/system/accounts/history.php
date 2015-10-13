<?php if (!defined('PARENT')) { exit; } 
include(PATH.'templates/system/tickets/global/order-by.php');
include(PATH.'templates/system/tickets/global/filter-by.php');
$dis  = array();
$SQL  = '';  
if (isset($_GET['keys'])) {
  $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
  $SQL           = 'AND (LOWER(`'.DB_PREFIX.'tickets`.`subject`) LIKE \'%'.$_GET['keys'].'%\' OR LOWER(`'.DB_PREFIX.'tickets`.`comments`) LIKE \'%'.$_GET['keys'].'%\')';
}
// Disputes..
if ($SETTINGS->disputes=='yes' && isset($_GET['disputes'])) {
  // Disputes in other tickets..
  $qD = mysql_query("SELECT `ticketID` FROM `".DB_PREFIX."disputes` 
        WHERE `visitorID` = '{$ACC->id}'
	    GROUP BY `ticketID`
	    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($DP = mysql_fetch_object($qD)) {
    $dis[] = $DP->ticketID;
  }
  // Disputes from started tickets..
  $qD2 = mysql_query("SELECT `id` FROM `".DB_PREFIX."tickets` 
         WHERE `visitorID` = '{$ACC->id}'
		 AND `isDisputed`  = 'yes'
		 AND `spamFlag`    = 'no'
	     ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($DP2 = mysql_fetch_object($qD2)) {
    $dis[] = $DP2->id;
  }
}
$q = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
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
	 ON `".DB_PREFIX."tickets`.`visitorID`  = `".DB_PREFIX."portal`.`id`
	 LEFT JOIN `".DB_PREFIX."levels`
	 ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	  OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
     ".(empty($dis) ? 'WHERE `'.DB_PREFIX.'portal`.`email` = \''.$ACC->email.'\'' : '')."
	 ".(!empty($dis) ? 'WHERE `'.DB_PREFIX.'tickets`.`id` IN('.implode(',',$dis).')' : '')."
	 $SQL
	 AND `spamFlag` = 'no'
     ".$filterBy.mswSQLDepartmentFilter($ticketFilterAccess)."
     ".$orderBy."
     LIMIT $limitvalue,$limit
     ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows  =  (isset($c->rows) ? $c->rows : '0');
$searchBoxUrl = 'acchistory&id='.$_GET['id'].(isset($_GET['disputes']) ? '&disputes=yes' : '');
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  function mswHisToggle() {
    if (jQuery('#b2').css('display')=='none') {
	  jQuery('#topfluid').css('margin-top','0');
	  jQuery('#b2').show();
	  jQuery('input[name="keys"]').focus();
	  jQuery('#search-icon-button').attr('class','icon-remove');
	} else {
	  jQuery('#topfluid').css('margin-top','20px');
	  jQuery('#b2').hide();
	  jQuery('#search-icon-button').attr('class','icon-search');
	}
  }
  jQuery(document).ready(function() {
   jQuery('.nyroModal').nyroModal();
   <?php
   // Remove notes icon if permission denied..
   if ($MSTEAM->notePadEnable=='no' && $MSTEAM->id!='1') {
   ?>
   jQuery('.tIcons .nyroModal').each(function(){
     jQuery(this).remove();
   });
   <?php
   }
   ?>
  });
  //]]>
  </script>
  <div class="header">
    
	<?php
    include(PATH.'templates/system/tickets/global/order-filter.php');
    include(PATH.'templates/system/tickets/global/status-filter.php');
    include(PATH.'templates/system/tickets/global/dept-filter.php');
	include(PATH.'templates/system/bootstrap/page-filter.php');
    ?>
    <button class="btn search-bar-button" type="button" onclick="mswHisToggle()"><i class="icon-search" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $msg_accounts13; ?>: <?php echo mswSpecialChars($ACC->name); ?> (<?php echo number_format($countedRows); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader38; ?> <span class="divider">/</span></li>
	<li><a href="?p=accountman"><?php echo $msg_adheader40; ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_accounts13; ?></li>
  </ul>

  <div class="container-fluid">
    
	<div class="row-fluid" id="topfluid" style="margin-top:20px">
	  
	  <?php
	  // Search..
	  include(PATH.'templates/system/bootstrap/search-box.php');
	  ?>
	  
	  <div class="well" style="margin-bottom:10px;padding-bottom:0">
       <table class="table table-striped table-hover">
        <thead>
         <tr>
          <th style="width:10%">ID / <?php echo $msg_showticket16; ?></th>
		  <th style="width:49%"><?php echo $msg_viewticket25; ?></th>
		  <th style="width:20%"><?php echo $msg_open36; ?></th>
          <th style="width:20%"><?php echo $msg_open37; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if (mysql_num_rows($q)>0) {
         while ($TICKETS = mysql_fetch_object($q)) {
		 $last = $MSPTICKETS->getLastReply($TICKETS->ticketID);
		 ?>
         <tr>
          <td><a href="?p=view-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>" title="<?php echo mswSpecialChars($msg_viewticket11); ?>"><?php echo mswTicketNumber($TICKETS->ticketID); ?></a>
		  <span class="ticketPriority"><?php echo mswCleanData($TICKETS->levelName); ?></span>
		  </td>
          <td onmouseover="jQuery('#icon_panel_<?php echo $TICKETS->ticketID; ?>').show()" onmouseout="jQuery('#icon_panel_<?php echo $TICKETS->ticketID; ?>').hide()"><?php echo mswSpecialChars($TICKETS->subject);
		  if ($TICKETS->isDisputed=='yes') {
		  ?>
		  <span class="tdCellInfoDispute">
		  <i class="icon-file-alt"></i> <?php echo $MSYS->department($TICKETS->department,$msg_script30); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  <i class="icon-bullhorn"></i> <?php echo str_replace('{count}',($TICKETS->disputeCount+1),$msg_showticket30); ?>
		  </span>
		  <?php
		  } else {
		  ?>
		  <span class="tdCellInfo"><span class="tIcons" id="icon_panel_<?php echo $TICKETS->ticketID; ?>"><a href="?p=edit-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>" title="<?php echo mswSpecialChars($msg_viewticket120); ?>"><i class="icon-pencil"></i></a>&nbsp;&nbsp;&nbsp;<a href="?p=view-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>&amp;editNotes=yes" title="<?php echo mswSpecialChars($msg_viewticket72); ?>" class="nyroModal"><i class="icon-file-text"></i></a></span> <i class="icon-file-alt"></i> <?php echo $MSYS->department($TICKETS->department,$msg_script30); ?></span>
		  <?php
		  }
		  ?>
		  </td>
		  <td><?php echo mswSpecialChars($TICKETS->ticketName); ?>
		  <span class="ticketDate"><?php echo $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->dateformat); ?> @ <?php echo $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->timeformat); ?></span>
		  </td>
		  <td>
		  <?php 
		  if ($last[0]!='0') {
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
		  <td colspan="4"><?php echo $msg_open10; ?></td>
		 </tr> 
		 <?php
		 }
		 ?>
        </tbody>
       </table>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
       <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=accountman')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
      </div>
	  <?php
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

</div>