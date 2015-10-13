<?php if (!defined('PARENT')) { exit; } 
$SQL         = '';

if (!isset($_GET['orderby'])) {
  $_GET['orderby'] = 'order_asc';
}
$orderBy      = 'ORDER BY `orderBy`';

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
	// Order Sequence (ascending)..
    case 'order_asc':
	$orderBy = 'ORDER BY `orderBy`';
	break;
	// Order Sequence (descending)..
    case 'order_desc':
	$orderBy = 'ORDER BY `orderBy` desc';
	break;
	// Manually Assign (ascending)..
    case 'man_asc':
	$orderBy = 'ORDER BY FIELD(`manual_assign`,\'yes\',\'no\')';
	break;
	// Manually Assign (descending)..
    case 'man_desc':
	$orderBy = 'ORDER BY FIELD(`manual_assign`,\'no\',\'yes\')';
	break;
	// Visibility (ascending)..
    case 'vis_asc':
	$orderBy = 'ORDER BY FIELD(`showDept`,\'yes\',\'no\')';
	break;
	// Visibility (descending)..
    case 'vis_desc':
	$orderBy = 'ORDER BY FIELD(`showDept`,\'no\',\'yes\')';
	break;
	// Most tickets..
    case 'tickets_desc':
	$orderBy = 'ORDER BY `tickCount` desc';
	break;
	// Least tickets..
    case 'tickets_asc':
	$orderBy = 'ORDER BY `tickCount`';
	break;
  }
}

if (isset($_GET['keys']) && $_GET['keys']) {
  $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
  $SQL           = (mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE') ? ' AND ' : 'WHERE ').' LOWER(`name`) LIKE \'%'.$_GET['keys'].'%\'';
}

$q           = mysql_query("SELECT SQL_CALC_FOUND_ROWS *, 
               (SELECT count(*) FROM `".DB_PREFIX."tickets` 
			    WHERE `".DB_PREFIX."departments`.`id` = `".DB_PREFIX."tickets`.`department`
				AND `spamFlag` = 'no'
			   ) AS `tickCount`
			   FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." 
               $SQL
               $orderBy
               LIMIT $limitvalue,$limit
			   ") 
               or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows  =  (isset($c->rows) ? $c->rows : '0');
?>
<div class="content">
        
  <div class="header">
    
	<?php
	// Order By..
	if (mswRowCount('departments')>0) {
	$links = array(
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_asc'.mswQueryParams(array('p','orderby')),     'name' => $msg_levels21),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_desc'.mswQueryParams(array('p','orderby')),    'name' => $msg_levels22),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_asc'.mswQueryParams(array('p','orderby')),    'name' => $msg_levels23),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_desc'.mswQueryParams(array('p','orderby')),   'name' => $msg_levels24),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=man_asc'.mswQueryParams(array('p','orderby')),      'name' => $msg_dept26),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=man_desc'.mswQueryParams(array('p','orderby')),     'name' => $msg_dept27),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=vis_asc'.mswQueryParams(array('p','orderby')),      'name' => $msg_dept28),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=vis_desc'.mswQueryParams(array('p','orderby')),     'name' => $msg_dept29),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=tickets_desc'.mswQueryParams(array('p','orderby')), 'name' => $msg_accounts11),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=tickets_asc'.mswQueryParams(array('p','orderby')),  'name' => $msg_accounts12)
	);
	echo $MSBOOTSTRAP->button($msg_script45,$links);
	// Page filter..
	include(PATH.'templates/system/bootstrap/page-filter.php');
	?>
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<?php
	}
	?>
	<h1 class="page-title"><?php echo $msg_dept9; ?> (<?php echo @number_format($countedRows); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><a href="index.php"><?php echo $msg_adheader3; ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_dept9; ?></li>
  </ul>

  <?php
  // Selected deleted..
  if (isset($OK1)) {
    if ($count>0) {
      echo mswActionCompleted($msg_dept13);
    }
  }
  // Sequence updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_dept21);
  }
  ?>
  
  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar" id="b1">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('?p=dept')"><i class="icon-plus"></i> <?php echo $msg_dept2; ?></button>
      </div>
	  
	  <?php
	  // Search..
	  include(PATH.'templates/system/bootstrap/search-box.php');
	  ?>
	  
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
		  <th style="width:7%">ID</th>
          <th style="width:11%"><?php echo $msg_customfields; ?></th>
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '47' : '42'); ?>%"><?php echo $msg_dept19; ?></th>
		  <th style="width:15%"><?php echo $msg_accounts3; ?></th>
          <th style="width:15%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if ($countedRows>0) {
         while ($DEPT = mysql_fetch_object($q)) {
		 ?>
         <tr>
		  <?php
		  if (USER_DEL_PRIV=='yes' && mswRowCount('tickets WHERE `department` = \''.$DEPT->id.'\'')==0) {
		  ?>
          <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal')" name="del[]" value="<?php echo $DEPT->id; ?>" id="dept_<?php echo $DEPT->id; ?>"></td>
		  <?php
		  } else {
		  ?>
		  <td>&nbsp;</td>
		  <?php
		  }
		  ?>
          <td><?php echo $DEPT->id; ?></td>
          <td><select name="order[<?php echo $DEPT->id; ?>]" style="width:50px">
          <?php
          for ($i=1; $i<($countedRows+1); $i++) {
          ?>
          <option value="<?php echo $i; ?>"<?php echo mswSelectedItem($DEPT->orderBy,$i,false); ?>><?php echo $i; ?></option>
          <?php
          }
          ?>
          </select></td>
          <td><?php echo mswSpecialChars($DEPT->name,false); ?>
		  <span class="tdCellInfo">
		  <?php 
		  $whatsOn = array($msg_script5,$msg_script5);
          if ($DEPT->showDept=='yes') {
            $whatsOn[0] = $msg_script4;
          }
          if ($DEPT->manual_assign=='yes') {
            $whatsOn[1] = $msg_script4;
          }
		  echo str_replace(array('{manual}','{visible}'),array($whatsOn[1],$whatsOn[0]),$msg_dept23); ?>
		  </span>
		  </td>
		  <td><a href="?p=search&amp;keys=&amp;dept=<?php echo $DEPT->id; ?>" title="<?php echo @number_format($DEPT->tickCount); ?>"><?php echo @number_format($DEPT->tickCount); ?></a></td>
          <td class="ms-options-links">
		    <a href="?p=dept&amp;edit=<?php echo $DEPT->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '6' : '5'); ?>"><?php echo $msg_dept8; ?></td>
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
	   <button class="btn btn-primary" type="submit" name="update-order"><i class="icon-sort-by-order"></i> <?php echo mswCleanData($msg_levels8); ?></button>
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