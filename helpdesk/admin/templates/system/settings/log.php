<?php if (!defined('PARENT')) { exit; } 
$from        = (isset($_GET['from']) && $MSDT->mswDatePickerFormat($_GET['from'])!='0000-00-00' ? $_GET['from'] : '');
$to          = (isset($_GET['to']) && $MSDT->mswDatePickerFormat($_GET['to'])!='0000-00-00' ? $_GET['to'] : '');
$type        = (isset($_GET['type']) && in_array($_GET['type'],array('user','acc')) ? $_GET['type'] : '');
$keys        = '';
$where       = array();
if (isset($_GET['q'])) {
  $chop  = explode(' ',$_GET['q']);
  $words = '';
  for ($i=0; $i<count($chop); $i++) {
    $words .= ($i ? 'OR ' : 'WHERE (')."`".DB_PREFIX."portal`.`name` LIKE '%".mswSafeImportString($chop[$i])."%' OR `".DB_PREFIX."users`.`name` LIKE '%".mswSafeImportString($chop[$i])."%' ";
  }
  if ($words) {
    $where[] = $words.')';
  }
}
if ($type) {
  $where[]  = (!empty($where) ? 'AND ' : 'WHERE ').'`type` = \''.$type.'\'';
}
if ($from && $to) {
  $where[]  = (!empty($where) ? 'AND ' : 'WHERE ').'DATE(FROM_UNIXTIME(`'.DB_PREFIX.'log`.`ts`)) BETWEEN \''.$MSDT->mswDatePickerFormat($from).'\' AND \''.$MSDT->mswDatePickerFormat($to).'\'';
}
$q           = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
               `".DB_PREFIX."log`.`ts` AS `lts`,
			   `".DB_PREFIX."log`.`id` AS `logID`,
			   `".DB_PREFIX."log`.`userID` AS `personID`,
			   `".DB_PREFIX."log`.`ip` AS `entryLogIP`,
			   `".DB_PREFIX."portal`.`name` AS `portalName`,
			   `".DB_PREFIX."users`.`name` AS `userName`
			   FROM `".DB_PREFIX."log`
               LEFT JOIN `".DB_PREFIX."users`
               ON `".DB_PREFIX."log`.`userID` = `".DB_PREFIX."users`.`id` 
			   LEFT JOIN `".DB_PREFIX."portal`
               ON `".DB_PREFIX."log`.`userID` = `".DB_PREFIX."portal`.`id` 
			   ".(!empty($where) ? implode(mswDefineNewline(),$where) : '')."
               ORDER BY `".DB_PREFIX."log`.`id` DESC
               LIMIT $limitvalue,$limit
               ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows  =  (isset($c->rows) ? $c->rows : '0');
$actualRows   = mswRowCount('log');
// Export url..
$url          = 'index.php?p=log&amp;export=yes'.mswQueryParams(array('p','export'));
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
	  jQuery('#b2').hide();
	} else {
	  jQuery('#search-icon-button').attr('class','icon-search');
	  jQuery('#b2').show();
	}
  }
  function searchLog() {
    var from = jQuery('input[name="from"]').val();
	var to   = jQuery('input[name="to"]').val();
	var keys = jQuery('input[name="q"]').val();
	if (keys=='') {
	  jQuery('input[name="q"]').focus();
	  return false;
	}
	return true;
  }
  //]]>
  </script>
  <div class="header">
    
    <?php
	// Page filter..
	if ($actualRows>0) {
	include(PATH.'templates/system/bootstrap/page-filter.php');
	// Filters..
	$links   = array(array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','type','from','to','q')),  'name' => $msg_log11));
	$links[] = array('link' => '?p='.$_GET['p'].'&amp;type=user'.mswQueryParams(array('p','type','next')),'name' => $msg_log13);
    $links[] = array('link' => '?p='.$_GET['p'].'&amp;type=acc'.mswQueryParams(array('p','type','next')),'name' => $msg_log12);
    echo $MSBOOTSTRAP->button($msg_search20,$links);
	?>
	<button class="btn search-bar-button" type="button" onclick="searchToggle()"><i class="icon-search" id="search-icon-button"></i></button>
	<?php
	}
	?>
	<h1 class="page-title"><?php echo $msg_adheader20; ?> (<?php echo @number_format($countedRows); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader37; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader20; ?></li>
  </ul>
  
  <?php
  // Delete selected..
  if (isset($_GET['deleted'])) {
    echo mswActionCompleted($msg_log9);
  }
  // Log cleared..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_log5);
  }
  ?>

  <div class="container-fluid">
    
	<div class="row-fluid" <?php echo ($countedRows==0 ? ' style="margin-top:20px"' : ''); ?>>
	
	  <?php
	  if ($actualRows>0) {
	  ?>
	  <form method="get" action="index.php" style="margin-bottom:0;padding-bottom:0" onsubmit="return searchLog()">
      <div class="btn-toolbar" id="b1" style="margin-top:10px;padding-top:0;display:none">
       <input type="hidden" name="p" value="log"><input type="text" class="input-large" name="q" value="<?php echo (isset($_GET['q']) ? mswSpecialChars($_GET['q']) : ''); ?>" placeholder="<?php echo mswSpecialChars($msg_reports15); ?>" style="margin-right:20px"><input type="text" placeholder="<?php echo mswSpecialChars($msg_reports2); ?>" class="input-small" id="from" name="from" value="<?php echo mswSpecialChars($from); ?>" style="margin-right:1px">
       <div class="input-append">
        <input placeholder="<?php echo mswSpecialChars($msg_reports3); ?>" type="text" class="input-small" id="to" name="to" value="<?php echo mswSpecialChars($to); ?>">
        <button type="submit" class="btn btn-info"><i class="icon-search"></i></button>
       </div>
      </div> 
	  </form>
	  
	  <div class="btn-toolbar" id="b2">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('<?php echo $url; ?>')"><i class="icon-save"></i> <?php echo $msg_log3; ?></button>
      </div>
	  <?php
	  }
	  ?>
	  
	  <form method="get" id="form" action="index.php" onsubmit="return ms_fieldCheck('none','none')">
	  <div class="well" style="margin-bottom:10px;padding-bottom:0">
       <table class="table table-striped table-hover">
        <thead>
         <tr>
          <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <th style="width:5%">
		  <input type="checkbox" onclick="checkBoxes(this.checked,'.well');ms_checkCount('well','delButton','mc_countVal')">
		  </th>
		  <?php
		  }
		  ?>
		  <th style="width:30%"><?php echo $msg_log; ?></th>
		  <th style="width:20%"><?php echo $msg_log16; ?></th>
		  <th style="width:20%"><?php echo $msg_log8; ?></th>
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '25' : '30'); ?>%"><?php echo $msg_log7; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if (mysql_num_rows($q)>0) {
         while ($LOG = mysql_fetch_object($q)) {
		 // IP entry..
		 $ips_html = '';
		 if (strpos($LOG->entryLogIP,',')!==false) {
		   $ips = array_map('trim',explode(',',mswCleanData($LOG->entryLogIP)));
		   foreach ($ips AS $ipA) {
		     $ips_html .= mswCleanData($ipA).' <a href="'.str_replace('{ip}',mswCleanData($ipA),IP_LOOKUP).'" onclick="window.open(this);return false"><i class="icon-external-link"></i></a><br>';
		   }
		 } else {
		   $ips_html = mswCleanData($LOG->entryLogIP).' <a href="'.str_replace('{ip}',mswCleanData($LOG->entryLogIP),IP_LOOKUP).'" onclick="window.open(this);return false"><i class="icon-external-link"></i></a>';
		 }
		 ?>
         <tr>
		  <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal')" name="del[]" value="<?php echo $LOG->logID; ?>" id="log_<?php echo $LOG->logID; ?>"></td>
		  <?php
		  }
		  ?>
          <td><?php echo mswSpecialChars(($LOG->type=='acc' ? $LOG->portalName : $LOG->userName)); ?> <a href="?p=<?php echo ($LOG->type=='acc' ? 'accounts&amp;edit='.$LOG->personID : 'team&amp;edit='.$LOG->personID); ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-edit"></i></a></td>
		  <td><?php echo ($LOG->type=='user' ? $msg_log15 : $msg_log14); ?></td>
		  <td><?php echo $ips_html; ?></td>
          <td><?php echo $MSDT->mswDateTimeDisplay($LOG->lts,$SETTINGS->dateformat).' / '.$MSDT->mswDateTimeDisplay($LOG->lts,$SETTINGS->timeformat); ?></td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '5' : '4'); ?>"><?php echo $msg_log4; ?></td>
		 </tr> 
		 <?php
		 }
		 ?>
        </tbody>
       </table>
      </div>
      
	  <?php
	  if ($countedRows>0) {
	  ?>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="p" value="log">
	   <?php
	   if (USER_DEL_PRIV=='yes') {
	   ?>
	   <div class="pull-right">
		<button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','clear');return false;" type="submit" name="export" class="btn btn-danger"><i class="icon-trash"></i> <?php echo mswSpecialChars($msg_log2); ?></button>
	   </div>
	   <?php
	   ?>
       <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','delete');return false;" class="btn btn-danger" disabled="disabled" type="submit" id="delButton"><i class="icon-trash"></i> <?php echo mswSpecialChars($msg_levels9); ?> <span id="mc_countVal">(0)</span></button>
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
	  ?>
      </form>
      <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
	
  </div>

</div>