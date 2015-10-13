<?php if (!defined('PARENT')) { exit; }
// Filters..
include(PATH.'templates/system/tickets/global/order-by.php');
include(PATH.'templates/system/tickets/global/filter-by.php');
$userAssign   = array();
$q_users      = mysql_query("SELECT * FROM `".DB_PREFIX."users` WHERE `notify` = 'yes' ORDER BY `name`")
                or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
while ($USERS = mysql_fetch_object($q_users)) {
  $userAssign[$USERS->id] = mswCleanData($USERS->name);
}

$q            = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
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
                AND `assignedto`     = 'waiting'
                AND `spamFlag`       = 'no'
                ".$filterBy.mswSQLDepartmentFilter($ticketFilterAccess)."
                ".$orderBy."
				        LIMIT $limitvalue,$limit
                ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows  =  (isset($c->rows) ? $c->rows : '0');
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
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
    <h1 class="page-title"><?php echo $msg_adheader32; ?> (<?php echo @number_format($countedRows); ?>)</h1>

	<span class="clearfix"></span>

  </div>

  <ul class="breadcrumb">
    <li><?php echo $msg_adheader41; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader32; ?></li>
  </ul>

  <?php
  // Delete..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_open21);
  }
  // Assign..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_assign4);
  }
  ?>

  <form method="post" id="form" action="?p=<?php echo $_GET['p']; ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">

	<div class="row-fluid" style="margin-top:15px">
	  <div class="well" style="margin-bottom:10px">
       <table class="table table-striped table-hover">
        <thead>
         <tr>
		  <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <th style="width:5%">
		   <input type="checkbox" onclick="checkBoxes(this.checked,'.well .checkboxArea');ms_checkCount('assign','delButton','mc_countVal');ms_checkCount('assign','assignButton','mc_countVal2');">
		  </th>
		  <?php
		  } else {
		  ?>
          <th style="width:5%">
		   <input type="checkbox" onclick="checkBoxes(this.checked,'.well .checkboxArea');ms_checkCount('assign','assignButton','mc_countVal');">
		  </th>
		  <?php
		  }
		  ?>
          <th style="width:12%">ID</th>
		  <th style="width:43%"><?php echo $msg_viewticket25; ?></th>
		  <th style="width:20%"><?php echo $msg_open36; ?></th>
          <th style="width:20%"><?php echo $msg_assign3; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if (mysql_num_rows($q)>0) {
         while ($TICKETS = mysql_fetch_object($q)) {
		 ?>
         <tr>
		  <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td class="checkboxArea"><input onclick="ms_checkCount('assign','delButton','mc_countVal');ms_checkCount('assign','assignButton','mc_countVal')" type="checkbox" name="id[]" value="<?php echo $TICKETS->ticketID; ?>" id="tickets_<?php echo $TICKETS->ticketID; ?>"></td>
          <?php
		  } else {
		  ?>
          <td class="checkboxArea"><input onclick="ms_checkCount('assign','assignButton','mc_countVal')" type="checkbox" name="id[]" value="<?php echo $TICKETS->ticketID; ?>" id="tickets_<?php echo $TICKETS->ticketID; ?>"></td>
          <?php
		  }
		  ?>
          <td><a href="?p=view-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>" title="<?php echo mswSpecialChars($msg_viewticket11); ?>"><?php echo mswTicketNumber($TICKETS->ticketID); ?></a>
		  <span class="ticketPriority"><?php echo mswCleanData($TICKETS->levelName); ?></span>
		  </td>
          <td onmouseover="jQuery('#icon_panel_<?php echo $TICKETS->ticketID; ?>').show()" onmouseout="jQuery('#icon_panel_<?php echo $TICKETS->ticketID; ?>').hide()"><?php echo mswSpecialChars($TICKETS->subject); ?>
		  <span class="tdCellInfo"><span class="tIcons" id="icon_panel_<?php echo $TICKETS->ticketID; ?>"><a href="?p=edit-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>" title="<?php echo mswSpecialChars($msg_viewticket120); ?>"><i class="icon-pencil"></i></a>&nbsp;&nbsp;&nbsp;<a href="?p=view-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>&amp;editNotes=yes" title="<?php echo mswSpecialChars($msg_viewticket72); ?>" class="nyroModal"><i class="icon-file-text"></i></a></span><i class="icon-file-alt"></i> <?php echo $MSYS->department($TICKETS->department,$msg_script30); ?></span>
		  </td>
		  <td><?php echo mswSpecialChars($TICKETS->ticketName); ?>
		  <span class="ticketDate"><?php echo $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->dateformat); ?> @ <?php echo $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->timeformat); ?></span>
		  </td>
		  <td class="ms-options-links">
		  <div class="assignToTD">
		   <?php
           if (!empty($userAssign)) {
           foreach ($userAssign AS $uI => $uN) {
           ?>
           <label class="checkbox">
		    <input type="checkbox" name="users[<?php echo $TICKETS->ticketID; ?>][]" value="<?php echo $uI; ?>"> <?php echo $uN; ?>
		   </label>
           <?php
           }
           }
           ?>
		  </div>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="5"><?php echo $msg_open10; ?></td>
		 </tr>
		 <?php
		 }
		 ?>
        </tbody>
       </table>
	   <?php
	   if ($countedRows>0) {
	   ?>
	   <div class="divSpacer">
	     <input type="hidden" name="process" value="1">
         <label class="checkbox">
		  <input type="checkbox" name="mail" value="yes" checked="checked"> <?php echo $msg_assign5; ?>
		 </label>
	   </div>
	   <?php
	   }
	   ?>
      </div>
	  <?php
	  if ($countedRows>0) {
	  ?>
	  <div class="btn-toolbar">
        <?php
	    if (USER_DEL_PRIV=='yes') {
		?>
        <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','delete');return false;" class="btn btn-danger" disabled="disabled" type="submit" id="delButton"><i class="icon-trash"></i> <?php echo mswCleanData($msg_open15); ?> <span id="mc_countVal">(0)</span></button>
		<?php
		}
		?>
		<button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','assign');return false;" class="btn btn-primary" type="submit" id="assignButton" disabled="disabled"><i class="icon-group"></i> <?php echo mswCleanData($msg_assign6); ?> <span id="mc_countVal2">(0)</span></button>
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