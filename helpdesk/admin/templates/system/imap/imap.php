<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $_GET['edit']  = (int)$_GET['edit'];
  $EDIT          = mswGetTableData('imap','id',$_GET['edit']);
  checkIsValid($EDIT);
}
?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo (isset($EDIT->id) ? $msg_imap25 : $msg_adheader39); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader24; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($EDIT->id) ? $msg_imap25 : $msg_adheader39); ?></li>
  </ul>
  
  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_imap22);
  }
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_imap23);
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('none','none')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-envelope-alt"></i> <?php echo $msg_imap32; ?></a></li>
       <li><a href="#two" data-toggle="tab"><i class="icon-cog"></i> <?php echo $msg_imap33; ?></a></li>
	   <li><a href="#three" data-toggle="tab"><i class="icon-edit"></i> <?php echo $msg_imap34; ?></a></li>
	   <li><a href="#four" data-toggle="tab"><i class="icon-food"></i> <?php echo $msp_imap40; ?></a></li>
	  </ul>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  
		  <label class="checkbox">
           <input type="checkbox" name="im_piping" value="yes"<?php echo (isset($EDIT->im_piping) && $EDIT->im_piping=='yes' ? ' checked="checked"' : (!isset($EDIT->im_piping) ? ' checked="checked"' : '')); ?>> <?php echo $msg_imap3; ?>
          </label>
          
		  <label><br><?php echo $msg_imap7.' / '.$msg_imap10; ?></label>
          <input type="text" class="input-xlarge" maxlength="100" name="im_host" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->im_host) ? $EDIT->im_host : ''); ?>"> /
		  <input type="text" class="input-small" maxlength="5" name="im_port" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->im_port) ? $EDIT->im_port : '143'); ?>">
		  
		  <label><?php echo $msg_imap8; ?></label>
          <input type="text" class="input-xlarge" maxlength="250" name="im_user" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->im_user) ? mswSpecialChars($EDIT->im_user) : ''); ?>">
          
		  <label><?php echo $msg_imap9; ?></label>
          <input type="password" class="input-xlarge" maxlength="100" name="im_pass" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->im_pass) ? mswSpecialChars($EDIT->im_pass) : ''); ?>">
          
		  <label><?php echo $msg_imap11; ?></label>
		  <div class="input-append">
            <input type="text" class="input-xlarge" name="im_name" maxlength="50" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->im_name) ? $EDIT->im_name : 'inbox'); ?>">
			<span class="add-on" id="im_name"><a href="#" onclick="if(ms_folderCheck()){ms_showImapFolders('im_name','fname');return false;}" title="<?php echo mswSpecialChars($msg_imap31); ?>"><i class="icon-folder-open"></i></a></span>
            <select style="margin-left:5px;display:none" class="fname" onclick="if(this.value!='0'){ms_insertMailBox(this.value,'im_name','fname')}"><option>1</option></select>
		  </div>
		  
		  </div>
		</div>
		<div class="tab-pane fade" id="two">
		 <div class="well">
		  
		  <label class="checkbox">
           <input type="checkbox" name="im_attach" value="yes"<?php echo (isset($EDIT->im_attach) && $EDIT->im_attach=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_imap13; ?>
          </label>
		  
		  <label class="checkbox">
           <input type="checkbox" name="im_ssl" value="yes"<?php echo (isset($EDIT->im_ssl) && $EDIT->im_ssl=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_imap16; ?>
          </label>
		  
		  <label><br><?php echo $msg_imap12; ?></label>
          <input type="text" class="input-xlarge" name="im_flags" maxlength="250" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->im_flags) ? $EDIT->im_flags : '/novalidate-cert'); ?>">
          
		  <label><?php echo $msg_imap14; ?></label>
		  <div class="input-append">
            <input type="text" class="input-xlarge" maxlength="50" tabindex="<?php echo (++$tabIndex); ?>" name="im_move" value="<?php echo (isset($EDIT->im_move) ? $EDIT->im_move : ''); ?>">
			<span class="add-on" id="im_move"><a href="#" onclick="if(ms_folderCheck()){ms_showImapFolders('im_move','fmove');return false;}" title="<?php echo mswSpecialChars($msg_imap31); ?>"><i class="icon-folder-open"></i></a></span>
            <select style="margi-left:5px;display:none" class="fmove" onclick="if(this.value!='0'){ms_insertMailBox(this.value,'im_move','fmove')}"><option>1</option></select>
		  </div>
		  
		  <label><?php echo $msg_imap15; ?></label>
          <input type="text" class="input-small" maxlength="3" tabindex="<?php echo (++$tabIndex); ?>" name="im_messages" value="<?php echo (isset($EDIT->im_messages) ? $EDIT->im_messages : '50'); ?>">
          
		 </div> 
		</div>
		<div class="tab-pane fade" id="three">
		 <div class="well">
		  
		  <label><?php echo $msg_imap17; ?></label>
          <select name="im_dept" tabindex="<?php echo (++$tabIndex); ?>">
          <?php
          $q_dept = mysql_query("SELECT `id`,`name` FROM `".DB_PREFIX."departments` ORDER BY `name`") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
          while ($DEPT = mysql_fetch_object($q_dept)) {
          ?>
          <option value="<?php echo $DEPT->id; ?>"<?php echo (isset($EDIT->im_dept) ? mswSelectedItem($EDIT->im_dept,$DEPT->id) : ''); ?>><?php echo mswSpecialChars($DEPT->name); ?></option>
          <?php
          }
          ?>
          </select>
	  
	      <label><?php echo $msg_imap18; ?></label>
          <select name="im_priority" tabindex="<?php echo (++$tabIndex); ?>">
          <?php
          foreach ($ticketLevelSel AS $k => $v) {
          ?>
          <option value="<?php echo $k; ?>"<?php echo (isset($EDIT->im_priority) ? mswSelectedItem($EDIT->im_priority,$k) : ''); ?>><?php echo $v; ?></option>
          <?php
          }
          ?>
          </select>
	  
	      <label><?php echo $msg_imap19; ?></label>
          <input type="text" class="input-xlarge" maxlength="250" name="im_email" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswCleanData(isset($EDIT->im_email) ? $EDIT->im_email : ''); ?>">
      
		 </div> 
		</div>
		<div class="tab-pane fade" id="four">
		 <div class="well">
		 
		   <label class="checkbox">
            <input type="checkbox" name="im_spam" value="yes"<?php echo (isset($EDIT->im_spam) && $EDIT->im_spam=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msp_imap41; ?>
           </label>
		   
		   <label class="checkbox">
            <input type="checkbox" name="im_spam_purge" value="yes"<?php echo (isset($EDIT->im_spam_purge) && $EDIT->im_spam_purge=='yes' ? ' checked="checked"' : ''); ?>> <?php echo $msg_imap42; ?>
           </label>
		   
		   <label><br><?php echo $msg_imap63; ?></label>
           <input type="text" class="input-small" maxlength="10" tabindex="<?php echo (++$tabIndex); ?>" name="im_score" value="<?php echo (isset($EDIT->im_score) ? $EDIT->im_score : ''); ?>">
          
		 </div> 
		</div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="<?php echo (isset($EDIT->id) ? 'update' : 'process'); ?>" value="1">
       <button class="btn btn-primary" type="submit"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo (isset($EDIT->id) ? $msg_imap25 : $msg_imap); ?></button>
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=imapman')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
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