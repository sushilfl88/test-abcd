<?php if (!defined('PARENT')) { exit; } 
$SQL = '';

if (!isset($_GET['orderby'])) {
  $_GET['orderby'] = 'order_asc';
}
$orderBy = 'ORDER BY `orderBy`';

if (isset($_GET['orderby'])) {
  switch ($_GET['orderby']) {
    // Cat Name (ascending)..
    case 'name_asc':
	$orderBy = 'ORDER BY `name`';
	break;
	// Cat Name (descending)..
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
	// Most questions..
    case 'questions_desc':
	$orderBy = 'ORDER BY `queCount` desc';
	break;
	// Least questions..
    case 'questions_asc':
	$orderBy = 'ORDER BY `queCount`';
	break;
  }
}

if (isset($_GET['opt'])) {
  switch ($_GET['opt']) {
    case 'disabled':
	$SQL = 'WHERE `enAtt` = \'no\'';
	break;
	case 'remote':
	$SQL = 'WHERE `path` = \'\'';
	break;
  }
}

if (isset($_GET['keys']) && $_GET['keys']) {
  $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
  $SQL           = 'WHERE LOWER(`name`) LIKE \'%'.$_GET['keys'].'%\' OR LOWER(`remote`) LIKE \'%'.$_GET['keys'].'%\' OR LOWER(`path`) LIKE \'%'.$_GET['keys'].'%\'';
} else {
  // Are we showing attachments only allocated to a question?
  if (isset($_GET['question'])) {
    $_GET['question'] = (int)$_GET['question'];
	$attachIDs        = array();
	$qA               = mysql_query("SELECT `itemID` FROM `".DB_PREFIX."faqassign`
                        WHERE `question` = '{$_GET['question']}'
						AND `desc`       = 'attachment'
						GROUP BY `itemID`
						") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    while ($AA = mysql_fetch_object($qA)) {
	  $attachIDs[] = $AA->itemID;
	}
	if (!empty($attachIDs)) {
	  $SQL = 'WHERE `id` IN('.implode(',',$attachIDs).')';
	} else {
	  $SQL = 'WHERE `id` IN(0)';
	}
  }
}  

$q            = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                (SELECT count(*) FROM `".DB_PREFIX."faqassign` 
			     WHERE (`".DB_PREFIX."faqassign`.`itemID` = `".DB_PREFIX."faqattach`.`id`)
				  AND `".DB_PREFIX."faqassign`.`desc`     = 'attachment'
			    ) AS `queCount`
				FROM `".DB_PREFIX."faqattach`
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
	if (mswRowCount('faqattach')>0) {
	$links = array(
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_asc'.mswQueryParams(array('p','orderby','next')),       'name' => $msg_attachments17),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_desc'.mswQueryParams(array('p','orderby','next')),      'name' => $msg_attachments18),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_asc'.mswQueryParams(array('p','orderby','next')),      'name' => $msg_levels23),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_desc'.mswQueryParams(array('p','orderby','next')),     'name' => $msg_levels24),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=questions_desc'.mswQueryParams(array('p','orderby','next')), 'name' => $msg_kbase58),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=questions_asc'.mswQueryParams(array('p','orderby','next')),  'name' => $msg_kbase57)
	);
	echo $MSBOOTSTRAP->button($msg_script45,$links);
	// Filters..
	$links = array(
	 array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','opt','next')),                     'name' => $msg_attachments20),
	 array('link' => '?p='.$_GET['p'].'&amp;opt=disabled'.mswQueryParams(array('p','opt','next')), 'name' => $msg_response27),
	 array('link' => '?p='.$_GET['p'].'&amp;opt=remote'.mswQueryParams(array('p','opt','next')),   'name' => $msg_attachments21)
	);
	echo $MSBOOTSTRAP->button($msg_search20,$links);
	// Page filter..
	include(PATH.'templates/system/bootstrap/page-filter.php');
	?>
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<?php
	}
	?>
	<h1 class="page-title"><?php echo str_replace('&amp;amp;','&amp;',mswSpecialChars($msg_adheader49)); ?> (<?php echo @number_format($countedRows); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader17; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo str_replace('&amp;amp;','&amp;',mswSpecialChars($msg_adheader49)); ?></li>
  </ul>
  
  <?php
  // Deleted..
  if (isset($OK1)) {
    if ($count>0) {
      echo mswActionCompleted($msg_attachments14);
    }
  }
  // Order..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_attachments19);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p']; ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar" id="b1">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('?p=attachments')"><i class="icon-plus"></i> <?php echo mswCleanData((isset($EDIT->id) ? $msg_attachments12 : $msg_attachments2)); ?></button>
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
          <th style="width:6%">
		  <input type="checkbox" onclick="checkBoxes(this.checked,'.well');ms_checkCount('well','delButton','mc_countVal')">
		  </th>
		  <?php
		  }
		  ?>
		  <th style="width:11%"><?php echo $msg_customfields; ?></th>
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '53' : '59'); ?>%"><?php echo $msg_attachments16; ?></th>
		  <th style="width:15%"><?php echo $msg_kbase56; ?></th>
          <th style="width:15%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if ($countedRows>0) {
         while ($ATT = mysql_fetch_object($q)) {
		 ?>
         <tr>
		  <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal');" name="del[]" value="<?php echo $ATT->id; ?>" id="att_<?php echo $ATT->id; ?>"></td>
		  <?php
		  }
		  ?>
          <td><select name="order[<?php echo $ATT->id; ?>]" style="margin:0 10px 0 0;width:50px">
          <?php
          for ($i=1; $i<($countedRows+1); $i++) {
          ?>
          <option value="<?php echo $i; ?>"<?php echo mswSelectedItem($ATT->orderBy,$i); ?>><?php echo $i; ?></option>
          <?php
          }
          ?>
          </select></td>
          <td>
		  <?php echo ($ATT->name ? mswSpecialChars($ATT->name) : ($ATT->remote ? $ATT->remote : $ATT->path)); ?>
		  <span class="tdCellInfo">
		  <?php echo str_replace(
		   array(
		    '{type}',
			'{size}'
		   ),
		   array(
		    strtoupper(substr(strrchr(strtolower(($ATT->remote ? $ATT->remote : $ATT->path)),'.'),1)),
			($ATT->size>0 ? mswFileSizeConversion($ATT->size) : 'N/A')
		   ),
		   $msg_attachments11); 
		  ?>
		  </span>
		  </td>
		  <td><a href="?p=faqman&amp;attached=<?php echo $ATT->id; ?>"><?php echo @number_format($ATT->queCount); ?></a></td>
          <td class="ms-options-links">
		    <span class="enableDisable"><i class="<?php echo ($ATT->enAtt=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','<?php echo $ATT->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    <a href="?p=attachments&amp;edit=<?php echo $ATT->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
			<a href="?fattachment=<?php echo $ATT->id; ?>" title="<?php echo mswSpecialChars($msg_viewticket50); ?>"><i class="icon-download-alt"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '5' : '4'); ?>"><?php echo $msg_attachments9; ?></td>
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