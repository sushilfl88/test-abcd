<?php if (!defined('PARENT')) { exit; } 
$SQL           = '';

if (!isset($_GET['orderby'])) {
  $_GET['orderby'] = 'user_asc';
}

if (isset($_GET['orderby'])) {
  switch ($_GET['orderby']) {
    // Protocol (ascending)..
    case 'protocol_asc':
	$orderBy = 'ORDER BY `im_protocol`';
	break;
	// Protocol (descending)..
    case 'protocol_desc':
	$orderBy = 'ORDER BY `im_protocol` desc';
	break;
	// Mailbox User (ascending)..
    case 'user_asc':
	$orderBy = 'ORDER BY `im_user`';
	break;
	// Mailbox User (descending)..
    case 'user_desc':
	$orderBy = 'ORDER BY `im_user` desc';
	break;
  }
}

if (isset($_GET['filter'])) {
  $SQL  = 'WHERE `im_piping` = \'no\'';
}

if (isset($_GET['keys']) && $_GET['keys']) {
  $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
  $SQL           = 'WHERE LOWER(`im_host`) LIKE \'%'.$_GET['keys'].'%\' OR LOWER(`im_user`) LIKE \'%'.$_GET['keys'].'%\'';
}

$q             = mysql_query("SELECT SQL_CALC_FOUND_ROWS * FROM `".DB_PREFIX."imap`
                 $SQL
				 $orderBy
				 LIMIT $limitvalue,$limit
				 ") 
                 or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c             = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows   = (isset($c->rows) ? $c->rows : '0');
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
	if ($countedRows>0) {
	$links = array(
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=protocol_asc'.mswQueryParams(array('p','orderby')),  'name' => $msg_imap35),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=protocol_desc'.mswQueryParams(array('p','orderby')), 'name' => $msg_imap36),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=user_asc'.mswQueryParams(array('p','orderby')),      'name' => $msg_imap37),
	 array('link' => '?p='.$_GET['p'].'&amp;orderby=user_desc'.mswQueryParams(array('p','orderby')),     'name' => $msg_imap38)
	);
	echo $MSBOOTSTRAP->button($msg_script45,$links);
	// Filter By..
	$links = array(
	 array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','filter','next')),  'name' => $msg_imap39),
	 array('link' => '?p='.$_GET['p'].'&amp;filter=disabled'.mswQueryParams(array('p','filter','next')), 'name' => $msg_response27)
	);
	echo $MSBOOTSTRAP->button($msg_search20,$links);
	// Page filter..
	include(PATH.'templates/system/bootstrap/page-filter.php');
	?>
	<button class="btn search-bar-button" type="button" onclick="mswToggle('b1','b2','keys')"><i class="icon-search" id="search-icon-button"></i></button>
	<?php
	}
	?>
	<h1 class="page-title"><?php echo $msg_adheader40; ?> (<?php echo @number_format($countedRows); ?>)</h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader24; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader40; ?></li>
  </ul>
  
  <?php
  // Selected deleted..
  if (isset($OK1)) {
    if ($count>0) {
      echo mswActionCompleted($msg_imap24);
    }
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <div class="btn-toolbar" id="b1">
       <button class="btn btn-primary" type="button" onclick="ms_windowLoc('?p=imap')"><i class="icon-plus"></i> <?php echo $msg_imap; ?></button>
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
          <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '32' : '37'); ?>%"><?php echo $msg_imap4; ?></th>
          <th style="width:40%"><?php echo $msg_imap8; ?></th>
          <th style="width:15%"><?php echo $msg_script43; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 if (mysql_num_rows($q)>0) {
         while ($IMAP = mysql_fetch_object($q)) {
		 ?>
         <tr>
		  <?php
		  if (USER_DEL_PRIV=='yes') {
		  ?>
          <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal')" name="del[]" value="<?php echo $IMAP->id; ?>" id="imap_<?php echo $IMAP->id; ?>"></td>
		  <?php
		  }
		  ?>
		  <td><?php echo $IMAP->id; ?></td>
          <td><?php echo mswSpecialChars($IMAP->im_protocol); ?></td>
          <td><?php echo mswSpecialChars($IMAP->im_user); ?></td>
          <td class="ms-options-links">
		    <span class="enableDisable"><i class="<?php echo ($IMAP->im_piping=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','<?php echo $IMAP->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    <a href="?p=imap&amp;edit=<?php echo $IMAP->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>"><i class="icon-pencil"></i></a>
			<a href="../?<?php echo $SETTINGS->imap_param.'='.$IMAP->id; ?>" class="nyroModal" title="<?php echo mswSpecialChars($msg_imap29); ?>"><i class="icon-envelope-alt"></i></a>
		  </td>
         </tr>
		 <?php
		 }
		 } else {
		 ?>
		 <tr class="warning nothing_to_see">
		  <td colspan="<?php echo (USER_DEL_PRIV=='yes' ? '5' : '4'); ?>"><?php echo $msg_imap21; ?></td>
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
	  ?>
	  
	  <?php
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