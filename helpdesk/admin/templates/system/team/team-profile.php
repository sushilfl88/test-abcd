<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
  
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_staffprofile2; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader4; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_staffprofile2; ?></li>
  </ul>

  <?php
  // Updated..
  if (isset($OK)) {
    echo mswActionCompleted($msg_staffprofile);
	$MSTEAM = mswGetTableData('users','id',$MSTEAM->id);
  }
  ?>
  
  <div class="container-fluid" style="margin-top:20px">
    
	<form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('name,email','tabArea')">
    <div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-user"></i> <?php echo $msg_user73; ?></a></li>
	   <li><a href="#two" data-toggle="tab"><i class="icon-cog"></i> <?php echo $msg_user76; ?></a></li>
	   <li><a href="#three" data-toggle="tab"><i class="icon-pencil"></i> <?php echo $msg_user19; ?></a></li>
	  </ul>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  
		  <label><?php echo $msg_user; ?></label>
          <input type="text" class="input-xlarge" name="name" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($MSTEAM->name) ? mswSpecialChars($MSTEAM->name) : ''); ?>">
		  
		  <label><?php echo $msg_user4; ?></label>
          <input type="text" class="input-xlarge" name="email" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($MSTEAM->email) ? mswSpecialChars($MSTEAM->email) : ''); ?>" onkeyup="ms_checkDataExists('<?php echo $_GET['p']; ?>','email','<?php echo (isset($MSTEAM->id) ? $MSTEAM->id : '0'); ?>')">
      
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
          <option value="<?php echo $k; ?>"<?php echo (isset($MSTEAM->timezone) ? mswSelectedItem($MSTEAM->timezone,$k) : ''); ?>><?php echo $v; ?></option>
          <?php
          }
          ?>
          </select>
		  
		  </div>
		</div>
		<div class="tab-pane fade" id="two">
		 <div class="well">
		  
		  <label><?php echo $msg_user65; ?></label>
          <input type="text" class="input-xlarge" name="nameFrom" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($MSTEAM->nameFrom) ? mswSpecialChars($MSTEAM->nameFrom) : ''); ?>">
      
		  <label><?php echo $msg_user66; ?></label>
          <input type="text" class="input-xlarge" name="emailFrom" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($MSTEAM->emailFrom) ? mswSpecialChars($MSTEAM->emailFrom) : ''); ?>">
          
		  <label><?php echo $msg_user85; ?></label>
          <input type="text" class="input-xlarge" name="email2" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($MSTEAM->email2) ? mswSpecialChars($MSTEAM->email2) : ''); ?>">
      
		 </div> 
		</div>
		<div class="tab-pane fade" id="three">
		 <div class="well">
		 
		  <label><?php echo $msg_user17; ?></label>
          <textarea rows="8" cols="40" name="signature" class="siggie" tabindex="<?php echo (++$tabIndex); ?>"><?php echo (isset($MSTEAM->signature) ? mswSpecialChars($MSTEAM->signature) : ''); ?></textarea>
          
		 </div>
		</div> 
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="process" value="1">
	   <input type="hidden" name="old_pass" value="<?php echo $MSTEAM->accpass; ?>">
	   <button class="btn btn-primary" type="submit"><i class="icon-ok"></i> <?php echo $msg_staffprofile2; ?></button>
       <?php
	   if (isset($MSTEAM->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=home')"><i class="icon-remove"></i> <?php echo $msg_levels11; ?></button>
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