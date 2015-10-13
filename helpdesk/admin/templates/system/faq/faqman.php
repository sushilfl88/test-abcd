<?php if (!defined('PARENT')) { exit; } 
$SQL = '';

if (!isset($_GET['orderby'])) {
  $_GET['orderby'] = 'order_asc';
}
$orderBy = 'ORDER BY `orderBy`';

if (isset($_GET['orderby'])) {
  switch ($_GET['orderby']) {
    // Question (ascending)..
    case 'que_asc':
	$orderBy = 'ORDER BY `question`';
	break;
	// Question (descending)..
    case 'que_desc':
	$orderBy = 'ORDER BY `question` desc';
	break;
	// Order Sequence (ascending)..
    case 'order_asc':
	$orderBy = 'ORDER BY `orderBy`';
	break;
	// Order Sequence (descending)..
    case 'order_desc':
	$orderBy = 'ORDER BY `orderBy` desc';
	break;
	// Most attachments..
    case 'att_desc':
	$orderBy = 'ORDER BY `attCount` desc';
	break;
	// Least attachments..
    case 'att_asc':
	$orderBy = 'ORDER BY `attCount`';
	break;
  }
}

if (isset($_GET['cat'])) {
  switch ($_GET['cat']) {
    case 'disabled':
	$SQL          = 'WHERE `enFaq` = \'no\'';
	break;
	default:
    $_GET['cat']  = (int)$_GET['cat'];
    $SQL          = 'WHERE (SELECT count(*) FROM `'.DB_PREFIX.'faqassign` WHERE (`'.DB_PREFIX.'faq`.`id` = `'.DB_PREFIX.'faqassign`.`question`) AND `'.DB_PREFIX.'faqassign`.`itemID` = \''.$_GET['cat'].'\' AND `'.DB_PREFIX.'faqassign`.`desc` = \'category\') > 0';
	break;
  }	
}

if (isset($_GET['keys']) && $_GET['keys']) {
  $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
  $SQL           = 'WHERE LOWER(`question`) LIKE \'%'.$_GET['keys'].'%\' OR LOWER(`answer`) LIKE \'%'.$_GET['keys'].'%\'';
} else {
  // Are we showing questions only allocated to an attachment?
  if (isset($_GET['attached'])) {
    $_GET['attached'] = (int)$_GET['attached'];
	$attachIDs        = array();
	$qA               = mysql_query("SELECT `question` FROM `".DB_PREFIX."faqassign`
                        WHERE `itemID` = '{$_GET['attached']}'
						AND `desc`     = 'attachment'
						GROUP BY `question`
						") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    while ($AA = mysql_fetch_object($qA)) {
	  $attachIDs[] = $AA->question;
	}
	if (!empty($attachIDs)) {
	  $SQL = 'WHERE `id` IN('.implode(',',$attachIDs).')';
	} else {
	  $SQL = 'WHERE `id` IN(0)';
	}
  }
}

$q              = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                  (SELECT count(*) FROM `".DB_PREFIX."faqassign` 
			       WHERE (`".DB_PREFIX."faqassign`.`question` = `".DB_PREFIX."faq`.`id`)
				    AND `".DB_PREFIX."faqassign`.`desc`       = 'attachment'
			      ) AS `attCount`
                  FROM `".DB_PREFIX."faq`
                  $SQL
                  $orderBy
                  LIMIT $limitvalue,$limit
                  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c              = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows    =  (isset($c->rows) ? $c->rows : '0');
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
	if (mswRowCount('faq')>0) {
	$links = array(
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=que_asc'.mswQueryParams(array('p','orderby','next')),  'name' => $msg_kbase46),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=que_desc'.mswQueryParams(array('p','orderby','next')), 'name' => $msg_kbase47),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_asc'.mswQueryParams(array('p','orderby','next')), 'name' => $msg_levels23),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=order_desc'.mswQueryParams(array('p','orderby','next')),'name' => $msg_levels24),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=att_desc'.mswQueryParams(array('p','orderby','next')), 'name' => $msg_kbase62),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=att_asc'.mswQueryParams(array('p','orderby','next')),  'name' => $msg_kbase61)
	);
	echo $MSBOOTSTRAP->button($msg_script45,$links);
	// Filters..
	$links   = array(array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','cat','next')),  'name' => $msg_kbase48));
	$q_c     = mysql_query("SELECT `id`,`name` FROM `".DB_PREFIX."categories` WHERE `subcat` = '0' ORDER BY `name`") 
               or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    while ($CAT = mysql_fetch_object($q_c)) {
	 $links[] = array('link' => '?p='.$_GET['p'].'&amp;cat='.$CAT->id.mswQueryParams(array('p','cat','next')),'name' => $msg_response26.' '.mswCleanData($CAT->name));
     $q_c2    = mysql_query("SELECT `id`,`name` FROM `".DB_PREFIX."categories` WHERE `subcat` = '{$CAT->id}' ORDER BY `name`") 
                or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	 while ($SUB = mysql_fetch_object($q_c2)) {
	  $links[] = array('link' => '?p='.$_GET['p'].'&amp;cat='.$SUB->id.mswQueryParams(array('p','cat')),'name' => '&nbsp;&nbsp;'.$msg_response26.' '.mswCleanData($SUB->name));
	 }
    }
	$links[] = array('link' => '?p='.$_GET['p'].'&amp;cat=disabled'.mswQueryParams(array('p','cat')),'name' => $msg_response27);
	echo $MSBOOTSTRAP->button($msg_search20,$links);
	// Page filter..
	include(PATH.'templates/system/bootstrap/page-filter.php');
	if (!isset($_GET['attached'])) {
	?>
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<?php
	}
	}
	?>
	<h1 class="page-title"><?php echo str_replace('&amp;amp;','&amp;',mswSpecialChars($msg_adheader47)); ?> (<?php echo @number_format($countedRows); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader17; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo str_replace('&amp;amp;','&amp;',mswSpecialChars($msg_adheader47)); ?></li>
  </ul>
  
  <?php
  // Reset..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_kbase21);
  }
  // Delete..
  if (isset($OK2)) {
    if ($count>0) {
      echo mswActionCompleted($msg_kbase10);
	}
  }
  // Order..
  if (isset($OK3)) {
    echo mswActionCompleted($msg_kbase45);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar" id="b1">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('?p=faq')"><i class="icon-plus"></i> <?php echo mswCleanData((isset($EDIT->id) ? $msg_kbase13 : $msg_kbase3)); ?></button>
       <button class="btn btn-info" type="button" onclick="ms_windowLoc('?p=faq-import')"><i class="icon-upload-alt"></i> <?php echo $msg_adheader55; ?></button>
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
		  <input type="checkbox" onclick="checkBoxes(this.checked,'.well');ms_checkCount('well','delButton','mc_countVal');ms_checkCount('well','delButton2','mc_countVal2')">
		  </th>
		  <?php
		  }
		  ?>
		  <th style="width:10%"><?php echo $msg_customfields; ?></th>
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '54' : '60'); ?>%"><?php echo $msg_kbase; ?></th>
		  <th style="width:15%"><?php echo $msg_kbase51; ?></th>
          <th style="width:15%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if (mysql_num_rows($q)>0) {
		 $totalR = mswRowCount('faq');
         while ($QUE = mysql_fetch_object($q)) {
		 if ($SETTINGS->enableVotes=='yes') {
		 $yes  = ($QUE->kviews>0 ? @number_format($QUE->kuseful/$QUE->kviews*100,2) : 0);
         $no   = ($QUE->kviews>0 ? @number_format($QUE->knotuseful/$QUE->kviews*100,2) : 0);
		 }
		 ?>
         <tr>
		  <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal');ms_checkCount('well','delButton2','mc_countVal2')" name="del[]" value="<?php echo $QUE->id; ?>" id="que_<?php echo $QUE->id; ?>"></td>
		  <?php
		  }
		  ?>
          <td><select name="order[<?php echo $QUE->id; ?>]" style="margin:0 10px 0 0;width:50px">
          <?php
          for ($i=1; $i<($totalR+1); $i++) {
          ?>
          <option value="<?php echo $i; ?>"<?php echo mswSelectedItem($QUE->orderBy,$i); ?>><?php echo $i; ?></option>
          <?php
          }
          ?>
          </select></td>
          <td>
		  <?php echo mswSpecialChars($QUE->question); ?>
		  <span class="tdCellInfo">
		  <?php
		  $assignedCats = mswFaqCategories($QUE->id);
		  echo ($assignedCats ? $assignedCats : '<span class="unassigned"><i class="icon-warning-sign"></i> '.$msg_kbase63.'</span>');
		  ?>
		  </span>
		  <span class="tdCellInfo">
		  <?php
		  echo ($SETTINGS->enableVotes=='yes' ? str_replace(array('{count}','{helpful}','{nothelpful}'),array($QUE->kviews,$yes,$no),$msg_kbase18) : '');
		  ?>
		  </span>
		  </td>
		  <td><a href="?p=attachman&amp;question=<?php echo $QUE->id; ?>" title="<?php echo $QUE->attCount; ?>"><?php echo $QUE->attCount; ?></a></td>
          <td class="ms-options-links">
		    <span class="enableDisable"><i class="<?php echo ($QUE->enFaq=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','<?php echo $QUE->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    <a href="?p=faq&amp;edit=<?php echo $QUE->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
			<a href="?p=<?php echo $_GET['p']; ?>&amp;view=<?php echo $QUE->id; ?>" class="nyroModal" title="<?php echo mswSpecialChars($msg_kbase12); ?>"><i class="icon-search"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '4' : '3'); ?>"><?php echo $msg_kbase9; ?></td>
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
	   if ($SETTINGS->enableVotes=='yes') {
	   ?>
	   <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','reset');return false;" class="btn" disabled="disabled" type="submit" id="delButton2"><i class="icon-refresh"></i> <?php echo mswCleanData($msg_kbase27); ?> <span id="mc_countVal2">(0)</span></button>
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