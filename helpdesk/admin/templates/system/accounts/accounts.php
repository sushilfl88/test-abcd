<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $_GET['edit']  = (int)$_GET['edit'];
  $EDIT          = mswGetTableData('portal','id',$_GET['edit']);
  checkIsValid($EDIT);
}
?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo (isset($EDIT->id) ? $msg_accounts6 : $msg_adheader39); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader38; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($EDIT->id) ? $msg_accounts6 : $msg_adheader39); ?></li>
  </ul>
  
  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted((isset($_POST['welcome']) ? $msg_accounts24 : $msg_accounts21));
  }
  
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_accounts22);
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('name,email<?php echo (!isset($EDIT->id) ? ',accpass' : ''); ?>','tabArea')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-user"></i> <?php echo $msg_accounts7; ?></a></li>
       <li><a href="#two" data-toggle="tab"><i class="icon-off"></i> <?php echo $msg_accounts29; ?></a></li>
	   <li><a href="#three" data-toggle="tab"><i class="icon-cog"></i> <?php echo $msg_accounts17; ?></a></li>
	   <li><a href="#four" data-toggle="tab"><i class="icon-file-text-alt"></i> <?php echo $msg_accounts18; ?></a></li>
	   <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-wrench"></i> <?php echo $msg_accounts32; ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
         <li><a href="#five" data-toggle="tab"><i class="icon-move"></i> <?php echo $msg_systemportal6; ?></a></li>
		</ul>
       </li>
	   <?php
	   }
	   ?>
      </ul>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  
		  <?php
		  if (!isset($EDIT->id)) {
		  ?>
		  <label class="checkbox">
          <input type="checkbox" name="welcome" value="yes" checked="checked"> <?php echo $msg_accounts23; ?>
          </label>
		  <?php
		  }
		  ?>
		  <label><?php echo (!isset($EDIT->id) ? '<br>' : '').$msg_user; ?></label>
          <input type="text" class="input-xlarge" name="name" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->name) ? mswSpecialChars($EDIT->name) : ''); ?>">
		  
		  <label><?php echo $msg_user4; ?></label>
          <input type="text" class="input-xlarge" name="email" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->email) ? mswSpecialChars($EDIT->email) : ''); ?>" onblur="ms_checkDataExists('<?php echo $_GET['p']; ?>','email','<?php echo (isset($EDIT->id) ? $EDIT->id : '0'); ?>')">
          
		  <label id="labelPass"><?php echo $msg_user12; ?></label>
		  <div class="input-append">
           <input type="password" class="input-xlarge" name="userPass" tabindex="<?php echo (++$tabIndex); ?>" value="">
		   <span class="add-on"><a href="#" onclick="ms_passGenerator('labelPass','userPass');return false" title="<?php echo mswSpecialChars($msg_accounts20); ?>"><i class="icon-lock"></i> </a></span>
          </div>		   
		   
		 </div>
		</div>
		<div class="tab-pane fade" id="two">
		 <div class="well">
		  
		  <label class="checkbox">
          <input type="checkbox" name="enabled" value="yes"<?php echo (isset($EDIT->enabled) && $EDIT->enabled=='yes' ? ' checked="checked"' : (!isset($EDIT->enabled) ? ' checked="checked"' : '')); ?>> <?php echo $msg_accounts19; ?>
          </label>
		  
		  <label><br><?php echo $msg_accounts31; ?></label>
		  <textarea rows="5" cols="20" name="reason"><?php echo (isset($EDIT->reason) ? mswSpecialChars($EDIT->reason) : ''); ?></textarea>
		  
		 </div>
		</div> 
		<div class="tab-pane fade" id="three">
		 <div class="well">
		  
		  <label class="checkbox">
          <input type="checkbox" name="enableLog" value="yes"<?php echo (isset($EDIT->enableLog) && $EDIT->enableLog=='yes' ? ' checked="checked"' : (!isset($EDIT->enableLog) ? ' checked="checked"' : '')); ?>> <?php echo $msg_accounts40; ?>
          </label>
		  
		  <label><br><?php echo $msg_user70; ?></label>
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
		  
		  <label><?php echo $msg_accounts39; ?></label>
          <select name="language" tabindex="<?php echo (++$tabIndex); ?>">
          <?php
          $showlang = opendir(REL_PATH.'content/language');
          while (false!==($read=readdir($showlang))) {
           if (is_dir(REL_PATH.'content/language/'.$read) && !in_array($read,array('.','..'))) {
           ?>
           <option<?php echo (isset($EDIT->language) ? mswSelectedItem($read,$EDIT->language) : ''); ?>><?php echo $read; ?></option>
           <?php
           }
          }
          closedir($showlang);
          ?>
          </select>
		  
		  <label><?php echo $msg_accounts16; ?></label>
          <input type="text" class="input-large" name="ip" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->ip) ? mswSpecialChars($EDIT->ip) : ''); ?>">
		  
		 </div> 
		</div>
		<div class="tab-pane fade" id="four">
		 <div class="well">
		 
		  <textarea rows="5" cols="20" name="notes"><?php echo (isset($EDIT->notes) ? mswSpecialChars($EDIT->notes) : ''); ?></textarea>
		 
		 </div> 
		</div>
		<?php
		if (isset($EDIT->id)) {
		?>
		<div class="tab-pane fade" id="five">
		 <div class="well">
		 
		  <label><?php echo $msg_systemportal8; ?></label>
          <div class="input-append" id="acc">
		   <input type="text" class="input-xlarge" name="dest_email" tabindex="<?php echo (++$tabIndex); ?>" value="" onkeyup="if(jQuery('#acc-search')){jQuery('#acc-search').hide()}">
           <span class="add-on"><a href="#" onclick="searchAccounts('<?php echo str_replace("'","\'",$msg_add7); ?>','email','acc','<?php echo $EDIT->id; ?>');return false" title="<?php echo mswSpecialChars($msg_add6); ?>"><i class="icon-search"></i> </a></span>
         </div>
		  
		  </div> 
		</div>
		<?php
		}
		?>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="<?php echo (isset($EDIT->id) ? 'update' : 'process'); ?>" value="1">
	   <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <input type="hidden" name="old_pass" value="<?php echo mswSpecialChars($EDIT->userPass); ?>">
	   <input type="hidden" name="old_email" value="<?php echo mswSpecialChars($EDIT->email); ?>">
	   <?php
	   }
	   ?>
       <button class="btn btn-primary" type="submit"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo (isset($EDIT->id) ? $msg_accounts6 : $msg_accounts4); ?></button>
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=accountman')"><i class="icon-remove"></i> <?php echo $msg_levels11; ?></button>
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