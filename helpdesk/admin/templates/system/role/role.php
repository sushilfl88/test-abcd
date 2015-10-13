<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $_GET['edit'] = (int)$_GET['edit'];
  $EDIT         = mswGetTableData('departments','id',$_GET['edit']);
  checkIsValid($EDIT);
}
?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo (isset($EDIT->id) ? $msg_role5 : $msg_role3); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><a href="index.php"><?php echo $msg_adheader3; ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($EDIT->id) ? $msg_role5 : $msg_role3); ?></li>
  </ul>

  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_role7);
  }
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_dept12);
  }
  ?>
  
  <form method="post" action="?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('name','tabArea')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-file-text-alt"></i> <?php echo $msg_role2; ?></a></li>
       <!-- <li><a href="#two" data-toggle="tab"><i class="icon-signin"></i> <?php echo $msg_dept25; ?></a></li> -->
      </ul>

	  <div id="myTabContent" class="tab-content">
	   <div class="tab-pane active in" id="one">
	    <div class="well">
		 
		 <label><?php echo $msg_role1; ?></label>
         <input type="text" class="input-xlarge" maxlength="100" name="name" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->name) ? mswSpecialChars($EDIT->name) : ''); ?>">
         
		 <!-- <label class="checkbox">
          <input type="checkbox" name="showDept" value="yes"<?php echo (isset($EDIT->id) && $EDIT->showDept=='yes' ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $msg_dept15; ?>
         </label> -->
		 
		 <!-- <label class="checkbox">
          <input<?php echo (isset($EDIT->id) && $EDIT->manual_assign=='yes' ? ' onclick="if(!this.checked){alert(\''.mswSpecialChars($msg_script_action5).'\')}" ' : ' '); ?>type="checkbox" name="manual_assign" value="yes"<?php echo (isset($EDIT->id) && $EDIT->manual_assign=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_dept22; ?>
         </label> -->
		
		</div>
	   </div>
	   <div class="tab-pane fade" id="two">
	    <div class="well">
		 
		 <label><?php echo $msg_dept17; ?></label>
         <input type="text" class="input-xxlarge" name="dept_subject" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->dept_subject) ? mswSpecialChars($EDIT->dept_subject) : ''); ?>">
         
		 <label><?php echo $msg_dept18; ?></label>
         <textarea rows="8" cols="40" name="dept_comments" id="dept_comments" tabindex="<?php echo (++$tabIndex); ?>"><?php echo (isset($EDIT->dept_comments) ? mswSpecialChars($EDIT->dept_comments) : ''); ?></textarea><br><br>
         
		</div>
	   </div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
       <input type="hidden" name="<?php echo (isset($EDIT->id) ? 'update' : 'process'); ?>" value="1">
       <button class="btn btn-primary" type="submit"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo (isset($EDIT->id) ? $msg_role5 : $msg_role3); ?></button>
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=deptman')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
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