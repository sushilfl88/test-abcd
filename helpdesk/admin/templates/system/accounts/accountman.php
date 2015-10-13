<?php if (!defined('PARENT')) { exit; }
$SQL = '';
if (!isset($_GET['orderby'])) {
  $_GET['orderby'] = 'order_asc';
}
$orderBy = 'ORDER BY `name`';

if (isset($_GET['orderby'])) {
  switch ($_GET['orderby']) {
    // Name (ascending)..
    case 'name_asc':
	$orderBy = 'ORDER BY `name`';
	break;
	// Name (descending)..
    case 'name_desc':
	$orderBy = 'ORDER BY `name` desc';
	break;
	// Email Address (ascending)..
    case 'email_asc':
	$orderBy = 'ORDER BY `email`';
	break;
	// Email Address (descending)..
    case 'email_desc':
	$orderBy = 'ORDER BY `email` desc';
	break;
	// Most tickets..
    case 'tickets_asc':
	$orderBy = 'ORDER BY `tickCount` desc';
	break;
	// Least tickets..
    case 'tickets_desc':
	$orderBy = 'ORDER BY `tickCount`';
	break;
  }
}

if (isset($_GET['filter'])) {
  switch ($_GET['filter']) {
    case 'disabled':
    $SQL = 'WHERE `enabled` = \'no\'';
    break;
  }
}

// Are we querying for disputes..
$sqlDisputes = '';
if ($SETTINGS->disputes=='yes') {
  $sqlDisputes = ',
   (SELECT count(*) FROM `'.DB_PREFIX.'disputes` 
    WHERE `'.DB_PREFIX.'portal`.`id` = `'.DB_PREFIX.'disputes`.`visitorID`
   ) AS `dispCount`';
}

$q           = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
               (SELECT count(*) FROM `".DB_PREFIX."tickets` 
			    WHERE `".DB_PREFIX."portal`.`id` = `".DB_PREFIX."tickets`.`visitorID`
				AND `spamFlag`   = 'no'
				AND `isDisputed` = 'no'
			   ) AS `tickCount`
			   $sqlDisputes
			   FROM `".DB_PREFIX."portal`
               $SQL
			   $orderBy
			   LIMIT $limitvalue,$limit
			   ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows  = (isset($c->rows) ? $c->rows : '0');
?>
<div class="content">
    
  <div class="header">
    
    <?php
	// Order By..
	$links = array(
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_asc'.mswQueryParams(array('p','orderby')),    'name' => $msg_levels21),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_desc'.mswQueryParams(array('p','orderby')),   'name' => $msg_levels22),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=email_asc'.mswQueryParams(array('p','orderby')),   'name' => $msg_accounts9),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=email_desc'.mswQueryParams(array('p','orderby')),  'name' => $msg_accounts10),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=tickets_asc'.mswQueryParams(array('p','orderby')), 'name' => $msg_accounts11),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=tickets_desc'.mswQueryParams(array('p','orderby')),'name' => $msg_accounts12)
	);
	echo $MSBOOTSTRAP->button($msg_script45,$links);
	// Filters..
	$links = array(
	 array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','orderby')),                       'name' => $msg_accounts14),
	 array('link' => '?p='.$_GET['p'].'&amp;filter=disabled'.mswQueryParams(array('p','filter')), 'name' => $msg_response27)
	);
	echo $MSBOOTSTRAP->button($msg_search20,$links);
	// Page filter..
	include(PATH.'templates/system/bootstrap/page-filter.php');
	?>
	<h1 class="page-title"><?php echo $msg_adheader40; ?> (<?php echo $countedRows; ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader38; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader40; ?></li>
  </ul>
  
  <?php
  // Deleted..
  if (isset($OK)) {
    echo mswActionCompleted($msg_accounts15);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p']; ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('?p=accounts')"><i class="icon-plus"></i> <?php echo $msg_accounts4; ?></button>
      </div>
	  
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
		  <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '33' : '36'); ?>%"><?php echo $msg_accounts; ?></th>
		  <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '34' : '36'); ?>%"><?php echo $msg_accounts2; ?></th>
          <th style="width:13%"><?php echo ($SETTINGS->disputes=='yes' ? $msg_accounts38 : $msg_accounts3); ?></th>
          <th style="width:15%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if (mysql_num_rows($q)>0) {
         while ($ACC = mysql_fetch_object($q)) {
		 if (isset($ACC->dispCount)) {
		   $dCStart        = mswRowCount('tickets WHERE `visitorID` = \''.$ACC->id.'\' AND `isDisputed` = \'yes\' AND `spamFlag` = \'no\'');
		   $ACC->dispCount = ($ACC->dispCount+$dCStart);
		 }
		 ?>
         <tr>
          <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal')" name="del[]" value="<?php echo $ACC->id; ?>" id="acc_<?php echo $ACC->id; ?>"></td>
		  <?php
		  }
		  ?>
		  <td><?php echo ($ACC->name ? mswSpecialChars($ACC->name) : 'N/A'); ?></td>
          <td><?php echo mswCleanData($ACC->email); ?></td>
		  <?php
		  if ($SETTINGS->disputes=='yes') {
		  ?>
		  <td><a href="?p=acchistory&amp;id=<?php echo $ACC->id; ?>" title="<?php echo @number_format($ACC->tickCount); ?>"><?php echo @number_format($ACC->tickCount); ?></a> / <a href="?p=acchistory&amp;id=<?php echo $ACC->id; ?>&amp;disputes=yes" title="<?php echo @number_format($ACC->dispCount); ?>"><?php echo @number_format($ACC->dispCount); ?></a></td>
          <?php
		  } else {
		  ?>
		  <td><a href="?p=acchistory&amp;id=<?php echo $ACC->id; ?>" title="<?php echo @number_format($ACC->tickCount); ?>"><?php echo @number_format($ACC->tickCount); ?></a></td>
          <?php
		  }
		  $appendDisUrl = '';
		  if ($SETTINGS->disputes=='yes' && isset($ACC->dispCount) && $ACC->dispCount>0) {
		    $appendDisUrl = '&amp;disputes=yes';
		  }
		  ?>
		  <td class="ms-options-links">
		    <span class="enableDisable"><i class="<?php echo ($ACC->enabled=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','<?php echo $ACC->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    <a href="?p=accounts&amp;edit=<?php echo $ACC->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
			<a href="?p=acchistory&amp;id=<?php echo $ACC->id.$appendDisUrl; ?>" title="<?php echo mswSpecialChars($msg_accounts13); ?>"><i class="icon-calendar"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '5' : '4'); ?>"><?php echo $msg_accounts5; ?></td>
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
	   <?php
	   if (USER_DEL_PRIV=='yes') {
	   ?>
       <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','delete');return false;" class="btn btn-danger" disabled="disabled" type="submit" id="delButton"><i class="icon-trash"></i> <?php echo mswCleanData($msg_levels9); ?> <span id="mc_countVal">(0)</span></button>
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