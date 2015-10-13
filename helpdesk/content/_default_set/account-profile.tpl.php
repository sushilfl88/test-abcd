<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
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
	  <ul class="nav nav-tabs" id="tabArea">
       <li class="active"><a href="#one" data-toggle="tab"><i class="icon-user"></i> <?php echo $this->TXT[2]; ?></a></li>
       <li><a href="#two" data-toggle="tab"><i class="icon-envelope-alt"></i> <?php echo $this->TXT[3]; ?></a></li>
	   <li><a href="#three" data-toggle="tab"><i class="icon-lock"></i> <?php echo $this->TXT[11]; ?></a></li>
	   <?php
	   if (!empty($this->LANGUAGES)) {
	   ?>
	   <li><a href="#four" data-toggle="tab"><i class="icon-file-text-alt"></i> <?php echo $this->TXT[9]; ?></a></li>
	   <?php
	   }
	   ?>
	  </ul>
	  <div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="one">
		 <div class="well">
		  
		  <label><?php echo $this->TXT[5]; ?></label>
          <input type="text" class="input-xlarge" name="name" maxlength="200" value="<?php echo (isset($this->ACCOUNT['name']) ? mswSpecialChars($this->ACCOUNT['name']) : ''); ?>">
		  
		  <label><?php echo $this->TXT[6]; ?></label>
          <select name="timezone">
		   <option value="0">- - - - -</option>
		   <?php
		   // Timezones..
		   // control/timezones.php
		   foreach ($this->TIMEZONES AS $zK => $zV) {
		   ?>
		   <option value="<?php echo $zK; ?>"<?php echo (isset($this->ACCOUNT['timezone']) ? mswSelectedItem($this->ACCOUNT['timezone'],$zK) : ''); ?>><?php echo $zV; ?></option>
		   <?php
		   }
		   ?>
		  </select>
		  
		 </div>
		</div>
		<div class="tab-pane fade" id="two">
		 <div class="well">
		  
		  <label><?php echo $this->TXT[7]; ?></label>
          <input type="text" class="input-xlarge" name="email" value="">
		  
		  <label><?php echo $this->TXT[8]; ?></label>
          <input type="text" class="input-xlarge" name="email2" value="">
		  
		 </div>
		</div>
		<div class="tab-pane fade" id="three">
		 <div class="well">
		  
		  <label><?php echo $this->TXT[12]; ?></label>
          <input type="password" class="input-xlarge" name="curpass" value="">
		  
		  <label><?php echo $this->TXT[13]; ?></label>
          <input type="password" class="input-xlarge" name="newpass" value="" onkeyup="clearNewPass2()">
		  
		  <label><?php echo $this->TXT[14]; ?></label>
          <input type="password" class="input-xlarge" name="newpass2" value="">
		  
		 </div>
		</div>
		<?php
		if (!empty($this->LANGUAGES)) {
		?>
		<div class="tab-pane fade" id="four">
		 <div class="well">
		 
		  <label><?php echo $this->TXT[10]; ?></label>
          <select name="language">
		   <?php
		   // Languages..
		   foreach ($this->LANGUAGES AS $lK) {
		   ?>
		   <option value="<?php echo $lK; ?>"<?php echo (isset($this->ACCOUNT['language']) ? mswSelectedItem($this->ACCOUNT['language'],$lK) : ''); ?>><?php echo ucfirst($lK); ?></option>
		   <?php
		   }
		   ?>
		  </select>
		 
		 </div>
		</div>
		<?php
		}
		?>
	  </div>	
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
       <input type="hidden" name="process" value="1">
       <button class="btn btn-primary" type="button" tabindex="4" onclick="ms_fieldCheck('name','tabArea','profile')"><i class="icon-ok"></i> <?php echo $this->TXT[4]; ?></button>
      </div>
	  <?php
	  // Footer..
	  include(PATH.'content/'.MS_TEMPLATE_SET.'/footer-right.tpl.php');
	  ?>
	</div>
  
  </div>
  </form>

</div>