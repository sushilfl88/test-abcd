<?php if (!defined('PARENT')) { exit; } 
$SQL         = '';

if (!isset($_GET['orderby'])) {
  $_GET['orderby'] = 'order_asc';
}

if (isset($_GET['orderby'])) {
  switch ($_GET['orderby']) {
    // Title (ascending)..
    case 'title_asc':
	$orderBy = 'ORDER BY `title`';
	break;
	// Title (descending)..
    case 'title_desc':
	$orderBy = 'ORDER BY `title` desc';
	break;
	// Order Sequence (ascending)..
    case 'order_asc':
	$orderBy = 'ORDER BY `orderBy`';
	break;
	// Order Sequence (descending)..
    case 'order_desc':
	$orderBy = 'ORDER BY `orderBy` desc';
	break;
  }
}

if (isset($_GET['dept'])) {
  if ($_GET['dept']=='disabled') {
    $SQL          = 'WHERE `enResponse` = \'no\'';
  } else {
    $_GET['dept'] = (int)$_GET['dept'];
    $SQL          = 'WHERE FIND_IN_SET(\''.$_GET['dept'].'\',`departments`)>0';
  }
}

if (isset($_GET['keys']) && $_GET['keys']) {
  $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
  $SQL           = 'WHERE LOWER(`title`) LIKE \'%'.$_GET['keys'].'%\' OR LOWER(`answer`) LIKE \'%'.$_GET['keys'].'%\'';
}

$q           = mysql_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."responses`
               $SQL
			   $orderBy
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
  });
  //]]>
  </script>
  <div class="header">
    
	<?php
	// Order By..
	if (mswRowCount('responses')>0) {
	$links = array(
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=title_asc'.mswQueryParams(array('p','orderby')),  'name' => $msg_response23),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=title_desc'.mswQueryParams(array('p','orderby')), 'name' => $msg_response24),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_asc'.mswQueryParams(array('p','orderby')),  'name' => $msg_levels23),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_desc'.mswQueryParams(array('p','orderby')), 'name' => $msg_levels24)
	);
	echo $MSBOOTSTRAP->button($msg_script45,$links);
	// Filters..
	$links   = array(array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','dept','next')), 'name' => $msg_response25));
	$q_dept  = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
               or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    while ($DEPT = mysql_fetch_object($q_dept)) {
	  $links[] = array('link' => '?p='.$_GET['p'].'&amp;dept='.$DEPT->id.mswQueryParams(array('p','dept','next')), 'name' => $msg_response26.' '.mswCleanData($DEPT->name));
	}
	$links[] = array('link' => '?p='.$_GET['p'].'&amp;dept=disabled'.mswQueryParams(array('p','dept','next')), 'name' => $msg_response27);
	echo $MSBOOTSTRAP->button($msg_search20,$links);
	// Page filter..
	include(PATH.'templates/system/bootstrap/page-filter.php');
	?>
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<?php
	}
	?>
	<h1 class="page-title"><?php echo $msg_adheader54; ?> (<?php echo @number_format($countedRows); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader13; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader54; ?></li>
  </ul>
  
  <?php
  // Selected deleted..
  if (isset($OK1)) {
    if ($count>0) {
      echo mswActionCompleted($msg_response10);
    }
  }
  // Sequence updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_levels20);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar" id="b1">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('?p=standard-responses')"><i class="icon-plus"></i> <?php echo $msg_response3; ?></button>
	   <button class="btn btn-info" type="button" onclick="ms_windowLoc('?p=standard-responses-import')"><i class="icon-upload-alt"></i> <?php echo $msg_response14; ?></button>
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
		  <th style="width:8%">ID</th>
		  <th style="width:11%"><?php echo $msg_customfields; ?></th>
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '61' : '66'); ?>%"><?php echo $msg_response; ?></th>
          <th style="width:15%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if (mysql_num_rows($q)>0) {
		 $totalR = mswRowCount('responses');
         while ($SR = mysql_fetch_object($q)) {
		 ?>
         <tr>
		  <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal')" name="del[]" value="<?php echo $SR->id; ?>" id="sr_<?php echo $SR->id; ?>"></td>
		  <?php
		  }
		  ?>
          <td><?php echo $SR->id; ?></td>
		  <td><select name="order[<?php echo $SR->id; ?>]" style="width:50px">
          <?php
          for ($i=1; $i<($totalR+1); $i++) {
          ?>
          <option value="<?php echo $i; ?>"<?php echo mswSelectedItem($SR->orderBy,$i,false); ?>><?php echo $i; ?></option>
          <?php
          }
          ?>
          </select></td>
          <td>
		  <?php echo mswCleanData($SR->title); ?>
		  <span class="tdCellInfo">
		  <?php echo mswSrCat($SR->departments); ?>
		  </span>
		  </td>
          <td class="ms-options-links">
		    <span class="enableDisable"><i class="<?php echo ($SR->enResponse=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','<?php echo $SR->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    <a href="?p=standard-responses&amp;edit=<?php echo $SR->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
		    <a href="?p=<?php echo $_GET['p']; ?>&amp;view=<?php echo $SR->id; ?>" class="nyroModal" title="<?php echo mswSpecialChars($msg_response12); ?>"><i class="icon-search"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '5' : '4'); ?>"><?php echo $msg_response9; ?></td>
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