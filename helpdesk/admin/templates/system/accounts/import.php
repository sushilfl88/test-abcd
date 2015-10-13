<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_adheader59; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader38; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader59; ?></li>
  </ul>
  
  <?php
  // Added..
  if (isset($OK)) {
    echo mswActionCompleted(str_replace('{count}',$count,$msg_accounts35));
  }
  ?>

  <form method="post" action="index.php?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('file','tabArea')" enctype="multipart/form-data">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-user"></i> <?php echo $msg_accounts34; ?></a></li>
       <li><a href="#two" data-toggle="tab"><i class="icon-file-text-alt"></i> <?php echo $msg_accounts33; ?></a></li>
	  </ul>
      <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  
		  <span class="pull-right">&#8226; <a href="templates/examples/accounts.csv" onclick="window.open(this);return false"><?php echo $msg_import15; ?></a> &#8226;</span>
		 
		  <label class="checkbox">
          <input type="checkbox" name="welcome" value="yes" checked="checked"> <?php echo $msg_accounts23; ?>
          </label>
		  
		  <label><br><?php echo $msg_import5; ?></label>
          <input class="input-xlarge" type="file" name="file">
		  
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
		  
		  <textarea rows="5" cols="20" name="notes"></textarea>
		 
		 </div> 
		</div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <input type="hidden" name="process" value="1">
       <button class="btn btn-primary" type="submit"><i class="icon-upload-alt"></i> <?php echo $msg_adheader59; ?></button>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>

  </div>
  </form>
	
</div>