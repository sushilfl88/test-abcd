<?php if (!defined('PARENT')) { exit; }
$_GET['id']   = (int)$_GET['id'];
$SQL          = '';
$q            = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                `".DB_PREFIX."replies`.`id` AS `repid`,
				`".DB_PREFIX."replies`.`ts` AS `repStamp`,
				`".DB_PREFIX."replies`.`comments` AS `repcomms`,
				`".DB_PREFIX."tickets`.`id` AS `tickID`
                FROM `".DB_PREFIX."replies`
                LEFT JOIN `".DB_PREFIX."tickets`
                ON `".DB_PREFIX."replies`.`ticketID` = `".DB_PREFIX."tickets`.`id`
                WHERE `replyType` = 'admin'
                AND `replyUser`   = '{$_GET['id']}'
				AND `spamFlag`    = 'no'
                GROUP BY `".DB_PREFIX."replies`.`id`,`".DB_PREFIX."replies`.`ticketID`
                ORDER BY `".DB_PREFIX."replies`.`id` DESC
                LIMIT $limitvalue,$limit
                ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows  = (isset($c->rows) ? $c->rows : '0');
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  <?php
  include(PATH.'templates/date-pickers.php');
  ?>
  function searchToggle() {
    jQuery('#b1').toggle();
	if (jQuery('#b1').css('display')!='none') {
	  jQuery('input[name="q"]').focus();
      jQuery('#search-icon-button').attr('class','icon-remove');
	} else {
	  jQuery('#search-icon-button').attr('class','icon-search');
	}
  }
  function searchLog() {
    var from = jQuery('input[name="from"]').val();
	var to   = jQuery('input[name="to"]').val();
	var q    = jQuery('input[name="q"]').val();
	if (from=='' && to=='' && q=='') {
	  jQuery('input[name="q"]').focus();
	  return false;
	} else {
	  if (from!='' && to=='') {
	    jQuery('input[name="to"]').focus();
	  } else {
	    if (from=='' && to!='') {
		  jQuery('input[name="from"]').focus();
		}
	  }
	}
	jQuery('#form').submit();
  }
  //]]>
  </script>
  <div class="header">
  
    <?php
	// Page filter..
	if ($countedRows>0) {
	include(PATH.'templates/system/bootstrap/page-filter.php');
	?>
	<button class="btn search-bar-button" type="button" onclick="searchToggle()"><i class="icon-search" id="search-icon-button"></i></button>
	<?php
	}
	?>
	<h1 class="page-title"><?php echo $msg_user87; ?> (<?php echo number_format($countedRows); ?>)</h1>
	 
	<span class="clearfix"></span>
  
  </div>
  
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader4; ?> <span class="divider">/</span></li>
    <li><?php echo $msg_user87; ?> <span class="divider">/</span></li>
	<li class="active"><?php echo mswSpecialChars($U->name); ?></li>
  </ul>
  
  <form method="get" id="form" action="index.php?p=<?php echo $_GET['p']; ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar" id="b1" style="margin-top:0;padding-top:0;display:none">
       <input type="hidden" name="p" value="responses"><input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"><input placeholder="<?php echo mswSpecialChars($msg_log10); ?>" type="text" class="input-small" name="q" value="<?php echo (isset($_GET['q']) ? mswSpecialChars($_GET['q']) : ''); ?>" style="margin-right:1px">
       <input type="text" placeholder="<?php echo mswSpecialChars($msg_reports2); ?>" class="input-small" id="from" name="from" value="<?php echo (isset($_GET['from']) ? mswSpecialChars($_GET['from']) : ''); ?>" style="margin-right:1px">
       <div class="input-append">
        <input placeholder="<?php echo mswSpecialChars($msg_reports3); ?>" type="text" class="input-small" id="to" name="to" value="<?php echo (isset($_GET['to']) ? mswSpecialChars($_GET['to']) : ''); ?>">
        <button type="button" class="btn btn-info" onclick="searchLog()"><i class="icon-search"></i></button>
       </div>
      </div>
	
	  <div class="well" style="margin-bottom:10px;padding-bottom:0">
       <table class="table table-striped table-hover">
        <thead>
         <tr>
          <th style="width:85%"><?php echo $msg_response12; ?></th>
          <th style="width:15%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if (mysql_num_rows($q)>0) {
		 while ($REPLY = mysql_fetch_object($q)) {
		 ?>
         <tr>
		  <td>
		  <?php echo substr(mswCleanData($MSBB->cleaner($REPLY->repcomms)),0,250); ?>..
		  <span class="tdCellInfo">
		   <?php echo $msg_user89; ?>: <span class="highlight"><?php echo date($SETTINGS->dateformat,$REPLY->repStamp); ?></span>
		  </span>
		  </td>
          <td class="ms-options-links">
		    <a href="?p=edit-reply&amp;id=<?php echo $REPLY->repid; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
			<a href="?p=view-ticket&amp;id=<?php echo $REPLY->tickID; ?>" title="<?php echo mswSpecialChars($msg_open7); ?>"><i class="icon-search"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="2"><?php echo $msg_user22; ?></td>
		 </tr> 
		 <?php
		 }
		 ?>
        </tbody>
       </table>
      </div>
	  
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=teamman')"><i class="icon-remove"></i> <?php echo $msg_levels11; ?></button>
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
  </form>

</div>