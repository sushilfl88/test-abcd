<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">

  <script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function() {
    if (jQuery('input[name="email"]').val()=='') {
	  jQuery('input[name="email"]').focus();
	}
  });
  //]]>
  </script>
  
  <div class="header">
	<h1 class="page-title"><?php echo $this->TXT[0]; ?></h1>
	<span class="clearfix"></span>
  </div>
  
  <ul class="breadcrumb">
    <li class="active"><?php echo $this->TXT[1]; ?></li>
  </ul>
        
  <form method="post" action="#" id="form">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  
	  <div class="well">
		 
		<label><?php echo $this->TXT[2]; ?></label>
        <input type="text" class="input-xlarge" name="email" tabindex="1" value="" onkeyup="msErrClear('email')">
         
		<div id="passArea">
		<label><?php echo $this->TXT[3]; ?></label>
        <input type="password" class="input-xlarge" name="pass" tabindex="2" value="" onkeyup="msErrClear('pass')">
		&nbsp;&nbsp;<a href="#" onclick="newPass('yes');return false"><?php echo $this->TXT[5]; ?></a>
		</div>
        
	  </div>
	  
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
       <input type="hidden" name="process" value="1">
       <button class="btn btn-primary" type="button" id="login" tabindex="3" onclick="ms_fieldCheck('email,pass','','login')"><i class="icon-lock"></i> <?php echo $this->TXT[4]; ?></button>
	   <button class="btn btn-primary" type="button" id="pass" style="display:none" onclick="ms_fieldCheck('email','','newpass')"><i class="icon-unlock-alt"></i> <?php echo $this->TXT[6]; ?></button>
	   <button class="btn btn-alert" type="button" style="display:none" id="cancel" onclick="newPass('no');return false"><i class="icon-remove"></i></button>
	  </div>
	
	  <?php
	  // Footer..
	  include(PATH.'content/'.MS_TEMPLATE_SET.'/footer-right.tpl.php');
	  ?>
	</div>
  
  </div>
  </form>

</div>