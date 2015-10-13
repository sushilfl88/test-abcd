<?php if (!defined('PARENT')) { exit; } 
$filters       = array();
$searchParams  = '';
$s             = '';
$countedRows   = 0;
$area          = (empty($_GET['area']) ? array('tickets','disputes') : $_GET['area']);
include(PATH.'templates/system/tickets/global/order-by.php');
if (isset($_GET['keys'])) {
  // Filters..
  if ($_GET['keys']) {
    $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
    // Hash will cause search to fail for ticket number, so lets remove it..
    if (substr($_GET['keys'],0,1)=='#') {
      $_GET['keys'] = substr($_GET['keys'],1);
    }
    $filters[0] = ((int)$_GET['keys']>0 ? "`".DB_PREFIX."tickets`.`id` = '".mswReverseTicketNumber($_GET['keys'])."'" : "LOWER(`".DB_PREFIX."portal`.`name`) LIKE '%".$_GET['keys']."%' OR LOWER(`".DB_PREFIX."tickets`.`subject`) LIKE '%".$_GET['keys']."%' OR LOWER(`".DB_PREFIX."tickets`.`ticketNotes`) LIKE '%".$_GET['keys']."%' OR LOWER(`email`) LIKE '%".$_GET['keys']."%' OR LOWER(`comments`) LIKE '%".$_GET['keys']."%'");
    // Are we also searching responses?
    if (isset($_GET['responses']) && !is_numeric($_GET['keys'])) {
      $ticketIDs = array();
	  $q         = mysql_query("SELECT `ticketID` FROM `".DB_PREFIX."replies`
	               WHERE LOWER(`comments`) LIKE '%".$_GET['keys']."%'
		           GROUP BY `ticketID`
		           ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
      while ($RP = mysql_fetch_object($q)) {
	    $ticketIDs[] = $RP->ticketID;
	  }
	  if (!empty($ticketIDs)) {
	    $filters[0] = $filters[0].' OR `'.DB_PREFIX.'tickets`.`id` IN('.implode(',',$ticketIDs).')';
	  }
    }
  }
  if (isset($_GET['priority']) && in_array($_GET['priority'],$levelPrKeys)) {
    $filters[]  = "`priority` = '{$_GET['priority']}'";
  }
  if (isset($_GET['dept']) && $_GET['dept']!=0 && $_GET['dept']>0) {
    $filters[] = "`department` = '{$_GET['dept']}'";
  }
  if (isset($_GET['assign'])) {
    if ($_GET['assign']!=0 && $_GET['assign']>0) {
      $filters[] = "FIND_IN_SET('{$_GET['assign']}',`assignedto`) > 0";
    }
  }
  if (isset($_GET['status']) && in_array($_GET['status'],array('close','open','closed'))) {
    $filters[] = "`ticketStatus` = '{$_GET['status']}'";
  }
  if (isset($_GET['from'],$_GET['to']) && $_GET['from'] && $_GET['to']) {
    $from  = $MSDT->mswDatePickerFormat($_GET['from']);
    $to    = $MSDT->mswDatePickerFormat($_GET['to']);
    $filters[]     = "DATE(FROM_UNIXTIME(`ts`)) BETWEEN '{$from}' AND '{$to}'";
  }
  if (count($area)>1) {
    $filters[] = "`isDisputed` IN('yes','no')";
  } else {
    if (in_array('tickets',$area)) {
      $filters[] = "`isDisputed` = 'no'";
    } else {
      $filters[] = "`isDisputed` = 'yes'";
    }
  }
  // Build search string..
  if (!empty($filters)) {
    for ($i=0; $i<count($filters); $i++) {
      $searchParams .= ($i ? ' AND (' : 'WHERE (').$filters[$i].')';
    }
  }
  // Count for pages..
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
	   ON `".DB_PREFIX."tickets`.`visitorID` = `".DB_PREFIX."portal`.`id`
	   LEFT JOIN `".DB_PREFIX."levels`
	   ON `".DB_PREFIX."tickets`.`priority`   = `".DB_PREFIX."levels`.`id`
	    OR `".DB_PREFIX."tickets`.`priority`  = `".DB_PREFIX."levels`.`marker`
       ".($searchParams ? $searchParams.' AND `spamFlag` = \'no\' '.mswSQLDepartmentFilter($ticketFilterAccess) : 'WHERE `spamFlag` = \'no\'')."
       ".$orderBy."
       LIMIT $limitvalue,$limit
       ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
  $countedRows  =  (isset($c->rows) ? $c->rows : '0');
}
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  <?php
  include(PATH.'templates/date-pickers.php');
  ?>
  //]]>
  </script>
  
  <div class="header">
    
	<?php
	if (isset($_GET['keys']) && isset($q)) {
	include(PATH.'templates/system/tickets/global/order-filter.php');
    include(PATH.'templates/system/tickets/global/status-filter.php');
    include(PATH.'templates/system/tickets/global/dept-filter.php');
	include(PATH.'templates/system/bootstrap/page-filter.php');
    }
	
	if (isset($_GET['keys']) && isset($q)) {
	?>
    <h1 class="page-title"><?php echo $msg_search6.' ('.@number_format($countedRows).')'; ?></h1>
	<?php
	} else {
	?>
	<h1 class="page-title"><?php echo $msg_adheader7; ?></h1>
	<?php
	}
	?>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader41; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($_GET['keys']) ? $msg_search6 : $msg_adheader7); ?></li>
  </ul>
  
  <?php
  // Update..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_search16);
  }
  ?>

  <div class="container-fluid" style="margin-top:20px">
    
    <form method="get" action="index.php" style="margin:0;padding:0" onsubmit="return ms_fieldCheck('none','none')">
	<div class="row-fluid" id="searchParams"<?php echo (isset($_GET['keys']) ? ' style="display:none"' : ''); ?>>
	  <ul class="nav nav-tabs">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-search"></i> <?php echo $msg_search; ?></a></li>
       <li><a href="#two" data-toggle="tab"><i class="icon-calendar"></i> <?php echo $msg_search19; ?></a></li>
       <li><a href="#three" data-toggle="tab"><i class="icon-filter"></i> <?php echo $msg_search20; ?></a></li>
      </ul>
      <div id="myTabContent" class="tab-content">
       <div class="tab-pane active in" id="one">
	    <div class="well">
		 
		 <label><?php echo $msg_search3; ?></label>
         <input type="text" class="input-xlarge" name="keys" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($_GET['keys']) ? mswSpecialChars($_GET['keys']) : ''); ?>">
         
		 <?php
		 if ($SETTINGS->disputes=='yes') {
         if (in_array('open',$userAccess) || in_array('close',$userAccess) || $MSTEAM->id=='1') {
         ?>
		 <label class="checkbox">
          <input type="checkbox" name="area[]" value="tickets"<?php echo (!empty($_GET['area']) && in_array('tickets',$_GET['area']) ? ' checked="checked"' : (empty($_GET['area']) && SEARCH_AUTO_CHECK_TICKETS=='yes' ? ' checked="checked"' : '')); ?>> <?php echo $msg_search12; ?>
         </label>
		 <?php
         }
		 if (in_array('disputes',$userAccess) || in_array('cdisputes',$userAccess) || $MSTEAM->id=='1') {
         ?>
		 <label class="checkbox">
         <input type="checkbox" name="area[]" value="disputes"<?php echo (!empty($_GET['area']) && in_array('disputes',$_GET['area']) ? ' checked="checked"' : (empty($_GET['area']) && SEARCH_AUTO_CHECK_DISPUTES=='yes' ? ' checked="checked"' : '')); ?>> <?php echo $msg_search13; ?>
         </label>
		 <?php
         }
		 }
         ?>
		 <label class="checkbox">
         <input type="checkbox" name="responses" value="yes"<?php echo (isset($_GET['responses']) ? ' checked="checked"' : (!isset($_GET['responses']) && SEARCH_AUTO_CHECK_RESPONSES=='yes' ? ' checked="checked"' : '')); ?>> <?php echo $msg_search23; ?>
         </label>
		 
		</div>
       </div>
       <div class="tab-pane fade" id="two">
	    <div class="well">
		 
		 <label><?php echo $msg_search7; ?></label>
         <input type="text" class="input-small" id="from" tabindex="<?php echo (++$tabIndex); ?>" name="from" value="<?php echo (isset($_GET['from']) ? mswSpecialChars($_GET['from']) : ''); ?>">
         <input type="text" class="input-small" id="to" tabindex="<?php echo (++$tabIndex); ?>" name="to" value="<?php echo (isset($_GET['to']) ? mswSpecialChars($_GET['to']) : ''); ?>">
		 
		</div>
       </div>
	   <div class="tab-pane fade" id="three">
	    <div class="well">
		 
		 <label><?php echo $msg_search4; ?></label>
         <select name="dept" tabindex="<?php echo (++$tabIndex); ?>">
         <option value="0">- - - - -</option>
         <?php
         $q_dept = mysql_query("SELECT * FROM ".DB_PREFIX."departments ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                   or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($DEPT = mysql_fetch_object($q_dept)) {
         ?>
         <option value="<?php echo $DEPT->id; ?>"<?php echo mswSelectedItem('dept',$DEPT->id,true); ?>><?php echo mswSpecialChars($DEPT->name); ?></option>
         <?php
         }
         ?>
         </select>
         
		 <label><?php echo $msg_search5; ?></label>
         <select name="priority" tabindex="<?php echo (++$tabIndex); ?>">
         <option value="0">- - - - -</option>
         <?php
         foreach ($ticketLevelSel AS $k => $v) {
         ?>
         <option value="<?php echo $k; ?>"<?php echo mswSelectedItem('priority',$k,true); ?>><?php echo $v; ?></option>
         <?php
         }
         ?>
         </select>
		 
		 <label><?php echo $msg_search8; ?></label>
         <select name="status" tabindex="<?php echo (++$tabIndex); ?>">
         <option value="0">- - - - -</option>
         <option value="open"<?php echo mswSelectedItem('status','open',true); ?>><?php echo $msg_viewticket14; ?></option>
         <option value="close"<?php echo mswSelectedItem('status','close',true); ?>><?php echo $msg_viewticket15; ?></option>
         <option value="closed"<?php echo mswSelectedItem('status','closed',true); ?>><?php echo $msg_viewticket16; ?></option>
         </select>
         
		 <label><?php echo $msg_open31; ?></label>
         <select name="assign" tabindex="<?php echo (++$tabIndex); ?>">
         <option value="0">- - - - -</option>
         <?php
         $q_users  = mysql_query("SELECT * FROM ".DB_PREFIX."users ORDER BY `name`") 
                     or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($U = mysql_fetch_object($q_users)) {
         ?>
         <option value="<?php echo $U->id; ?>"<?php echo mswSelectedItem('assign',$U->id,true); ?>><?php echo mswCleanData($U->name); ?></option>
         <?php
         }
         ?>
         </select>
		
		</div>
       </div>
      </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>">
	   <?php
	   if ($SETTINGS->disputes=='no') {
	   ?>
	   <input type="hidden" name="area[]" value="tickets">
	   <?php
	   }
	   ?>
       <button class="btn btn-primary" type="submit"><i class="icon-ok"></i> <?php echo mswCleanData($msg_search2); ?></button>
      </div>
	  <?php
	  // Footer links..
	  if (!isset($_GET['keys'])) {
	  include(PATH.'templates/footer-links.php');
	  }
	  ?>
    </div>
    </form>
	
	<?php
	// Show search results
	if (isset($_GET['keys']) && isset($q)) {
	?>
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
	<form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>">
	<div class="row-fluid" id="searchResults">
	
	  <div class="btn-toolbar">
       <button class="btn btn-primary" type="button" onclick="ms_divHideShow('searchParams','searchResults');jQuery('.header .btn-group').hide()"><i class="icon-repeat"></i> <?php echo $msg_search21; ?></button>
	   <button class="btn" type="button" onclick="ms_windowLoc('?p=search')"><i class="icon-minus-sign"></i> <?php echo $msg_search22; ?></button>
      </div>
	
	  <div class="well" style="margin-bottom:10px;padding-bottom:10px">
       <table class="table table-striped table-hover">
        <thead>
         <tr>
          <th style="width:5%">
		   <input type="checkbox" onclick="checkBoxes(this.checked,'.well');ms_checkCount('well','upButton','mc_countVal');ms_checkCount('well','upButton2','mc_countVal2')">
		  </th>
		  <th style="width:12%">ID / <?php echo $msg_showticket16; ?></th>
		  <th style="width:43%"><?php echo $msg_viewticket25; ?></th>
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
		  <td><input onclick="ms_checkCount('well','upButton','mc_countVal');ms_checkCount('well','upButton2','mc_countVal2')" type="checkbox" name="id[]" value="<?php echo $TICKETS->ticketID; ?>" id="tickets_<?php echo $TICKETS->ticketID; ?>"></td>
          <td><a href="?p=view-<?php echo ($TICKETS->isDisputed ? 'dispute' : 'ticket'); ?>&amp;id=<?php echo $TICKETS->ticketID; ?>" title="<?php echo mswSpecialChars($msg_user29); ?>"><?php echo mswTicketNumber($TICKETS->ticketID); ?></a>
		  <span class="ticketPriority"><?php echo mswCleanData($TICKETS->levelName); ?></span>
		  </td>
          <td onmouseover="jQuery('#icon_panel_<?php echo $TICKETS->ticketID; ?>').show()" onmouseout="jQuery('#icon_panel_<?php echo $TICKETS->ticketID; ?>').hide()"><?php echo mswSpecialChars($TICKETS->subject);
		  if ($TICKETS->isDisputed=='yes') {
		  ?>
		  <span class="tdCellInfoDispute">
		  <span class="tIcons" id="icon_panel_<?php echo $TICKETS->ticketID; ?>"><a href="?p=edit-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>" title="<?php echo mswSpecialChars($msg_viewticket120); ?>"><i class="icon-pencil"></i></a>&nbsp;&nbsp;&nbsp;<a href="?p=view-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>&amp;editNotes=yes" title="<?php echo mswSpecialChars($msg_viewticket72); ?>" class="nyroModal"><i class="icon-file-text"></i></a></span>
		  <i class="icon-file-alt"></i> <?php echo $MSYS->department($TICKETS->department,$msg_script30); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  <i class="icon-bullhorn"></i> <?php echo str_replace('{count}',($TICKETS->disputeCount+1),$msg_showticket30); ?>
		  </span>
		  <?php
		  } else {
		  ?>
		  <span class="tdCellInfo"><span class="tIcons" id="icon_panel_<?php echo $TICKETS->ticketID; ?>"><a href="?p=edit-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>" title="<?php echo mswSpecialChars($msg_viewticket120); ?>"><i class="icon-pencil"></i></a>&nbsp;&nbsp;&nbsp;<a href="?p=view-ticket&amp;id=<?php echo $TICKETS->ticketID; ?>&amp;editNotes=yes" title="<?php echo mswSpecialChars($msg_viewticket72); ?>" class="nyroModal"><i class="icon-file-text"></i></a></span><i class="icon-file-alt"></i> <?php echo $MSYS->department($TICKETS->department,$msg_script30); ?></span>
		  <?php
		  }
		  ?>
		  </td>
		  <td><?php echo mswSpecialChars($TICKETS->ticketName); ?>
		  <span class="ticketDate"><?php echo $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->dateformat); ?> @ <?php echo $MSDT->mswDateTimeDisplay($TICKETS->ticketStamp,$SETTINGS->timeformat); ?></span>
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
		  <td colspan="6"><?php echo $msg_open10; ?></td>
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
       <select name="department" tabindex="<?php echo (++$tabIndex); ?>">
        <option value="no-change"><?php echo $msg_search15; ?></option>
        <?php
        $q_dept = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                  or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        while ($DEPT = mysql_fetch_object($q_dept)) {
        ?>
        <option value="<?php echo $DEPT->id; ?>"><?php echo mswCleanData($DEPT->name); ?></option>
        <?php
        }
        ?>
       </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	   <select name="priority" tabindex="<?php echo (++$tabIndex); ?>">
        <option value="no-change"><?php echo $msg_search17; ?></option>
        <?php
        foreach ($ticketLevelSel AS $k => $v) {
        ?>
        <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
        <?php
        }
        ?>
       </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	   <select name="status">
        <option value="no-change"><?php echo $msg_search18; ?></option>
        <option value="open"><?php echo $msg_viewticket14; ?></option>
        <option value="close"><?php echo $msg_viewticket15; ?></option>
        <option value="closed"><?php echo $msg_viewticket16; ?></option>
       </select>
	  </div>
	  <?php
	  }
	  ?>
      </div>
      
	  <?php
	  if ($countedRows>0) {
	  ?>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0"> 
	   <input type="hidden" name="process" value="yes">
       <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','update');return false;" class="btn btn-primary" type="submit" id="upButton" disabled="disabled"><i class="icon-ok"></i> <?php echo mswCleanData($msg_search14); ?> <span id="mc_countVal">(0)</span></button>
       <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','export-search');return false;" class="btn btn-primary" type="submit" id="upButton2" disabled="disabled"><i class="icon-save"></i> <?php echo mswCleanData($msg_search25); ?> <span id="mc_countVal2">(0)</span></button>
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
	</form>
	<?php
	}
	?>
  </div>

</div>