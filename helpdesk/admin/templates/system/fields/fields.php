<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $_GET['edit']  = (int)$_GET['edit'];
  $EDIT          = mswGetTableData('cusfields','id',$_GET['edit']);
  checkIsValid($EDIT);
  $deptS         = explode(',',$EDIT->departments);
}
?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo (isset($EDIT->id) ? $msg_customfields11 : $msg_customfields2); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader26; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($EDIT->id) ? $msg_customfields11 : $msg_customfields2); ?></li>
  </ul>
  
  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_customfields12);
  }
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_customfields13);
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('fieldInstructions','tabArea')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-list-alt"></i> <?php echo $msg_customfields34; ?></a></li>
       <li><a href="#two" data-toggle="tab"><i class="icon-cog"></i> <?php echo $msg_customfields35; ?></a></li>
	   <li><a href="#three" data-toggle="tab"><i class="icon-random"></i> <?php echo $msg_customfields36; ?></a></li>
	  </ul>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  
		 <label class="radio inline">
		  <input type="radio" name="fieldType" value="input"<?php echo (isset($EDIT->id) && $EDIT->fieldType=='input' ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $msg_customfields6; ?>
         </label>
		 
		 <label class="radio inline">
          <input type="radio" name="fieldType" value="textarea"<?php echo (isset($EDIT->id) && $EDIT->fieldType=='textarea' ? ' checked="checked"' : ''); ?>> <?php echo $msg_customfields5; ?>
         </label>
		 
		 <label class="radio inline">
		  <input type="radio" name="fieldType" value="select"<?php echo (isset($EDIT->id) && $EDIT->fieldType=='select' ? ' checked="checked"' : ''); ?>> <?php echo $msg_customfields7; ?>
         </label>
		 
		 <label class="radio inline">
		  <input type="radio" name="fieldType" value="checkbox"<?php echo (isset($EDIT->id) && $EDIT->fieldType=='checkbox' ? ' checked="checked"' : ''); ?>> <?php echo $msg_customfields8; ?>
         </label>
		 
	     <label><br><?php echo $msg_customfields3; ?></label>
         <input type="text" class="input-xxlarge" maxlength="250" name="fieldInstructions" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->id) ? mswSpecialChars($EDIT->fieldInstructions) : ''); ?>">
         
		 <label><?php echo $msg_customfields10; ?></label>
         <textarea rows="3" cols="40" name="fieldOptions" tabindex="<?php echo (++$tabIndex); ?>" style="width:40%"><?php echo (isset($EDIT->id) ? mswSpecialChars($EDIT->fieldOptions) : ''); ?></textarea>
      
		 </div>
		</div>
		<div class="tab-pane fade" id="two">
		 <div class="well">
		  
		  <label class="checkbox">
           <input type="checkbox" name="enField" value="yes"<?php echo (isset($EDIT->id) && $EDIT->enField=='yes' ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $msg_customfields27; ?>
          </label>
		  
		  <label class="checkbox">
           <input type="checkbox" name="fieldReq" value="yes"<?php echo (isset($EDIT->id) && $EDIT->fieldReq=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_customfields9; ?>
          </label>
		  
		  <label class="checkbox">
           <input type="checkbox" name="fieldLoc[]" value="ticket"<?php echo (isset($EDIT->id) && strpos($EDIT->fieldLoc,'ticket')!==false ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $msg_customfields18; ?>
          </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="fieldLoc[]" value="reply"<?php echo (isset($EDIT->id) && strpos($EDIT->fieldLoc,'reply')!==false ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $msg_customfields19; ?>
          </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="fieldLoc[]" value="admin"<?php echo (isset($EDIT->id) && strpos($EDIT->fieldLoc,'admin')!==false ? ' checked="checked"' : (!isset($EDIT->id) ? ' checked="checked"' : '')); ?>> <?php echo $msg_customfields20; ?>
          </label>
		  
          <label class="checkbox">
           <input type="checkbox" name="repeatPref" value="yes"<?php echo (isset($EDIT->id) && $EDIT->repeatPref=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_customfields28; ?>
          </label>
		  
         </div> 
		</div>
		<div class="tab-pane fade" id="three">
		 <div class="well">
		  
		  <label class="checkbox">
		   <input type="checkbox" value="0" onclick="checkBoxes(this.checked,'#cb')"> <?php echo $msg_response6; ?>
          </label>
		 
		  <div id="cb">
		  <?php
          $q_dept = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                    or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
          while ($DEPT = mysql_fetch_object($q_dept)) {
          ?>
		  <label class="checkbox">
          <input type="checkbox" name="dept[]" value="<?php echo $DEPT->id; ?>"<?php echo (isset($EDIT->id) && in_array($DEPT->id,$deptS) ? ' checked="checked"' : ''); ?>> <?php echo mswSpecialChars($DEPT->name); ?><br>
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
       <button class="btn btn-primary" type="submit"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo mswCleanData((isset($EDIT->id) ? $msg_customfields11 : $msg_customfields2)); ?></button>
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=fieldsman')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
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