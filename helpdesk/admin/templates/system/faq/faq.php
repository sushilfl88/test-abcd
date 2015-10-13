<?php if (!defined('PARENT')) { exit; }
$categories  = array();
$attachments = array();
if (isset($_GET['edit'])) {
  $_GET['edit'] = (int)$_GET['edit'];
  $EDIT         = mswGetTableData('faq','id',$_GET['edit']);
  checkIsValid($EDIT);
  $categories   = mswFaqCategories($EDIT->id,'get');
  $qAS          = mysql_query("SELECT `itemID` FROM `".DB_PREFIX."faqassign` WHERE `question` = '{$EDIT->id}' AND `desc` = 'attachment' GROUP BY `itemID`") 
                  or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($AA = mysql_fetch_object($qAS)) {
    $attachments[] = $AA->itemID;
  }
}
$qA  = mysql_query("SELECT * FROM `".DB_PREFIX."faqattach` WHERE `enAtt` = 'yes' ORDER BY `name`") 
       or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo str_replace('&amp;amp;','&amp;',mswSpecialChars((isset($EDIT->id) ? $msg_kbase13 : $msg_kbase3))); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader17; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo str_replace('&amp;amp;','&amp;',mswSpecialChars((isset($EDIT->id) ? $msg_kbase13 : $msg_kbase3))); ?></li>
  </ul>
  
  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_kbase7);
  }
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_kbase8);
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('question,answer','tabArea')">
  <div class="container-fluid" style="margin-top:20px">
    <?php
	if (mswRowCount('categories')>0) {
	?>
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab" onclick="jQuery('#prev').show();return false;"><i class="icon-file-text-alt"></i> <?php echo $msg_kbase42; ?></a></li>
       <li><a href="#two" data-toggle="tab" onclick="jQuery('#prev').hide();return false"><i class="icon-reorder"></i> <?php echo $msg_import10; ?></a></li>
       <li><a href="#three" data-toggle="tab" onclick="jQuery('#prev').hide();return false"><i class="icon-paperclip"></i> <?php echo $msg_adheader33; ?></a></li>
      </ul>
	
	  <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		 
		 <label class="checkbox">
          <input type="checkbox" name="enFaq" value="yes"<?php echo (isset($EDIT->enFaq) && $EDIT->enFaq=='yes' ? ' checked="checked"' : (!isset($EDIT->enFaq) ? ' checked="checked"' : '')); ?>> <?php echo $msg_kbase28; ?>
         </label>
		 
		 <label><br><?php echo $msg_kbase; ?></label>
         <input type="text" class="input-xlarge" name="question" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo (isset($EDIT->id) ? mswSpecialChars($EDIT->question) : ''); ?>">
         
		 <label><?php echo $msg_kbase2; ?></label>
         <?php
         define('BB_BOX','answer');
         include(PATH.'templates/system/bbcode-buttons.php');
         ?>
         <textarea rows="8" cols="40" name="answer" id="answer" tabindex="<?php echo (++$tabIndex); ?>"><?php echo (isset($EDIT->id) ? mswSpecialChars($EDIT->answer) : ''); ?></textarea>
         <?php
		 // Preview area..do not remove empty div
		 ?>
		 <div id="previewArea" class="previewArea prevFAQ" onclick="ms_closePreview('answer','previewArea')"></div>
		 
		 </div>
		</div>
		<div class="tab-pane fade" id="two">
		 <div class="well">
		 
		 <label class="checkbox">
          <input type="checkbox" value="0" onclick="checkBoxes(this.checked,'#cb')"> <?php echo $msg_kbase6; ?>
         </label>
		 
		 <div id="cb">
		 <?php
         $q_cat = mysql_query("SELECT * FROM `".DB_PREFIX."categories` WHERE `subcat` = '0' ORDER BY `name`") 
                  or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($CAT = mysql_fetch_object($q_cat)) {
         ?>
		 <label class="checkbox">
          <input type="checkbox" name="cat[]" value="<?php echo $CAT->id; ?>"<?php echo mswCheckedArrItem($categories,$CAT->id); ?>><?php echo mswCleanData($CAT->name); ?>
         </label>
		 <input type="hidden" name="catall[]" value="<?php echo $CAT->id; ?>">
		 <?php
         $q_cat2 = mysql_query("SELECT * FROM `".DB_PREFIX."categories` WHERE `subcat` = '{$CAT->id}' ORDER BY `name`") 
                   or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($SUB = mysql_fetch_object($q_cat2)) {
         ?>
		 <label class="checkbox">
          <input type="checkbox" name="cat[]" value="<?php echo $SUB->id; ?>"<?php echo mswCheckedArrItem($categories,$SUB->id); ?>>- <?php echo mswCleanData($SUB->name); ?>
		 </label>
		 <input type="hidden" name="catall[]" value="<?php echo $SUB->id; ?>">
         <?php
         }
         }
         ?>
		 </div>
		 
		 </div>
		</div>
		<div class="tab-pane fade" id="three">
		 <div class="well attacharea" style="height:300px;overflow:auto">
		  <table class="table table-striped table-hover">
          <thead>
           <tr>
            <th style="width:6%">
		     <input type="checkbox" onclick="checkBoxes(this.checked,'.attacharea')">
		    </th>
		    <th style="width:77%"><?php echo $msg_attachments16; ?></th>
            <th style="width:17%"><?php echo $msg_kbase49; ?></th>
           </tr>
          </thead>
          <tbody>
		   <?php
		   if (mysql_num_rows($qA)>0) {
           while ($ATT = mysql_fetch_object($qA)) {
		   $ext  = substr(strrchr(strtolower(($ATT->remote ? $ATT->remote : $ATT->path)),'.'),1);
		   $info = '['.strtoupper($ext).'] '.($ATT->size>0 ? mswFileSizeConversion($ATT->size) : 'N/A');
		   ?>
           <tr>
            <td><input type="checkbox" name="att[]" value="<?php echo $ATT->id; ?>"<?php echo mswCheckedArrItem($attachments,$ATT->id); ?>></td>
		    <td><?php echo ($ATT->name ? mswSpecialChars($ATT->name) : ($ATT->remote ? $ATT->remote : $ATT->path)); ?></td>
            <td><a href="?fattachment=<?php echo $ATT->id; ?>" title="<?php echo mswSpecialChars($msg_kbase50); ?>"><?php echo $info; ?></a></td>
           </tr>
		   <?php
		   }
		   } else {
		   ?>
		   <tr class="warning nothing_to_see">
		    <td colspan="3"><?php echo $msg_attachments9; ?></td>
		   </tr> 
		   <?php
		   }
		   ?>
          </tbody>
         </table>
		 </div>
		</div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="<?php echo (isset($EDIT->id) ? 'update' : 'process'); ?>" value="1">
       <button class="btn btn-primary" type="submit"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo mswCleanData((isset($EDIT->id) ? $msg_kbase13 : $msg_kbase3)); ?></button>
       <button class="btn" type="button" onclick="ms_textPreview('faq','answer','previewArea')" id="prev"><i class="icon-search"></i> <?php echo mswCleanData($msg_viewticket55); ?></button>
	   <button class="btn" type="button" onclick="ms_closePreview('answer','previewArea')" style="display:none" id="clse"><i class="icon-remove"></i> <?php echo mswCleanData($msg_viewticket101); ?></button>
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=faqman')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
       <?php
	   }
	   ?>
	  </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
    <?php
	} else {
	?>
	<div class="row-fluid">
	<p class="nothing_to_see"><?php echo $msg_kbase64; ?></p>
	<?php
	// Footer links..
	include(PATH.'templates/footer-links.php');
	?>
	</div>
	<?php
	}
	?>
  </div>
  </form>

</div>