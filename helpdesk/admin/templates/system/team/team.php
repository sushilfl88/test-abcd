<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $_GET['edit']         = (int)$_GET['edit'];
  $EDIT                 = mswGetTableData('users','id',$_GET['edit']);
  checkIsValid($EDIT);
  $ePageAccess          = mswGetUserPageAccess($_GET['edit']);
  $eDeptAccess          = mswGetDepartmentAccess($_GET['edit']);
  $mswDeptFilterAccess  = mswDeptFilterAccess($MSTEAM,$eDeptAccess,'department');
}
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
    
	<h1 class="page-title"><?php echo (isset($EDIT->id) ? $msg_user14 : $msg_adheader57); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader4; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($EDIT->id) ? $msg_user14 : $msg_adheader57); ?></li>
  </ul>

  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted((isset($_POST['welcome']) ? $msg_user41 : $msg_user6));
  }
  
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_user15);
  }
  ?>
  
  <div class="container-fluid" style="margin-top:20px">
    
	<form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('name,email','tabArea')">
    <div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-user"></i> <?php echo $msg_user73; ?></a></li>
	   <?php
	   if (!isset($EDIT->id) || (isset($EDIT->id) && $EDIT->id>1)) {
	   ?>
       <li><a href="#two" data-toggle="tab"><i class="icon-lock"></i> <?php echo $msg_user74; ?></a></li>
	   <li><a href="#three" data-toggle="tab"><i class="icon-folder-open"></i> <?php echo $msg_user75; ?></a></li>
	   <?php
	   }
	   ?>
	   <li><a href="#four" data-toggle="tab"><i class="icon-cog"></i> <?php echo $msg_user76; ?></a></li>
	   <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-wrench"></i> <?php echo $msg_settings85; ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
         <li><a href="#seven" data-toggle="tab"><i class="icon-envelope"></i> <?php echo $msg_adheader61; ?></a></li>
         <li><a href="#eight" data-toggle="tab"><i class="icon-asterisk"></i> <?php echo $msg_user104; ?></a></li>
         <li><a href="#five" data-toggle="tab"><i class="icon-pencil"></i> <?php echo $msg_user19; ?></a></li>
	     <li><a href="#six" data-toggle="tab"><i class="icon-file"></i> <?php echo $msg_accounts18; ?></a></li>
		</ul>
       </li>
      </ul>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  <label class="checkbox">
          <input type="checkbox" name="enabled" value="yes"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='yes' ? ' checked="checked"' : (!isset($EDIT->enabled) ? ' checked="checked"' : '')); ?>> <?php echo $msg_accounts19; ?>
          </label>
		  <?php
		  if (!isset($EDIT->id)) {
		  ?>
		  <label class="checkbox">
          <input type="checkbox" name="welcome" value="yes" checked="checked"> <?php echo $msg_accounts23; ?>
          </label>
		  <?php
		  }
		  ?>
		 
		  <label><br><?php echo $msg_user; ?></label>
          <input type="text" class="input-xlarge" name="name" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->name) ? mswSpecialChars($EDIT->name) : ''); ?>">
		  
		  <label><?php echo $msg_user4; ?></label>
          <input type="text" class="input-xlarge" name="email" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->email) ? mswSpecialChars($EDIT->email) : ''); ?>" onblur="ms_checkDataExists('<?php echo $_GET['p']; ?>','email','<?php echo (isset($EDIT->id) ? $EDIT->id : '0'); ?>')">
      
          <label id="labelPass"><?php echo $msg_user12; ?></label>
		  <div class="input-append">
          <input type="password" class="input-xlarge" name="accpass" tabindex="<?php echo (++$tabIndex); ?>" value="">
		   <span class="add-on"><a href="#" onclick="ms_passGenerator('labelPass','accpass');return false" title="<?php echo mswSpecialChars($msg_accounts20); ?>"><i class="icon-lock"></i> </a></span>
          </div>
         
          <label><?php echo $msg_user70; ?></label>
          <select name="timezone" tabindex="<?php echo (++$tabIndex); ?>">
          <option value="0">- - - - - - -</option>
          <?php
          // TIMEZONES..
          foreach ($timezones AS $k => $v) {
          ?>
          <option value="<?php echo $k; ?>"<?php echo (isset($EDIT->timezone) ? mswSelectedItem($EDIT->timezone,$k) : ''); ?>><?php echo $v; ?></option>
          <?php
          }
          ?>
          </select>
		  
		  </div>
		</div>
		<?php
	    if (!isset($EDIT->id) || (isset($EDIT->id) && $EDIT->id>1)) {
	    ?>
		<div class="tab-pane fade" id="two">
		 <div class="well" style="max-height:300px;overflow:auto">
		  
		  <?php
		  $countAdminPages = count($adminPages);
		  $howManyParents  = ceil($countAdminPages/2);
		  ?>
		  <div class="row-fluid span9">
		    
			<div class="span6">
			<?php
			for ($i=0; $i<$howManyParents; $i++) {
			?>
			<label class="checkbox"<?php echo ($i>0 ? ' style="margin-top:15px"' : ''); ?>>
             <input onclick="checkBoxes(this.checked,'.boxes_<?php echo $i; ?>')" type="checkbox"> <b><?php echo $adminPages[$i]['title']; ?></b>
            </label>
			<div class="boxes_<?php echo $i; ?>">
			<?php
			foreach ($adminPages[$i] AS $k => $v) {
			if ($k!='title') {
			?>
			<label class="checkbox">
             <input type="checkbox" name="accessPages[]" value="<?php echo $k; ?>"<?php echo (isset($EDIT->id) && in_array($k,$ePageAccess) ? ' checked="checked"' : ''); ?>> <?php echo $v; ?>
            </label>
		    <?php
			}
			}
			?>
			</div>
			<?php
			}
			?>
		    </div>
		    
		    <div class="span6">
			<?php
			for ($i=$howManyParents; $i<$countAdminPages; $i++) {
			?>
			<label class="checkbox" <?php echo ($i>$howManyParents ? ' style="margin-top:15px"' : ''); ?>>
             <input onclick="checkBoxes(this.checked,'.boxes_<?php echo $i; ?>')" type="checkbox"> <b><?php echo $adminPages[$i]['title']; ?></b>
            </label>
			<div class="boxes_<?php echo $i; ?>">
		    <?php
			foreach ($adminPages[$i] AS $k => $v) {
			if ($k!='title') {
			?>
			<label class="checkbox">
             <input type="checkbox" name="accessPages[]" value="<?php echo $k; ?>"<?php echo (isset($EDIT->id) && in_array($k,$ePageAccess) ? ' checked="checked"' : ''); ?>> <?php echo $v; ?>
            </label>
		    <?php
			}
			}
			?>
			</div>
			<?php
			}
			?>
		    </div>
		    
		    <span class="clearfix"></span>
		  </div>
		  
		  <span class="clearfix"></span>
		  
		  <label><br><?php echo $msg_user100; ?></label>
          <input type="text" class="input-xlarge" name="addpages" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->addpages) ? mswSpecialChars($EDIT->addpages) : ''); ?>">
		  
		 </div> 
		</div>
		<div class="tab-pane fade" id="three">
		 <div class="well">
		  <div class="overflow deptboxes">
		  <label class="checkbox">
           <input onclick="checkBoxes(this.checked,'.deptboxes')" type="checkbox" name="all" value="all"<?php echo (isset($eDeptAccess) && mswRowCount('departments')==count($eDeptAccess) ? ' checked="checked"' : ''); ?>> <b><?php echo $msg_user56; ?></b>
          </label>
		  <?php
          // If global log in no filter necessary..
          $q_dept = mysql_query("SELECT * FROM ".DB_PREFIX."departments ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                    or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
          while ($DEPT = mysql_fetch_object($q_dept)) {
          ?>
		  <label class="checkbox">
           <input type="checkbox" name="dept[]" value="<?php echo $DEPT->id; ?>"<?php echo (isset($EDIT->id) && in_array($DEPT->id,$eDeptAccess) ? ' checked="checked"' : ''); ?>> <?php echo mswSpecialChars($DEPT->name); ?>
          </label>
		  <?php
          }
          ?>
          </div>
		  
		  <label class="checkbox" style="margin-top:20px">
		   <input type="checkbox" name="assigned" value="yes"<?php echo (isset($EDIT->assigned) && $EDIT->assigned=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_user69; ?>
		  </label>
		 </div> 
		</div>
		<?php
		}
		?>
		<div class="tab-pane fade" id="four">
		 <div class="well">
		  <label class="checkbox">
		   <input type="checkbox" name="notePadEnable" value="yes"<?php echo (isset($EDIT->notePadEnable) && $EDIT->notePadEnable=='yes' ? ' checked="checked"' : (!isset($EDIT->notePadEnable) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user54; ?> 
		  </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="notify" value="yes"<?php echo (isset($EDIT->notify) && $EDIT->notify=='yes' ? ' checked="checked"' : (!isset($EDIT->notify) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user18; ?>
		  </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="enableLog" value="yes"<?php echo (isset($EDIT->enableLog) && $EDIT->enableLog=='yes' ? ' checked="checked"' : (!isset($EDIT->enableLog) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user91; ?>
		  </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="mergeperms" value="yes"<?php echo (isset($EDIT->mergeperms) && $EDIT->mergeperms=='yes' ? ' checked="checked"' : (!isset($EDIT->mergeperms) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user101; ?>
		  </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="profile" value="yes"<?php echo (isset($EDIT->profile) && $EDIT->profile=='yes' ? ' checked="checked"' : (!isset($EDIT->profile) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user107; ?>
		  </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="helplink" value="yes"<?php echo (isset($EDIT->helplink) && $EDIT->helplink=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_user108; ?>
		  </label>
		  
		  <?php
          if ($SETTINGS->ticketHistory=='yes') {
          ?>
		  <label class="checkbox">
		   <input type="checkbox" name="ticketHistory" value="yes"<?php echo (isset($EDIT->ticketHistory) && $EDIT->ticketHistory=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_user90; ?>
		  </label>
		  <?php
		  }
          if (USER_DEL_PRIV=='yes') {
          ?>
          <label class="checkbox">
		   <input type="checkbox" name="delPriv" value="yes"<?php echo (isset($EDIT->delPriv) && $EDIT->delPriv=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_user64; ?>
		  </label>
		  <?php
          }
		  ?>
		  
		  <label><br><?php echo $msg_user65; ?></label>
          <input type="text" class="input-xlarge" name="nameFrom" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->nameFrom) ? mswSpecialChars($EDIT->nameFrom) : ''); ?>">
      
		  <label><?php echo $msg_user66; ?></label>
          <input type="text" class="input-xlarge" name="emailFrom" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->emailFrom) ? mswSpecialChars($EDIT->emailFrom) : ''); ?>">
          
		  <label><?php echo $msg_user85; ?></label>
          <input type="text" class="input-xlarge" name="email2" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->email2) ? mswSpecialChars($EDIT->email2) : ''); ?>">
      
		 </div> 
		</div>
		<div class="tab-pane fade" id="five">
		 <div class="well">
		 
		  <label class="checkbox">
		   <input type="checkbox" name="emailSigs" value="yes"<?php echo (isset($EDIT->emailSigs) && $EDIT->emailSigs=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_user45; ?>
		  </label>
		 
		  <label><br><?php echo $msg_user17; ?></label>
          <textarea rows="8" cols="40" name="signature" class="siggie" tabindex="<?php echo (++$tabIndex); ?>"><?php echo (isset($EDIT->signature) ? mswSpecialChars($EDIT->signature) : ''); ?></textarea>
          
		 </div>
		</div> 
		
		<div class="tab-pane fade" id="six">
		 <div class="well">
		 
		  <textarea rows="5" cols="20" name="notes"><?php echo (isset($EDIT->notes) ? mswSpecialChars($EDIT->notes) : ''); ?></textarea>
		 
		 </div> 
		</div>
		
		<div class="tab-pane fade" id="seven">
		 <div class="well">
		 
		  <label class="checkbox">
		   <input type="checkbox" name="mailbox" value="yes"<?php echo (isset($EDIT->mailbox) && $EDIT->mailbox=='yes' ? ' checked="checked"' : (!isset($EDIT->mailbox) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user95; ?> 
		  </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="mailDeletion" value="yes"<?php echo (isset($EDIT->mailDeletion) && $EDIT->mailDeletion=='yes' ? ' checked="checked"' : (!isset($EDIT->mailDeletion) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user96; ?> 
		  </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="mailScreen" value="yes"<?php echo (isset($EDIT->mailScreen) && $EDIT->mailScreen=='yes' ? ' checked="checked"' : (!isset($EDIT->mailScreen) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user97; ?> 
		  </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="mailCopy" value="yes"<?php echo (isset($EDIT->mailCopy) && $EDIT->mailCopy=='yes' ? ' checked="checked"' : (!isset($EDIT->mailCopy) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user98; ?> 
		  </label>
		  
		  <label><br><?php echo $msg_user99; ?></label>
          <input type="text" class="input-small" name="mailFolders" maxlength="3" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->mailFolders) ? (int)$EDIT->mailFolders : 5); ?>">
		 
		  <label><?php echo $msg_user106; ?></label>
          <input type="text" class="input-small" name="mailPurge" maxlength="3" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->mailPurge) ? (int)$EDIT->mailPurge : 0); ?>">
		 
		 </div> 
		</div>
		
		<div class="tab-pane fade" id="eight">
		 <div class="well">
		  
		  <label class="checkbox">
		   <input type="checkbox" name="digest" value="yes"<?php echo (isset($EDIT->digest) && $EDIT->digest=='yes' ? ' checked="checked"' : (!isset($EDIT->digest) ? ' checked="checked"' : '')); ?>> <?php echo $msg_user102; ?>
		  </label>
		  
		  <label class="checkbox">
		   <input type="checkbox" name="digestasg" value="yes"<?php echo (isset($EDIT->digestasg) && $EDIT->digestasg=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_user103; ?>
		  </label>
		  
		  <p style="text-align:right">
		   <a href="../email-digest.php" class="nyroModal"><i class="icon-cog"></i> <?php echo $msg_user105; ?></a>
		  </p>
		 
		 </div> 
		</div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="<?php echo (isset($EDIT->id) ? 'update' : 'process'); ?>" value="1">
	   <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <input type="hidden" name="old_pass" value="<?php echo $EDIT->accpass; ?>">
	   <?php
	   }
	   ?>
       <button class="btn btn-primary" type="submit"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo (isset($EDIT->id) ? $msg_user14 : $msg_adheader57); ?></button>
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=teamman')"><i class="icon-remove"></i> <?php echo $msg_levels11; ?></button>
       <?php
	   }
	   ?>
	  </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
    </form>
	
  </div>

</div>