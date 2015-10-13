<?php if (!defined('PARENT')) { exit; } 
$_GET['disputeUsers'] = (int)$_GET['disputeUsers'];
$q                    = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
                        `".DB_PREFIX."disputes`.`id` AS `disputeID`,
						`".DB_PREFIX."portal`.`id` AS `portalID`,
                        (SELECT count(*) FROM `".DB_PREFIX."replies` WHERE `".DB_PREFIX."replies`.`disputeUser` = `".DB_PREFIX."disputes`.`id` 
						  AND `".DB_PREFIX."replies`.`ticketID` = '{$_GET['disputeUsers']}') AS `tickRepCount`
						FROM `".DB_PREFIX."disputes` 
						LEFT JOIN `".DB_PREFIX."portal`
						ON `".DB_PREFIX."disputes`.`visitorID`  = `".DB_PREFIX."portal`.`id`
						WHERE `ticketID`                        = '{$_GET['disputeUsers']}' 
						ORDER BY `name`
						") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
$c                    = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
$countedRows          = (isset($c->rows) ? $c->rows : '0');
// Load ticket data..
$SUPTICK              = mswGetTableData('tickets','id',$_GET['disputeUsers']);
if (isset($SUPTICK->visitorID)) {
$PORTAL               = mswGetTableData('portal','id',$SUPTICK->visitorID);
}
// Check we have all data..
if (!isset($PORTAL->name)) {
  die('An error has occured. Portal data not found for visitor ID:  '.$SUPTICK->visitorID);
}
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  function ms_userBox(type,max) {
    switch (type) {
      case 'add':
      var n = parseInt(jQuery('#tabArea li').length-1);
      if (n<max) {
	    var nextTab = parseInt(n+1);
	    jQuery('div[class="tab-content"]').append('<div class="tab-pane fade" id="tab'+nextTab+'">'+jQuery('div[id="tab1"]').html()+'</div>');
		jQuery('div[id="tab'+nextTab+'"] input').val('');
		jQuery('#tabArea li').last().after('<li><a href="#tab'+nextTab+'" data-toggle="tab"><i class="icon-user"></i> '+nextTab+'</a></li>');
		// Adjust onclick methods..
		jQuery('#tab'+nextTab+' span[class="add-on namebox"] a').attr('onclick','searchDisputeAccount(\'name\',\'tab'+nextTab+'\',\'<?php echo $_GET['disputeUsers']; ?>\','+nextTab+')');
		jQuery('#tab'+nextTab+' span[class="add-on emailbox"] a').attr('onclick','searchDisputeAccount(\'email\',\'tab'+nextTab+'\',\'<?php echo $_GET['disputeUsers']; ?>\','+nextTab+')');
		// Adjust tab ids for input data..
		jQuery('#tab'+nextTab+' input[name="name[1]"]').attr('name','name['+nextTab+']');
		jQuery('#tab'+nextTab+' input[name="email[1]"]').attr('name','email['+nextTab+']');
		jQuery('#tab'+nextTab+' input[name="send[1]"]').attr('name','send['+nextTab+']');
		jQuery('#tab'+nextTab+' input[name="priv[1]"]').attr('name','priv['+nextTab+']');
		jQuery('#mc_countVal2').html('('+nextTab+')');
		jQuery('#tabArea a[href="#tab'+nextTab+'"]').tab('show');
      }
      break;
      case 'remove':
      var n = parseInt(jQuery('#tabArea li').length-1);
      if (n>1) {
	    var lastTab = parseInt(n-1);
        jQuery('#tabArea li').last().remove();
		jQuery('div[class="tab-pane fade"]').last().remove();
		jQuery('#mc_countVal2').html('('+parseInt(n-1)+')');
		jQuery('#tabArea a[href="#tab'+lastTab+'"]').tab('show');
      }
      break;
    }
  }
  function ms_toggleDisArea(area) {
    switch (area) {
	  case 'first':
	  ms_divHideShow('main_button_area','add_button_area');
	  break;
	  case 'second':
	  ms_divHideShow('add_button_area','main_button_area');
	  break;
	}
  }
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_disputes8; ?>: #<?php echo mswTicketNumber($_GET['disputeUsers']); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader41; ?> <span class="divider">/</span></li>
    <li><a href="?p=view-dispute&amp;id=<?php echo $_GET['disputeUsers']; ?>"><?php echo $msg_viewticket96; ?></a> <span class="divider">/</span></li>
	<li class="active"><?php echo $msg_disputes8; ?></li>
  </ul>
  
  <?php
  // Add user..
  if (isset($OK1)) {
    echo mswActionCompleted(str_replace('{count}',@number_format($count),$msg_viewticket68));
  }
  // Remove..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_viewticket114);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#start" data-toggle="tab" onclick="ms_toggleDisArea('first');return false"><i class="icon-comments-alt"></i> <?php echo $msg_script_action4; ?></a></li>
       <li><a href="#tab1" data-toggle="tab" onclick="ms_toggleDisArea('second');return false"><i class="icon-user"></i> <?php echo $msg_viewticket58; ?></a></li>
      </ul>
	  
	  <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="start">
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
		    <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '35' : '38'); ?>%"><?php echo $msg_accounts; ?></th>
		    <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '35' : '37'); ?>%"><?php echo $msg_accounts2; ?></th>
			<th style="width:10%"><?php echo $msg_viewticket106; ?></th>
            <th style="width:15%"><?php echo $msg_viewticket105; ?></th>
           </tr>
          </thead>
          <tbody>
		   <tr class="warning">
            <td>&nbsp;</td>
		    <td><?php echo mswCleanData($PORTAL->name); ?> <a href="?p=accounts&amp;edit=<?php echo $PORTAL->id; ?>" style="font-size:11px"><i class="icon-pencil"></i></a></td>
            <td><?php echo mswCleanData($PORTAL->email); ?></td>
			<td><?php echo (mswRowCount('replies WHERE `ticketID` = \''.$_GET['disputeUsers'].'\' AND `replyType` = \'visitor\' AND `disputeUser` = \'0\'')+1); ?></td>
		    <td class="ms-options-links">
		      <span class="enableDisable"><i class="<?php echo ($SUPTICK->disPostPriv=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','t<?php echo $SUPTICK->id; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    </td>
           </tr>
		   <?php
		   while ($ACC = mysql_fetch_object($q)) {
		   ?>
           <tr>
            <?php
		    if (USER_DEL_PRIV=='yes') {
		    ?>
            <td><input type="checkbox" onclick="ms_checkCount('well','delButton','mc_countVal')" name="del[<?php echo $ACC->disputeID; ?>]" value="<?php echo mswSpecialChars($ACC->name); ?>" id="acc_<?php echo $ACC->disputeID; ?>"></td>
		    <?php
		    }
		    ?>
		    <td><?php echo mswCleanData($ACC->name); ?> <a href="?p=accounts&amp;edit=<?php echo $ACC->portalID; ?>" style="font-size:11px"><i class="icon-pencil"></i></a></td>
            <td><?php echo mswCleanData($ACC->email); ?></td>
			<td><?php echo number_format($ACC->tickRepCount); ?></td>
		    <td class="ms-options-links">
		      <span class="enableDisable"><i class="<?php echo ($ACC->postPrivileges=='yes' ? 'icon-flag' : 'icon-flag-alt'); ?>" onclick="ms_enableDisable(this,'<?php echo $_GET['p']; ?>','p<?php echo $ACC->disputeID; ?>')" title="<?php echo mswSpecialChars($msg_response28); ?>"></i></span>
		    </td>
           </tr>
		   <?php
		   }
		   ?>
           </tbody>
           </table>
		 </div>
		</div>
		<div class="tab-pane fade" id="tab1">
		 <div class="well" style="margin-bottom:10px">
		  
		   <label><?php echo $msg_user; ?></label>
		   <div class="input-append">
		    <input type="text" class="input-xlarge" name="name[1]" value="">
		    <span class="add-on namebox"><a href="#" onclick="searchDisputeAccount('name','tab1','<?php echo $_GET['disputeUsers']; ?>','1')" title="<?php echo mswSpecialChars($msg_add6); ?>"><i class="icon-search"></i> </a></span>
           </div>
		   
		   <label><?php echo $msg_user4; ?></label>
		   <div class="input-append">
		    <input type="text" class="input-xlarge" name="email[1]" value="">
            <span class="add-on emailbox"><a href="#" onclick="searchDisputeAccount('email','tab1','<?php echo $_GET['disputeUsers']; ?>','1')" title="<?php echo mswSpecialChars($msg_add6); ?>"><i class="icon-search"></i> </a></span>
           </div>
		   
		   <label class="checkbox">
		    <input type="checkbox" name="send[1]" value="yes" checked="checked"> <?php echo $msg_viewticket115; ?>
		   </label>
		   
		   <label class="checkbox">
		    <input type="checkbox" name="priv[1]" value="yes" checked="checked"> <?php echo $msg_viewticket116; ?>
		   </label>
		 
		 </div>
	    </div>
	  </div>
	  <?php
	  if ($countedRows>0) {
	  ?>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0" id="main_button_area">
	    <?php
		if (USER_DEL_PRIV=='yes') {
		?>
        <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','removeusers');return false;" class="btn btn-danger" disabled="disabled" type="submit" id="delButton"><i class="icon-trash"></i> <?php echo $msg_viewticket65; ?> <span id="mc_countVal">(0)</span></button>
		<?php
		}
		?>
	    <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=view-dispute&amp;id=<?php echo $_GET['disputeUsers']; ?>')"><i class="icon-remove"></i> <?php echo mswSpecialChars($msg_levels11); ?></button>
      </div>
	  <?php
	  }
	  ?>
	  <div class="row-fluid" id="add_button_area" style="display:none">
	    <div class="pull-left">
		 <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','add');return false;" class="btn btn-primary" type="submit"><i class="icon-plus"></i> <?php echo $msg_viewticket67; ?> <span id="mc_countVal2">(1)</span></button>
		</div>
		<div class="pull-right">
		 <button class="btn" type="button" title="<?php echo mswSpecialChars($msg_script46); ?>" onclick="ms_userBox('add','<?php echo (LICENCE_VER=='locked' ? RESTR_DISPUTE : '9999999'); ?>')">+</button>
         <button class="btn" type="button" title="<?php echo mswSpecialChars($msg_script47); ?>" onclick="ms_userBox('remove','')">-</button>
        </div>
		<span class="clearfix"></span>
	  </div> 
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
  
  </div>
  </form>

</div>