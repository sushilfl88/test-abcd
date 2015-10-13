<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo mswCleanData($this->SETTINGS->website); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li><?php echo $this->TXT[0]; ?></li>
  </ul>
        
  <div class="container-fluid">
    
	<div class="row-fluid">
	 
	 <div class="well" style="margin:15px 0 5px 0">
	  <?php echo $this->TXT[1]; ?>
	 </div>
	 
	 <?php
	 // Show if FAQ is enabled...
	 if ($this->SETTINGS->kbase=='yes') {
	 ?>
	 <div class="row-fluid">
      <div class="span6">
	  
	   <div class="row-fluid">
        <div class="block" style="padding:0;margin:0;margin-top:10px">
	     <p class="block-heading uppercase"><i class="icon-heart"></i> <?php echo $this->TXT[2]; ?></p>
         <div class="block-body">
		  
		  <table class="table table-hover">
	      <tbody>
		   <?php
		   // MOST POPULAR QUESTIONS
		   // html/faq-question-link.htm
		   // html/nothing-found.htm
		   echo $this->POPULAR;
		   ?>
	      </tbody>
	      </table>
		 
		 </div>
	    </div>
	   </div>
	  
	  </div>
	  <div class="span6">
	  
	  <div class="row-fluid">
        <div class="block" style="padding:0;margin:0;margin-top:10px">
	     <p class="block-heading uppercase"><i class="icon-calendar"></i> <?php echo $this->TXT[3]; ?></p>
         <div class="block-body">
		 
		  <table class="table table-hover">
	      <tbody>
		   <?php
		   // LATEST QUESTIONS
		   // html/faq-question-link.htm
		   // html/nothing-found.htm
		   echo $this->LATEST;
		   ?>
	      </tbody>
	      </table>
		 
		 </div>
	    </div>
	   </div>
	  
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