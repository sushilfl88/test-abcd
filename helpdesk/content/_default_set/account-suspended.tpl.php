<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo $this->TXT[0]; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li><?php echo $this->TXT[0]; ?></li>
  </ul>
        
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	 <div class="well">
	 <?php
	 echo $this->TXT[1];
	 ?>
	 </div>
	 <?php
	 // Footer..
	 include(PATH.'content/'.MS_TEMPLATE_SET.'/footer-right.tpl.php');
	 ?>
	</div>
  
  </div>

</div>