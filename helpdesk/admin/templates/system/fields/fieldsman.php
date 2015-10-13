<?php if (!defined('PARENT')) { exit; } 
$SQL = '';

if (!isset($_GET['orderby'])) {
  $_GET['orderby'] = 'order_asc';
}

if (isset($_GET['orderby'])) {
  switch ($_GET['orderby']) {
    // Title (ascending)..
    case 'title_asc':
	$orderBy = 'ORDER BY `fieldInstructions`';
	break;
	// Title (descending)..
    case 'title_desc':
	$orderBy = 'ORDER BY `fieldInstructions` desc';
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
  switch ($_GET['dept']) {
    case 'disabled':
    $SQL          = 'WHERE `enField` = \'no\'';
    break;
	case 'required':
    $SQL          = 'WHERE `fieldReq` = \'yes\'';
    break;
	case 'ticket':
    case 'reply':
    case 'admin':
    $SQL          = 'WHERE FIND_IN_SET(\''.$_GET['dept'].'\',`fieldLoc`) > 0';
    break;
    default:
    $_GET['dept'] = (int)$_GET['dept'];
    $SQL          = 'WHERE FIND_IN_SET(\''.$_GET['dept'].'\',`departments`)>0';
	break;
  }
}

if (isset($_GET['keys']) && $_GET['keys']) {
  $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
  $SQL           = 'WHERE LOWER(`fieldInstructions`) LIKE \'%'.$_GET['keys'].'%\' OR LOWER(`fieldOptions`) LIKE \'%'.$_GET['keys'].'%\'';
}

$q            = mysql_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."cusfields`
                $SQL
			    $orderBy
				LIMIT $limitvalue,$limit
				") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows  =  (isset($c->rows) ? $c->rows : '0');
?>
<div class="content">
        
  <div class="header">
    
	<?php
	// Order By..
	if (mswRowCount('cusfields')>0) {
	$links = array(
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=title_asc'.mswQueryParams(array('p','orderby','next')),  'name' => $msg_customfields37),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=title_desc'.mswQueryParams(array('p','orderby','next')), 'name' => $msg_customfields38),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_asc'.mswQueryParams(array('p','orderby','next')),  'name' => $msg_levels23),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_desc'.mswQueryParams(array('p','orderby','next')), 'name' => $msg_levels24)
	);
	echo $MSBOOTSTRAP->button($msg_script45,$links);
	// Order By..
	$links   = array(array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','dept')),  'name' => $msg_customfields39));
	$q_dept  = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
               or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    while ($DEPT = mysql_fetch_object($q_dept)) {
	  $links[] = array('link' => '?p='.$_GET['p'].'&amp;dept='.$DEPT->id.mswQueryParams(array('p','dept')), 'name' => $msg_response26.' '.mswCleanData($DEPT->name));
	}
	$links[] = array('link' => '?p='.$_GET['p'].'&amp;dept=disabled'.mswQueryParams(array('p','dept')), 'name' => $msg_response27);
	$links[] = array('link' => '?p='.$_GET['p'].'&amp;dept=required'.mswQueryParams(array('p','dept')), 'name' => $msg_customfields43);
	$links[] = array('link' => '?p='.$_GET['p'].'&amp;dept=ticket'.mswQueryParams(array('p','dept')),   'name' => $msg_customfields44);
	$links[] = array('link' => '?p='.$_GET['p'].'&amp;dept=reply'.mswQueryParams(array('p','dept')),    'name' => $msg_customfields45);
	$links[] = array('link' => '?p='.$_GET['p'].'&amp;dept=admin'.mswQueryParams(array('p','dept')),    'name' => $msg_customfields46);
	echo $MSBOOTSTRAP->button($msg_search20,$links);
	// Page filter..
	include(PATH.'templates/system/bootstrap/page-filter.php');
	?>
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<?php
	}
	?>
	<h1 class="page-title"><?php echo $msg_adheader43; ?> (<?php echo @number_format($countedRows); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader26; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader43; ?></li>
  </ul>

  <?php
  // Selected deleted..
  if (isset($OK1)) {
    if ($count>0) {
      echo mswActionCompleted($msg_customfields14);
    }
  }
  // Sequence updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_customfields22);
  }
  ?>
  
  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar" id="b1">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('?p=fields')"><i class="icon-plus"></i> <?php echo $msg_customfields2; ?></button>
      </div>
	  
	  <?php
	  // Search..
	  include(PATH.'templates/system/bootstrap/search-box.php');
	  ?>
	  
	  <div class="well" style="padding-bottom:0;margin-bottom:10px">
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
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '62' : '67'); ?>%"><?php echo $msg_customfields4.'/'.$msg_customfields3; ?></th>
		  <th style="width:15%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if ($countedRows>0) {
         while ($FIELD = mysql_fetch_object($q)) {
		 ?>
         <tr>
          <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal')" name="del[]" value="<?php echo $FIELD->id; ?>" id="field_<?php echo $FIELD->id; ?>"></td>
		  <?php
		  }
		  ?>
		  <td><?php echo $FIELD->id; ?></td>
          <td><select name="order[<?php echo $FIELD->id; ?>]" style="width:50px">
          <?php
          for ($i=1; $i<($countedRows+1); $i++) {
          ?>
          <option value="<?php echo $i; ?>"<?php echo mswSelectedItem($FIELD->orderBy,$i); ?>><?php echo $i; ?></option>
          <?php
          }
          ?>
          </select></td>
          <td>
		  <?php echo mswSpecialChars($FIELD->fieldInstructions); ?>
		  <span class="tdCellInfo">
		  <?php
		  echo str_replace(
		   array('{required}','{depts}','{display}'),
		   array(
		    ms_YesNo($FIELD->fieldReq),
			mswSrCat($FIELD->departments),
			mswFieldDisplayInformation($FIELD->fieldLoc)
		   ),
		   $msg_customfields33
		  );
		  ?>
		  </span>
		  </td>
          <td class="ms-options-links">
		    <span class="enableDisable"><i class="<?php echo ($FIELD->enField=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','<?php echo $FIELD->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    <a href="?p=fields&amp;edit=<?php echo $FIELD->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '5' : '4'); ?>"><?php echo $msg_customfields16; ?></td>
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
	  ?>

      <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
	
  </div>
  </form>

</div>