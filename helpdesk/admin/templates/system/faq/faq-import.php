<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_adheader55; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader17; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader55; ?></li>
  </ul>

  <?php
  // Added..
  if (isset($OK1)) {
    echo mswActionCompleted(str_replace('{count}',$total,$msg_import14));
  }
  ?>
  
  <form method="post" action="?p=<?php echo $_GET['p']; ?>" enctype="multipart/form-data" onsubmit="return ms_fieldCheck('file','tabArea')">
  <div class="container-fluid" style="margin-top:20px">
    <?php
	if (mswRowCount('categories')>0) {
	?>
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-file-text-alt"></i> <?php echo $msg_response22; ?></a></li>
       <li><a href="#two" data-toggle="tab"><i class="icon-reorder"></i> <?php echo $msg_import10; ?></a></li>
      </ul>

	  <div id="myTabContent" class="tab-content">
	   <div class="tab-pane active in" id="one">
	    <div class="well">
		 
		 <span class="pull-right">&#8226; <a href="templates/examples/faqs.csv" onclick="window.open(this);return false"><?php echo $msg_import15; ?></a> &#8226;</span>
		 
		 <label><?php echo $msg_import5; ?></label>
         <input class="input-xlarge" type="file" name="file" tabindex="<?php echo (++$tabIndex); ?>">
       
		 <label><br><?php echo $msg_import6; ?></label>
         <input class="input-small" type="text" name="lines" tabindex="<?php echo (++$tabIndex); ?>" value="5000">
         
		 <label><?php echo $msg_import7; ?></label>
         <input class="input-small" type="text" name="delimiter" tabindex="<?php echo (++$tabIndex); ?>" value=",">
         
		 <label><?php echo $msg_import8; ?></label>
         <input class="input-small" type="text" name="enclosed" tabindex="<?php echo (++$tabIndex); ?>" value="&quot;">
       
		</div>
	   </div>
	   <div class="tab-pane fade" id="two">
		<div class="well">
		 
		 <label class="checkbox">
          <input type="checkbox" name="clear" value="yes"> <?php echo $msg_import4; ?>
         </label>
		 
		 <label class="checkbox" style="margin-top:20px">
          <input type="checkbox" value="0" onclick="checkBoxes(this.checked,'#cb')"> <?php echo $msg_kbase6; ?>
         </label>
		 
		 <div id="cb">
		 <?php
         $q_cat = mysql_query("SELECT * FROM `".DB_PREFIX."categories` WHERE `subcat` = '0' ORDER BY `name`") 
                  or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($CAT = mysql_fetch_object($q_cat)) {
         ?>
		 <label class="checkbox">
          <input type="checkbox" name="cat[]" value="<?php echo $CAT->id; ?>"><?php echo mswSpecialChars($CAT->name); ?>
         </label>
		 <input type="hidden" name="catall[]" value="<?php echo $CAT->id; ?>">
		 <?php
         $q_cat2 = mysql_query("SELECT * FROM `".DB_PREFIX."categories` WHERE `subcat` = '{$CAT->id}' ORDER BY `name`") 
                   or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         while ($SUB = mysql_fetch_object($q_cat2)) {
         ?>
		 <label class="checkbox">
          <input type="checkbox" name="cat[]" value="<?php echo $SUB->id; ?>">- <?php echo mswCleanData($SUB->name); ?>
		 </label>
		 <input type="hidden" name="catall[]" value="<?php echo $SUB->id; ?>">
         <?php
         }
         }
         ?>
		 </div>
        
		</div>
	   </div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="process" value="1">
       <button class="btn btn-primary" type="submit"><i class="icon-upload-alt"></i> <?php echo mswCleanData($msg_adheader55); ?></button>
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