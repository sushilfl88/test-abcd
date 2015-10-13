<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo $this->TXT[0]; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li><a href="?p=open"><?php echo $this->TXT[1]; ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->TXT[0]; ?></li>
  </ul>
        
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	 
	 <div class="well ticketconfirmmessage">
	  <?php 
	  // Confirmation message..
	  echo $this->TXT[2]; 
	  ?>
	 </div>
	 <?php
	 // Additional text only shown when an account is also created..
	 if ($this->ADD_TXT) {
	 ?>
	 <div class="block mainticket">
	  <p class="block-heading"><?php echo strtoupper($this->TXT[3]); ?></p>
	  <div class="block-body">
	   <?php
	   echo $this->ADD_TXT;
	   ?>
	  </div>
	 </div> 
	 <?php	
	 }
	 
	 // Footer..
	 include(PATH.'content/'.MS_TEMPLATE_SET.'/footer-right.tpl.php');
	 ?>
	</div>
  
  </div>

</div>