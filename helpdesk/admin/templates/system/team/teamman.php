<?php if (!defined('PARENT')) { exit; } 
$SQL = '';
if ($MSTEAM->id!='1') {
  $SQL = 'WHERE `id` > 1';
}

if (!isset($_GET['orderby'])) {
  $_GET['orderby'] = 'name_asc';
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
	// Most responses..
    case 'resp_asc':
	$orderBy = 'ORDER BY `respCount` desc';
	break;
	// Least tickets..
    case 'resp_desc':
	$orderBy = 'ORDER BY `respCount`';
	break;
  }
}

if (isset($_GET['filter'])) {
  switch ($_GET['filter']) {
    case 'disabled':
    $SQL = 'WHERE `enabled` = \'no\'';
    break;
	case 'notify':
    $SQL = 'WHERE `notify` = \'no\'';
    break;
	case 'delpriv':
    $SQL = 'WHERE `delPriv` = \'yes\'';
    break;
	case 'notepad':
    $SQL = 'WHERE `notePadEnable` = \'yes\'';
    break;
	case 'assigned':
    $SQL = 'WHERE `assigned` = \'yes\'';
    break;
  }
}

if (isset($_GET['keys']) && $_GET['keys']) {
  $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
  $SQL           = 'WHERE LOWER(`name`) LIKE \'%'.$_GET['keys'].'%\' OR LOWER(`email`) LIKE \'%'.$_GET['keys'].'%\'';
}

$q           = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
               (SELECT count(*) FROM `".DB_PREFIX."replies` 
			    WHERE `".DB_PREFIX."replies`.`replyUser` = `".DB_PREFIX."users`.`id` 
				AND `".DB_PREFIX."replies`.`replyType` = 'admin'
			   ) AS `respCount`
			   FROM `".DB_PREFIX."users`
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
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_asc'.mswQueryParams(array('p','orderby')),  'name' => $msg_levels21),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=name_desc'.mswQueryParams(array('p','orderby')), 'name' => $msg_levels22),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=email_asc'.mswQueryParams(array('p','orderby')), 'name' => $msg_accounts9),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=email_desc'.mswQueryParams(array('p','orderby')),'name' => $msg_accounts10),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=resp_asc'.mswQueryParams(array('p','orderby')),  'name' => $msg_user78),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=resp_desc'.mswQueryParams(array('p','orderby')), 'name' => $msg_user79)
	);
	echo $MSBOOTSTRAP->button($msg_script45,$links);
	// Filters..
	$links = array(
	 array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','filter')),                         'name' => $msg_accounts14),
	 array('link' => '?p='.$_GET['p'].'&amp;filter=disabled'.mswQueryParams(array('p','filter')),  'name' => $msg_response27),
	 array('link' => '?p='.$_GET['p'].'&amp;filter=notify'.mswQueryParams(array('p','filter')),    'name' => $msg_user80),
	 array('link' => '?p='.$_GET['p'].'&amp;filter=delpriv'.mswQueryParams(array('p','filter')),   'name' => $msg_user81),
	 array('link' => '?p='.$_GET['p'].'&amp;filter=notepad'.mswQueryParams(array('p','filter')),   'name' => $msg_user82),
	 array('link' => '?p='.$_GET['p'].'&amp;filter=assigned'.mswQueryParams(array('p','filter')),  'name' => $msg_user83)
	);
	echo $MSBOOTSTRAP->button($msg_search20,$links);
	// Page filter..
	include(PATH.'templates/system/bootstrap/page-filter.php');
	?>
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $msg_adheader58; ?> (<?php echo @number_format($countedRows); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader4; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader58; ?></li>
  </ul>
  
  <?php
  // Deleted..
  if (isset($OK)) {
    echo mswActionCompleted($msg_user13);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar" id="b1">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('?p=team')"><i class="icon-plus"></i> <?php echo $msg_adheader57; ?></button>
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
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '28' : '26'); ?>%"><?php echo $msg_user; ?></th>
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '29' : '31'); ?>%"><?php echo $msg_user4; ?></th>
          <th style="width:13%"><?php echo $msg_user77; ?></th>
		  <th style="width:17%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if (mysql_num_rows($q)>0) {
         while ($USER = mysql_fetch_object($q)) {
		 ?>
         <tr>
		  <?php
		  if (USER_DEL_PRIV=='yes') {
		  if ($USER->id>1) {
		  ?>
          <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal')" name="del[]" value="<?php echo $USER->id; ?>" id="user_<?php echo $USER->id; ?>"></td>
		  <?php
		  } else {
		  ?>
		  <td>&nbsp;</td>
		  <?php
		  }
		  }
		  ?>
          <td><?php echo $USER->id; ?></td>
          <td><?php echo mswSpecialChars($USER->name); ?></td>
          <td><?php echo mswSpecialChars($USER->email); ?></td>
		  <td><a href="?p=responses&amp;id=<?php echo $USER->id; ?>" title=""><?php echo @number_format($USER->respCount); ?></a></td>
          <td class="ms-options-links">
		    <span class="enableDisable"><i class="<?php echo ($USER->enabled=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','<?php echo $USER->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    <a href="?p=team&amp;edit=<?php echo $USER->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
			<a href="?p=responses&amp;id=<?php echo $USER->id; ?>" title="<?php echo mswSpecialChars($msg_user25); ?>"><i class="icon-comments-alt"></i></a>
			<a href="?p=graph&amp;id=<?php echo $USER->id; ?>" title="<?php echo mswSpecialChars($msg_user31); ?>"><i class="icon-bar-chart"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '6' : '5'); ?>"><?php echo $msg_user11; ?></td>
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
       <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','delete');return false;" class="btn btn-danger" disabled="disabled" type="submit" id="delButton"><i class="icon-trash"></i> <?php echo $msg_levels9; ?> <span id="mc_countVal">(0)</span></button>
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