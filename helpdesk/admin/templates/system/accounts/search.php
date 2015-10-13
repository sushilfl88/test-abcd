<?php if (!defined('PARENT')) { exit; } 
$searchParams  = '';
$countedRows   = 0;

if (!isset($_GET['orderby'])) {
  $_GET['orderby'] = 'order_asc';
}
$orderBy      = 'ORDER BY `name`';

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

if (isset($_GET['keys'])) {
  // Filters..
  if ($_GET['keys']) {
    $_GET['keys']  = mswSafeImportString(strtolower($_GET['keys']));
    $filters[]     = "LOWER(`".DB_PREFIX."portal`.`name`) LIKE '%".$_GET['keys']."%' OR LOWER(`".DB_PREFIX."portal`.`email`) LIKE '%".$_GET['keys']."%' OR LOWER(`".DB_PREFIX."portal`.`notes`) LIKE '%".$_GET['keys']."%'";
  }
  if (isset($_GET['ip']) && $_GET['ip']) {
    $filters[]  = "`ip` = '".mswSafeImportString($_GET['ip'])."'";
  }
  if (isset($_GET['from'],$_GET['to']) && $_GET['from'] && $_GET['to']) {
    $from  = $MSDT->mswDatePickerFormat($_GET['from']);
    $to    = $MSDT->mswDatePickerFormat($_GET['to']);
    $filters[]     = "DATE(FROM_UNIXTIME(`ts`)) BETWEEN '{$from}' AND '{$to}'";
  }
  if (isset($_GET['timezone']) && $_GET['timezone']) {
    $filters[]  = "`timezone` = '".mswSafeImportString($_GET['timezone'])."'";
  }
  if (isset($_GET['status']) && in_array($_GET['status'],array('yes','no'))) {
    $filters[]  = "`enabled` = '{$_GET['status']}'";
  }
  if (isset($_GET['c1'],$_GET['c2']) && $_GET['c2']>0) {
    $_GET['c1'] = (int)$_GET['c1'];
	$_GET['c2'] = (int)$_GET['c2'];
    $filters[]  = "(SELECT count(*) FROM `".DB_PREFIX."tickets` WHERE `".DB_PREFIX."portal`.`email` = `".DB_PREFIX."tickets`.`email` AND `spamFlag` = 'no') BETWEEN '{$_GET['c1']}' AND '{$_GET['c2']}'";
  }
  // Build search string..
  if (!empty($filters)) {
    for ($i=0; $i<count($filters); $i++) {
      $searchParams .= ($i ? ' AND (' : 'WHERE (').$filters[$i].')';
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
                 $searchParams
			     $orderBy
			     LIMIT $limitvalue,$limit
				 ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
  $countedRows  = (isset($c->rows) ? $c->rows : '0');
}
// Export url..
$url          = 'index.php?p='.$_GET['p'].'&amp;export=yes'.mswQueryParams(array('p','export'));
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  <?php
  include(PATH.'templates/date-pickers.php');
  ?>
  //]]>
  </script>
  
  <div class="header">
     
    <?php
	if (isset($_GET['keys']) && isset($q)) {
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
	// Page filter..
	include(PATH.'templates/system/bootstrap/page-filter.php');
	}
	
	if (isset($_GET['keys']) && isset($q)) {
	?>
    <h1 class="page-title"><?php echo $msg_search6.' ('.@number_format($countedRows).')'; ?></h1>
	<?php
	} else {
	?>
	<h1 class="page-title"><?php echo $msg_adheader56; ?></h1>
	<?php
	}
	?>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader38; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader56; ?></li>
  </ul>
  
  <?php
  // Deleted..
  if (isset($OK)) {
    echo mswActionCompleted($msg_accounts15);
  }
  ?>

  <div class="container-fluid" style="margin-top:20px">
    
	<form method="get" action="index.php" style="margin:0;padding:0" onsubmit="return ms_fieldCheck('none','none')">
	<div class="row-fluid" id="searchParams"<?php echo (isset($_GET['keys']) ? ' style="display:none"' : ''); ?>>
	  <ul class="nav nav-tabs">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-search"></i> <?php echo $msg_search; ?></a></li>
       <li><a href="#two" data-toggle="tab"><i class="icon-calendar"></i> <?php echo $msg_accounts25; ?></a></li>
       <li><a href="#three" data-toggle="tab"><i class="icon-filter"></i> <?php echo $msg_search20; ?></a></li>
      </ul>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		 
		 <label><?php echo $msg_accounts26; ?></label>
         <input type="text" class="input-xlarge" name="keys" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($_GET['keys']) ? mswSpecialChars($_GET['keys']) : ''); ?>">
         
		 <label><?php echo $msg_accounts16; ?></label>
         <input type="text" class="input-xlarge" name="ip" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($_GET['ip']) ? mswSpecialChars($_GET['ip']) : ''); ?>">
         
		 </div>
		</div>
		<div class="tab-pane fade" id="two">
		 <div class="well">
		 
		 <label><?php echo $msg_accounts27; ?></label>
         <input type="text" class="input-small" id="from" tabindex="<?php echo (++$tabIndex); ?>" name="from" value="<?php echo (isset($_GET['from']) ? mswSpecialChars($_GET['from']) : ''); ?>">
         <input type="text" class="input-small" id="to" tabindex="<?php echo (++$tabIndex); ?>" name="to" value="<?php echo (isset($_GET['to']) ? mswSpecialChars($_GET['to']) : ''); ?>">
		 
		 <label><?php echo $msg_accounts28; ?></label>
		 <select name="timezone" tabindex="<?php echo (++$tabIndex); ?>">
         <option value="0">- - - - - - -</option>
         <?php
         // TIMEZONES..
         foreach ($timezones AS $k => $v) {
         ?>
         <option value="<?php echo $k; ?>"<?php echo (isset($_GET['timezone']) ? mswSelectedItem('timezone',$k,true) : ''); ?>><?php echo $v; ?></option>
         <?php
         }
         ?>
         </select>
		 
		 </div> 
		</div>
		<div class="tab-pane fade" id="three">
		 <div class="well">
		 
		 <label><?php echo $msg_accounts29; ?></label>
		 <select name="status" tabindex="<?php echo (++$tabIndex); ?>">
		 <option value="all"<?php echo (isset($_GET['status']) ? mswSelectedItem('status','all',true) : ''); ?>>- - - - - -</option>
		 <option value="yes"<?php echo (isset($_GET['status']) ? mswSelectedItem('status','yes',true) : ''); ?>><?php echo $msg_script48; ?></option>
		 <option value="no"<?php echo (isset($_GET['status']) ? mswSelectedItem('status','no',true) : ''); ?>><?php echo $msg_script49; ?></option>
		 </select>
		 
		 <label><?php echo $msg_accounts30; ?></label>
         <input type="text" class="input-small" tabindex="<?php echo (++$tabIndex); ?>" name="c1" value="<?php echo (isset($_GET['c1']) ? mswSpecialChars($_GET['c1']) : '0'); ?>"> &amp;
         <input type="text" class="input-small" tabindex="<?php echo (++$tabIndex); ?>" name="c2" value="<?php echo (isset($_GET['c2']) ? mswSpecialChars($_GET['c2']) : '0'); ?>">
		 
		 </div>
		</div> 
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="p" value="accountsearch">
       <button class="btn btn-primary" type="submit"><i class="icon-ok"></i> <?php echo mswCleanData($msg_adheader56); ?></button>
      </div>
	  <?php
	  // Footer links..
	  if (!isset($_GET['keys'])) {
	  include(PATH.'templates/footer-links.php');
	  }
	  ?>
    </div>
	</form>
	
	<?php
	// Show search results
	if (isset($_GET['keys']) && isset($q)) {
	?>
	<form method="post" id="form" action="?p=accountsearch<?php echo mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
	<div class="row-fluid" id="searchResults">
	
	  <div class="btn-toolbar">
       <button class="btn btn-primary" type="button" onclick="ms_divHideShow('searchParams','searchResults')"><i class="icon-repeat"></i> <?php echo $msg_search21; ?></button>
	   <button class="btn" type="button" onclick="ms_windowLoc('?p=accountsearch')"><i class="icon-minus-sign"></i> <?php echo $msg_search22; ?></button>
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
       <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','delete');return false;" class="btn btn-danger" disabled="disabled" type="submit" id="delButton"><i class="icon-trash"></i> <?php echo mswSpecialChars($msg_levels9); ?> <span id="mc_countVal">(0)</span></button>
	   <?php
	   }
	   ?>
	   <button class="btn btn-primary" type="button" onclick="ms_windowLoc('<?php echo $url; ?>')"><i class="icon-cog"></i> <?php echo $msg_accounts36; ?></button>
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
	</form>
	<?php
	}
	?>
  
  </div>

</div>