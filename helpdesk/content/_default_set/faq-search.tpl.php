<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
    
    <button class="btn search-bar-button" type="button" onclick="mswToggleSearch()"><i class="icon-search" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $this->TXT[0]; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li><?php echo $this->TXT[2]; ?> <span class="divider">/</span></li>
	<li class="active"><?php echo $this->RESULTS; ?></li>
  </ul>
        
  <div class="container-fluid">
    
	<div class="row-fluid" style="margin-top:15px">
	
	  <?php
	  // Search box..
	  include(PATH.'content/'.MS_TEMPLATE_SET.'/faq-search-box.tpl.php');
	  ?>
	  
	  <table class="table table-hover">
	   <tbody>
		<?php
		// QUESTIONS FOR THIS CATEGORY
		// html/faq-question-link.htm
		// html/nothing-found.htm
		echo $this->FAQ;
		?>
	   </tbody>
	  </table>
	  
	  <?php
	  // PAGE NUMBERS
	  if ($this->PAGES) {
	  ?>
	  <div class="pagination pagination-small pagination-centered">
       <?php
	   // control/classes/page.php
	   echo $this->PAGES;
	   ?>
      </div>
	  <?php
	  }
	  
	  // Footer..
	  include(PATH.'content/'.MS_TEMPLATE_SET.'/footer-right.tpl.php');
	  ?>
	  
	</div>
  
  </div>

</div>