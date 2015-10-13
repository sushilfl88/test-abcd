<?php if (!defined('PARENT')) { exit; } 
if (isset($_GET['edit'])) {
  $_GET['edit'] = (int)$_GET['edit'];
  $EDIT         = mswGetTableData('categories','id',$_GET['edit']);
  checkIsValid($EDIT);
}
?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo (isset($EDIT->id) ? $msg_kbasecats5 : $msg_kbase16); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader17; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo (isset($EDIT->id) ? $msg_kbasecats5 : $msg_kbase16); ?></li>
  </ul>

  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted($msg_kbasecats);
  }
  // Updated..
  if (isset($OK2)) {
    echo mswActionCompleted($msg_kbasecats7);
  }
  ?>
  
  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('name')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-edit"></i> <?php echo $msg_kbase59; ?></a></li>
      </ul>
	  <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  
		  <label class="checkbox">
           <input type="checkbox" name="enCat" value="yes"<?php echo (isset($EDIT->enCat) && $EDIT->enCat=='yes' ? ' checked="checked"' : (!isset($EDIT->enCat) ? ' checked="checked"' : '')); ?>> <?php echo $msg_kbase24; ?>
          </label>
		  
		  <label><br><?php echo $msg_kbase17; ?></label>
          <input class="input-xlarge" type="text" name="name" tabindex="<?php echo (++$tabIndex); ?>" maxlength="100" value="<?php echo (isset($EDIT->name) ? mswSpecialChars($EDIT->name) : ''); ?>">
      
		  <label><?php echo $msg_kbase15; ?></label>
          <input class="input-xlarge" type="text" name="summary" tabindex="<?php echo (++$tabIndex); ?>" maxlength="250" value="<?php echo (isset($EDIT->summary) ? mswSpecialChars($EDIT->summary) : ''); ?>">
      
		  <label><?php echo $msg_kbase38; ?></label>
          <select name="subcat">
           <option value="0"><?php echo $msg_kbase36; ?></option>
           <?php
           $q_cat = mysql_query("SELECT * FROM `".DB_PREFIX."categories` WHERE `subcat` = '0' ORDER BY `name`") 
                    or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
           if (mysql_num_rows($q_cat)>0) {
		   ?>
		   <optgroup label="<?php echo mswSpecialChars($msg_kbase37); ?>">
           <?php
		   while ($CAT = mysql_fetch_object($q_cat)) {
           ?>
           <option<?php echo (isset($EDIT->id) ? mswSelectedItem($EDIT->subcat,$CAT->id) : ''); ?> value="<?php echo $CAT->id; ?>"><?php echo mswCleanData($CAT->name); ?></option>
           <?php
           }
		   }
           ?>
           </optgroup>
          </select>
	  
		  </div>
		</div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="<?php echo (isset($EDIT->id) ? 'update' : 'process'); ?>" value="1">
       <button class="btn btn-primary" type="submit"><i<?php echo (isset($EDIT->id) ? ' class="icon-ok"' : ' class="icon-plus"'); ?>></i> <?php echo mswCleanData((isset($EDIT->id) ? $msg_kbasecats5 : $msg_kbase16)); ?></button>
       <?php
	   if (isset($EDIT->id)) {
	   ?>
	   <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=faq-catman')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
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