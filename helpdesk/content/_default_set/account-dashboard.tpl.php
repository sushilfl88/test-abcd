<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
    
	<h1 class="page-title"><?php echo $this->TXT[0]; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li><?php echo $this->TXT[1]; ?></li>
  </ul>
        
  <div class="container-fluid">
    
	<div class="row-fluid">
	 <div class="row-fluid">
      <div class="span8">
	   
	   <div class="row-fluid">
        <div class="block" style="padding:0;margin:0;margin-top:10px">
	     <div class="block-body">
		  <?php echo $this->TXT[7]; ?>
		 </div>
	    </div>
	   </div>
	  
	  </div>
	  <div class="span4">
	   
	   <div class="row-fluid">
        <div class="block" style="padding:0;margin:0;margin-top:10px">
	     <p class="block-heading uppercase"><i class="icon-gear"></i> <?php echo $this->TXT[2]; ?></p>
         <div class="block-body">
		  <div class="pull-right"><a href="?p=profile"><i class="icon-edit"></i></a></div>
		  &#8226; <?php echo $this->USER_DATA->email; ?><br>
		  &#8226; <?php echo $this->TXT[5]; ?>: <?php echo ($this->USER_DATA->timezone ? $this->USER_DATA->timezone : $this->SETTINGS->timezone); ?><br>
		  &#8226; <?php echo $this->TXT[6]; ?>: <?php echo ucfirst($this->USER_DATA->language); ?><br>
		  &#8226; <?php echo $this->TXT[8]; ?>: <?php echo mswIPAddresses(); ?>
		 </div>
	    </div>
	   </div>
	  
	  </div>
	 </div> 
	 <div class="row-fluid">
      
	  <div class="block" style="padding:0;margin:0;margin-top:10px">
	   <p class="block-heading uppercase"><i class="icon-ticket"></i> <?php echo $this->TXT[3]; ?></p>
       <div class="block-body">
	     
	    <table class="table table-hover">
	    <tbody>
	    <?php
	    // TICKETS
	    // html/tickets/tickets-dashboard.htm
	    // html/tickets/tickets-no-data.htm
		echo $this->TICKETS;
	    ?>
		</tbody>
	    </table>
	     
	   </div>
	  </div>
	 
	 </div>
	 
	 <?php
	 // Only show if dispute system is enabled..
	 if ($this->SETTINGS->disputes=='yes') {
	 ?>
	 <div class="row-fluid">
      
	  <div class="block" style="padding:0;margin:0;margin-top:10px">
	   <p class="block-heading uppercase"><i class="icon-bullhorn"></i> <?php echo $this->TXT[4]; ?></p>
       <div class="block-body">
	   
	    <table class="table table-hover">
	    <tbody>
	    <?php
	    // DISPUTE TICKETS
	    // html/tickets/tickets-dashboard.htm
	    // html/tickets/tickets-no-data.htm
		echo $this->DISPUTES;
	    ?>
		</tbody>
	    </table>
	   
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