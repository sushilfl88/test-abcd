<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $_GET['edit']  = (int)$_GET['edit'];
  $EDIT          = mswGetTableData('responses','id',$_GET['edit']);
  checkIsValid($EDIT);
  $deptArr       = ($EDIT->departments!='0' ? explode(',',$EDIT->departments) : array());
}
?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo (isset($_GET['edit']) ? $msg_response13 : $msg_adheader53); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader13; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($_GET['edit']) ? $msg_response13 : $msg_adheader53); ?></li>
  </ul>
  
  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_response7);
  }
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_response8);
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('title','tabArea')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab" onclick="jQuery('#prev').show();return false;"><i class="icon-file-text-alt"></i> <?php echo $msg_response19; ?></a></li>
       <li><a href="#two" data-toggle="tab" onclick="jQuery('#prev').hide();return false"><i class="icon-random"></i> <?php echo $msg_response20; ?></a></li>
      </ul>

	  <div id="myTabContent" class="tab-content">
	   <div class="tab-pane active in" id="one">
	    <div class="well">
	     
		 <label class="checkbox">
		  <input type="checkbox" name="enResponse" value="yes"<?php echo (isset($EDIT->enResponse) && $EDIT->enResponse=='yes' ? ' checked="checked"' : (!isset($EDIT->enResponse) ? ' checked="checked"' : '')); ?>> <?php echo $msg_response21; ?>
         </label>
		
		 <label><br><?php echo $msg_response; ?></label>
         <input type="text" class="input-xxlarge" name="title" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->id) ? mswSpecialChars($EDIT->title) : ''); ?>">
      
		 <label><?php echo $msg_response2; ?></label>
         <?php
         define('BB_BOX','answer');
         include(PATH.'templates/system/bbcode-buttons.php');
         ?>
         <textarea rows="8" cols="40" name="answer" id="answer" tabindex="<?php echo (++$tabIndex); ?>"><?php echo (isset($EDIT->id) ? mswSpecialChars($EDIT->answer) : ''); ?></textarea>
         <?php
		 // Preview area..do not remove empty div
		 ?>
		 <div id="previewArea" class="previewArea prevSR" onclick="ms_closePreview('answer','previewArea')"></div>
		
		</div>
	   </div>
	   <div class="tab-pane fade" id="two">
	    <div class="well">
		 
		 <label class="checkbox">
		  <input type="checkbox" value="0" onclick="checkBoxes(this.checked,'#cb')"> <?php echo $msg_response6; ?>
         </label>
		 
		 <div id="cb">
		 <?php
         // If global log in no filter necessary..
         $q_dept = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                   or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($DEPT = mysql_fetch_object($q_dept)) {
         ?>
         <label class="checkbox">
		  <input type="checkbox" name="dept[]"<?php echo (isset($deptArr) && in_array($DEPT->id,$deptArr) ? ' checked="checked" ' : ' '); ?>value="<?php echo $DEPT->id; ?>"> <?php echo mswSpecialChars($DEPT->name); ?>
         </label>
		 <input type="hidden" name="deptall[]" value="<?php echo $DEPT->id; ?>">
		 <?php
         }
         ?>
         </div>
		 
		</div>
	   </div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="<?php echo (isset($EDIT->id) ? 'update' : 'process'); ?>" value="1">
       <button class="btn btn-primary"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo mswCleanData((isset($EDIT->id) ? $msg_response13 : $msg_response3)); ?></button>
       <button class="btn" type="button" onclick="ms_textPreview('standard-responses','answer','previewArea')" id="prev"><i class="icon-search"></i> <?php echo mswCleanData($msg_viewticket55); ?></button>
	   <button class="btn" type="button" onclick="ms_closePreview('answer','previewArea')" style="display:none" id="clse"><i class="icon-remove"></i> <?php echo mswCleanData($msg_viewticket101); ?></button>
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=responseman')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
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