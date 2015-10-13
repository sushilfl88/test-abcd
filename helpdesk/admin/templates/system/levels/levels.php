<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $_GET['edit'] = (int)$_GET['edit'];
  $EDIT         = mswGetTableData('levels','id',$_GET['edit']);
  checkIsValid($EDIT);
}
?>
<div class="content">
  
  <div class="header">
    
	<h1 class="page-title"><?php echo (isset($EDIT->id) ? $msg_levels5 : $msg_adheader50); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader52; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($EDIT->id) ? $msg_levels5 : $msg_adheader50); ?></li>
  </ul>

  <?php
  // Added..
  if (isset($OK)) {
    echo mswActionCompleted($msg_levels7);
  }
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_levels12);
  }
  ?>
  
  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('name')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-level-down"></i> <?php echo $msg_levels25; ?></a></li>
      </ul>
	  <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  
		  <label><?php echo $msg_levels18; ?></label>
          <input type="text" class="input-xlarge" maxlength="100" name="name" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->name) ? mswSpecialChars($EDIT->name) : ''); ?>">
          
		  <label class="checkbox">
           <input type="checkbox" name="display" value="yes"<?php echo (isset($EDIT->display) && $EDIT->display=='yes' ? ' checked="checked"' : (!isset($EDIT->display) ? ' checked="checked"' : '')); ?>> <?php echo $msg_levels15; ?>
          </label>
		  
		 </div>
		</div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="<?php echo (isset($EDIT->id) ? 'update' : 'process'); ?>" value="1">
       <button class="btn btn-primary" type="submit"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo mswCleanData((isset($EDIT->id) ? $msg_levels10 : $msg_levels2)); ?></button>
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=levelsman')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
       <?php
	   }
	   ?>
	  </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>

  </div>
  </form>

</div>