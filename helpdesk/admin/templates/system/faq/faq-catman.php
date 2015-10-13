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

if (isset($_GET['cat'])) {
  define('DISABLED_CATS',1);
}

if (isset($_GET['keys']) && $_GET['keys']) {
  $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
  $SQL           = 'AND (LOWER(`name`) LIKE \'%'.$_GET['keys'].'%\' OR LOWER(`summary`) LIKE \'%'.$_GET['keys'].'%\')';
}

$q            = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                (SELECT count(*) FROM `".DB_PREFIX."faqassign` 
			     WHERE (`".DB_PREFIX."categories`.`id` = `".DB_PREFIX."faqassign`.`itemID`)
				  AND `".DB_PREFIX."faqassign`.`desc` = 'category'
				) AS `queCount`
				FROM `".DB_PREFIX."categories`
                WHERE `subcat` = '0'
				$SQL
				$orderBy 
				".(!defined('DISABLED_CATS') ? 'LIMIT '.$limitvalue.','.$limit : '')
				) or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows  = (isset($c->rows) ? $c->rows : '0');
?>
<div class="content">
  <?php
  // For sub cat filtering on disabled cats where the parents are not disabled..
  // We show all cats but manipulate the DOM to remove them..
  if (defined('DISABLED_CATS')) {
  ?>
  <script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function() {
   jQuery('.en_cat_yes').each(function() {
     jQuery(this).remove();
   });
   if (!jQuery('tr [class="en_subcat_no"]').val()) {
	 jQuery('tbody').append('<tr class="warning nothing_to_see"><td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '6' : '5'); ?>"><?php echo $msg_kbasecats8; ?></td></tr>');
     jQuery('div [class="btn-toolbar"]').hide();
   }
  });
  //]]>
  </script>
  <?php
  }
  ?>
  <div class="header">
    
	<?php
	// Order By..
	if (mswRowCount('categories')>0) {
	$links = array(
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_asc'.mswQueryParams(array('p','orderby','next')),       'name' => $msg_kbase43),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_desc'.mswQueryParams(array('p','orderby','next')),      'name' => $msg_kbase44),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_asc'.mswQueryParams(array('p','orderby','next')),      'name' => $msg_levels23),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_desc'.mswQueryParams(array('p','orderby','next')),     'name' => $msg_levels24),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=questions_desc'.mswQueryParams(array('p','orderby','next')), 'name' => $msg_kbase58),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=questions_asc'.mswQueryParams(array('p','orderby','next')),  'name' => $msg_kbase57)
	);
	echo $MSBOOTSTRAP->button($msg_script45,$links);
	// Filters..
	$links = array(
	 array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','cat','next')),                    'name' => $msg_pkbase7),
	 array('link' => '?p='.$_GET['p'].'&amp;cat=disabled'.mswQueryParams(array('p','cat','next')),'name' => $msg_response27)
	);
	echo $MSBOOTSTRAP->button($msg_search20,$links);
	// Page filter..
	if (!defined('DISABLED_CATS')) {
	include(PATH.'templates/system/bootstrap/page-filter.php');
	}
	?>
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<?php
	}
	?>
	<h1 class="page-title"><?php echo $msg_adheader45; ?> (<?php echo @number_format(mswRowCount('categories')); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader17; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader45; ?></li>
  </ul>
  
  <?php
  // Deleted..
  if (isset($OK1)) {
    if ($count>0) {
      echo mswActionCompleted($msg_kbasecats12);
    }
  }
  // Order..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_kbase45);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar" id="b1">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('?p=faq-cat')"><i class="icon-plus"></i> <?php echo $msg_kbase16; ?></button>
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
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '53' : '59'); ?>%"><?php echo $msg_kbase17; ?></th>
		  <th style="width:15%"><?php echo $msg_kbase56; ?></th>
          <th style="width:15%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if ($countedRows>0) {
         while ($CAT = mysql_fetch_object($q)) {
		 ?>
         <tr class="en_cat_<?php echo $CAT->enCat; ?>">
          <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td><input type="checkbox" onclick="ms_checkRange(this.checked,'subcat_<?php echo $CAT->id; ?>');ms_checkCount('well','delButton','mc_countVal');" name="del[]" value="<?php echo $CAT->id; ?>" id="cat_<?php echo $CAT->id; ?>"></td>
		  <?php
		  }
		  ?>
		  <td><select name="order[<?php echo $CAT->id; ?>]" style="margin:0 10px 0 0;width:50px">
          <?php
          for ($i=1; $i<($countedRows+1); $i++) {
          ?>
          <option value="<?php echo $i; ?>"<?php echo mswSelectedItem($CAT->orderBy,$i); ?>><?php echo $i; ?></option>
          <?php
          }
          ?>
          </select></td>
          <td>
		  <?php echo mswSpecialChars($CAT->name); ?>
		  <span class="tdCellInfo">
		  <?php echo (strlen($CAT->summary)>CATEGORIES_SUMMARY_TEXT_LIMIT ? substr(mswSpecialChars($CAT->summary),0,CATEGORIES_SUMMARY_TEXT_LIMIT).'..' : mswSpecialChars($CAT->summary)); ?>
		  </span>
		  </td>
		  <td><a href="?p=faqman&amp;cat=<?php echo $CAT->id; ?>" title="<?php echo @number_format($CAT->queCount); ?>"><?php echo @number_format($CAT->queCount); ?></a></td>
          <td class="ms-options-links">
		    <span class="enableDisable"><i class="<?php echo ($CAT->enCat=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','<?php echo $CAT->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    <a href="?p=faq-cat&amp;edit=<?php echo $CAT->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
		  </td>
         </tr>
		 <?php
		 
		 //============================
		 // SUB CATEGORIES
		 //============================
		 
		 $q2  = mysql_query("SELECT *, 
		        (SELECT count(*) FROM `".DB_PREFIX."faqassign` 
			     WHERE (`".DB_PREFIX."categories`.`id` = `".DB_PREFIX."faqassign`.`itemID`)
				  AND `".DB_PREFIX."faqassign`.`desc` = 'category'
				) AS `queCount`
				FROM `".DB_PREFIX."categories` 
		        WHERE `subcat` = '{$CAT->id}'
				".(defined('DISABLED_CATS') ? 'AND `enCat` = \'no\'' : '')." 
				".$SQL." ".$orderBy
				) or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		 $subCount = mysql_num_rows($q2);
		 if ($subCount>0) {
		 while ($SUB = mysql_fetch_object($q2)) {
		 ?>
         <tr class="en_subcat_<?php echo $SUB->enCat; ?>">
          <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td style="padding-left:15px" class="subcat_<?php echo $CAT->id; ?>"><input type="checkbox" onclick="if(!this.checked){ms_uncheck('cat_<?php echo $CAT->id; ?>')};ms_checkCount('well','delButton','mc_countVal')" name="del[]" value="<?php echo $SUB->id; ?>" id="cat_<?php echo $SUB->id; ?>"></td>
		  <?php
		  }
		  ?>
		  <td style="padding-left:15px"><select name="orderSub[<?php echo $SUB->id; ?>]" style="margin:0 10px 0 0;width:50px">
          <?php
          for ($i=1; $i<($subCount+1); $i++) {
          ?>
          <option value="<?php echo $i; ?>"<?php echo mswSelectedItem($SUB->orderBy,$i); ?>><?php echo $i; ?></option>
          <?php
          }
          ?>
          </select></td>
          <td style="padding-left:15px">
		  <?php echo mswSpecialChars($SUB->name); ?>
		  <span class="tdCellInfo">
		  <?php echo (strlen($SUB->summary)>CATEGORIES_SUMMARY_TEXT_LIMIT ? substr(mswSpecialChars($SUB->summary),0,CATEGORIES_SUMMARY_TEXT_LIMIT).'..' : mswSpecialChars($SUB->summary)); ?>
		  </span>
		  </td>
		  <td><a href="?p=faqman&amp;cat=<?php echo $SUB->id; ?>" title="<?php echo @number_format($SUB->queCount); ?>"><?php echo @number_format($SUB->queCount); ?></a></td>
          <td class="ms-options-links">
		    <span class="enableDisable"><i class="<?php echo ($SUB->enCat=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','<?php echo $SUB->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    <a href="?p=faq-cat&amp;edit=<?php echo $SUB->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 }
		 
		 //============================
		 // END SUB CATEGORIES
		 //============================
				
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '6' : '5'); ?>"><?php echo $msg_kbasecats8; ?></td>
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
	  
	  if ($countedRows>0 && $countedRows>$limit && !defined('DISABLED_CATS')) {
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