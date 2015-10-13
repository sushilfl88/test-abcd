<?php if (!defined('PARENT')) { exit; } 
// Vars..
$from  = (isset($_GET['from']) && $MSDT->mswDatePickerFormat($_GET['from'])!='0000-00-00' ? $_GET['from'] : $MSDT->mswConvertMySQLDate(date('Y-m-d',strtotime('-6 months',$MSDT->mswTimeStamp()))));
$to    = (isset($_GET['to']) && $MSDT->mswDatePickerFormat($_GET['to'])!='0000-00-00' ? $_GET['to'] : $MSDT->mswConvertMySQLDate(date('Y-m-d',$MSDT->mswTimeStamp())));
$view  = (isset($_GET['view']) && in_array($_GET['view'],array('month','day')) ? $_GET['view'] : 'month');
$dept  = (isset($_GET['dept']) ? $_GET['dept'] : '0');
$cns   = array(0,0,0,0);
$where = 'WHERE DATE(FROM_UNIXTIME(`ts`)) BETWEEN \''.$MSDT->mswDatePickerFormat($from).'\' AND \''.$MSDT->mswDatePickerFormat($to).'\'';
if (substr($dept,0,1)=='u') {
  $where .= mswDefineNewline().'AND FIND_IN_SET(\''.substr($dept,1).'\',`assignedto`) > 0';
} else {
  if ($dept>0) {
    $where .= mswDefineNewline().'AND `department` = \''.$dept.'\'';
  }
}
$where .= mswDefineNewline().'AND `assignedto` != \'waiting\'';
switch ($view) {
  case 'month':
  $q = mysql_query("SELECT *,MONTH(FROM_UNIXTIME(`ts`)) AS `m`,YEAR(FROM_UNIXTIME(`ts`)) AS `y` FROM `".DB_PREFIX."tickets` 
       $where
	   AND `spamFlag` = 'no'
       GROUP BY MONTH(FROM_UNIXTIME(`ts`)),YEAR(FROM_UNIXTIME(`ts`))
       ORDER BY 2
       ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $ticketNumRows = mysql_num_rows($q);
  break;
  case 'day':
  $q = mysql_query("SELECT *,DATE(FROM_UNIXTIME(`ts`)) AS `d` FROM `".DB_PREFIX."tickets` 
       $where
	   AND `spamFlag` = 'no'
       GROUP BY DATE(FROM_UNIXTIME(`ts`))
       ORDER BY 2
       ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $ticketNumRows = mysql_num_rows($q);
  break;
}
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
	  jQuery('#search-icon-button').attr('class','icon-remove');
	} else {
	  jQuery('#search-icon-button').attr('class','icon-search');
	}
  }
  //]]>
  </script>

  <div class="header">
  
    <button class="btn search-bar-button" type="button" onclick="searchToggle()"><i class="<?php echo (!isset($_GET['dept']) ? 'icon-search' : 'icon-remove'); ?>" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $msg_adheader34; ?></h1>
	 
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader37; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader34; ?></li>
  </ul>

  <form method="get" action="index.php" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="btn-toolbar" id="b1" style="margin-top:0;padding-top:0<?php echo (!isset($_GET['dept']) ? ';display:none' : ''); ?>">
	 <select name="dept" class="span2 noright-borders" style="margin-right:1px">
     <option value="0"><?php echo $msg_tools10; ?></option>
     <?php
     $q_dept = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
               or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
     while ($DEPT = mysql_fetch_object($q_dept)) {
     ?>
     <option value="<?php echo $DEPT->id; ?>"<?php echo mswSelectedItem('dept',$DEPT->id,true); ?>><?php echo mswSpecialChars($DEPT->name); ?></option>
     <?php
     }
     // For administrator, show all assigned users in filter..
     if ($MSTEAM->id=='1') {
     $q_users     = mysql_query("SELECT * FROM `".DB_PREFIX."users` ORDER BY `name`") 
                    or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	 if (mysql_num_rows($q_users)>0) {
	 ?>
	 <option value="0" disabled="disabled">- - - - - -</option>
	 <?php
     while ($U = mysql_fetch_object($q_users)) {
     ?>
     <option value="u<?php echo $U->id; ?>"<?php echo mswSelectedItem('dept','u'.$U->id,true); ?>><?php echo $msg_open31.' '.mswSpecialChars($U->name); ?></option>
     <?php
     }
	 }
     }
     ?>
     </select>
	 <input type="hidden" name="p" value="reports"><input type="text" placeholder="<?php echo mswSpecialChars($msg_reports2); ?>" class="input-small" id="from" name="from" value="<?php echo mswSpecialChars($from); ?>" style="margin-right:1px">
     <input type="text" placeholder="<?php echo mswSpecialChars($msg_reports3); ?>" class="input-small" id="to" name="to" value="<?php echo mswSpecialChars($to); ?>" style="margin-right:1px">
     <div class="input-append">
	  <select name="view" class="span1 noleft-borders" style="width:100px">
	   <option value="day"<?php echo mswSelectedItem('view','day',true); ?>><?php echo $msg_reports4; ?></option>
	   <option value="month"<?php echo mswSelectedItem('view','month',true); ?>><?php echo $msg_reports5; ?></option>
	  </select>
	  <button type="submit" class="btn btn-info"><i class="icon-search"></i></button>
	 </div> 
	</div>
	
	<div class="row-fluid">
	
	  <div class="well" style="margin-bottom:10px;padding-bottom:0">
       <table class="table table-striped table-hover">
        <thead>
         <tr>
          <th style="width:32%"><?php echo $msg_reports7; ?></th>
		  <th style="width:<?php echo ($SETTINGS->disputes=='yes' ? '17' : '34'); ?>%"><?php echo $msg_reports8; ?></th>
          <th style="width:<?php echo ($SETTINGS->disputes=='yes' ? '17' : '34'); ?>%"><?php echo $msg_reports9; ?></th>
		  <?php
		  if ($SETTINGS->disputes=='yes') {
		  ?>
          <th style="width:17%"><?php echo $msg_reports10; ?></th>
          <th style="width:17%"><?php echo $msg_reports11; ?></th>
		  <?php
		  }
		  ?>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if ($ticketNumRows>0) {
         while ($REP = mysql_fetch_object($q)) {
		 switch ($view) {
           case 'month':
           // Open tickets..
           $C1 = mysql_fetch_object(
                  mysql_query("SELECT COUNT(*) AS `c` FROM `".DB_PREFIX."tickets` 
                  $where
                  AND `ticketStatus`             = 'open'
                  AND `isDisputed`               = 'no'
                  AND MONTH(FROM_UNIXTIME(`ts`)) = '{$REP->m}'
                  AND YEAR(FROM_UNIXTIME(`ts`))  = '{$REP->y}'
                  ")
                 );
           // Closed tickets..      
           $C2 = mysql_fetch_object(
                  mysql_query("SELECT COUNT(*) AS `c` FROM `".DB_PREFIX."tickets` 
                  $where
                  AND `ticketStatus`             = 'close'
                  AND `isDisputed`               = 'no'
                  AND MONTH(FROM_UNIXTIME(`ts`)) = '{$REP->m}'
                  AND YEAR(FROM_UNIXTIME(`ts`))  = '{$REP->y}'
                  ")
                 );      
		   if ($SETTINGS->disputes=='yes') {		 
           // Open disputes..
           $C3 = mysql_fetch_object(
                  mysql_query("SELECT COUNT(*) AS `c` FROM `".DB_PREFIX."tickets` 
                  $where
                  AND `ticketStatus`             = 'open'
                  AND `isDisputed`               = 'yes'
                  AND MONTH(FROM_UNIXTIME(`ts`)) = '{$REP->m}'
                  AND YEAR(FROM_UNIXTIME(`ts`))  = '{$REP->y}'
                  ")
                 );
           // Closed disputes..      
           $C4 = mysql_fetch_object(
                  mysql_query("SELECT COUNT(*) AS `c` FROM `".DB_PREFIX."tickets` 
                  $where
                  AND `ticketStatus`             = 'close'
                  AND `isDisputed`               = 'yes'
                  AND MONTH(FROM_UNIXTIME(`ts`)) = '{$REP->m}'
                  AND YEAR(FROM_UNIXTIME(`ts`))  = '{$REP->y}'
                  ")
                 ); 
           }
		   break;
		   case 'day':
		   // Open tickets..
		   $C1 = mysql_fetch_object(
				  mysql_query("SELECT COUNT(*) AS `c` FROM `".DB_PREFIX."tickets` 
				  $where
				  AND `ticketStatus`             = 'open'
				  AND `isDisputed`               = 'no'
				  AND DATE(FROM_UNIXTIME(`ts`))  = '{$REP->d}'
				  ")
				 );
		   // Closed tickets..      
		   $C2 = mysql_fetch_object(
				  mysql_query("SELECT COUNT(*) AS `c` FROM `".DB_PREFIX."tickets` 
				  $where
				  AND `ticketStatus`             = 'close'
				  AND `isDisputed`               = 'no'
				  AND DATE(FROM_UNIXTIME(`ts`))  = '{$REP->d}'
				  ")
				 );      
		   if ($SETTINGS->disputes=='yes') {
		   // Open disputes..
		   $C3 = mysql_fetch_object(
				  mysql_query("SELECT COUNT(*) AS `c` FROM `".DB_PREFIX."tickets` 
				  $where
				  AND `ticketStatus`             = 'open'
				  AND `isDisputed`               = 'yes'
				  AND DATE(FROM_UNIXTIME(`ts`))  = '{$REP->d}'
				  ")
				 );
		   // Closed disputes..      
		   $C4 = mysql_fetch_object(
				  mysql_query("SELECT COUNT(*) AS `c` FROM `".DB_PREFIX."tickets` 
				  $where
				  AND `ticketStatus`             = 'close'
				  AND `isDisputed`               = 'yes'
				  AND DATE(FROM_UNIXTIME(`ts`))  = '{$REP->d}'
				  ")
				 ); 
		   }
		   break;
		 }  
		 $cnt1 = (isset($C1->c) ? $C1->c : '0');
		 $cnt2 = (isset($C2->c) ? $C2->c : '0');
		 $cnt3 = (isset($C3->c) ? $C3->c : '0');
		 $cnt4 = (isset($C4->c) ? $C4->c : '0');
		 ?>
         <tr>
          <td><?php echo ($view=='day' ? date($SETTINGS->dateformat,strtotime($REP->d)) : $msg_script21[($REP->m-1)].' '.$REP->y); ?></td>
		  <td><?php echo number_format($cnt1); ?></td>
          <td><?php echo number_format($cnt2); ?></td>
		  <?php
		  if ($SETTINGS->disputes=='yes') {
		  ?>
          <td><?php echo number_format($cnt3); ?></td>
		  <td><?php echo number_format($cnt4); ?></td>
		  <?php
		  }
		  ?>
         </tr>
		 <?php
		 // Totals..
		 $cns[0] = ($cns[0]+$cnt1);
         $cns[1] = ($cns[1]+$cnt2);
         $cns[2] = ($cns[2]+$cnt3);
         $cns[3] = ($cns[3]+$cnt4);
		 }
		 if ($ticketNumRows>0) {
		 ?>
		 <tr class="warning boldArea">
          <td><?php echo $msg_reports12; ?></td>
		  <td><?php echo number_format($cns[0]); ?></td>
          <td><?php echo number_format($cns[1]); ?></td>
		  <?php
		  if ($SETTINGS->disputes=='yes') {
		  ?>
          <td><?php echo number_format($cns[2]); ?></td>
		  <td><?php echo number_format($cns[3]); ?></td>
		  <?php
		  }
		  ?>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo ($SETTINGS->disputes=='yes' ? '5' : '3'); ?>"><?php echo $msg_reports13; ?></td>
		 </tr> 
		 <?php
		 }
		 ?>
        </tbody>
       </table>
      </div>
	  <?php
	  if ($ticketNumRows>0) {
	  $url = 'index.php?p=reports&amp;ex=yes&amp;from='.$from.'&amp;to='.$to.'&amp;view='.$view.'&amp;dept='.$dept;
	  ?>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('<?php echo $url; ?>')"><i class="icon-save"></i> <?php echo mswCleanData($msg_reports14); ?></button>
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