<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
	<h1 class="page-title"><?php echo $this->TXT[0]; ?></h1>
	<span class="clearfix"></span>
  </div>
  
  <ul class="breadcrumb">
    <li class="active"><?php echo $this->TXT[1]; ?></li>
  </ul>
        
  <form method="post" action="?p=create" onsubmit="return ms_fieldCheck('name,email,email2<?php echo $this->RECPA_ERR_PARAM; ?>','','create')" id="form">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  
	  <div class="well">
		
	    <label><?php echo $this->TXT[5]; ?></label>
        <input type="text" class="input-xlarge" name="name" tabindex="1" maxlength="200" value="" onkeyup="msErrClear('name')">
	  
		<label><?php echo $this->TXT[2]; ?></label>
        <input type="text" class="input-xlarge" name="email" tabindex="2" value="" onkeyup="msErrClear('email')">
         
		<label><?php echo $this->TXT[3]; ?></label>
        <input type="text" class="input-xlarge" name="email2" tabindex="3" value="" onkeyup="msErrClear('email2')">
		
		<?php 
        // SPAM PREVENTION IF RECAPTCHA ENABLED
        // html/recaptcha.htm
        echo $this->RECAPTCHA; 
        ?>
        
	  </div>
	  
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
       <input type="hidden" name="process" value="1">
       <button class="btn btn-primary" type="submit" tabindex="51"><i class="icon-ok"></i> <?php echo $this->TXT[4]; ?></button>
      </div>
	  
	  <?php
	  // Hidden elements that hold text for errors..
	  ?>
      <p id="err1" style="display:none"><?php echo $this->TXT[6]; ?></p>
	  <p id="err2" style="display:none"><?php echo $this->TXT[7]; ?></p>
	  <p id="err3" style="display:none"><?php echo $this->TXT[8]; ?></p>
	  <p id="err4" style="display:none"><?php echo $this->TXT[9]; ?></p>
	  <?php
	  
	  // Footer..
	  include(PATH.'content/'.MS_TEMPLATE_SET.'/footer-right.tpl.php');
	  ?>
	</div>
  
  </div>
  </form>

</div>