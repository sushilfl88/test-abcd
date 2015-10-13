<?php if (!defined('PARENT')) { exit; } ?>
<div class="content">
        
  <div class="header">
    
	<button class="btn search-bar-button" type="button" onclick="mswToggleSearch()"><i class="icon-search" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $this->TXT[0]; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li><?php echo $this->TXT[1]; ?> <span class="divider">/</span></li>
	<li class="active"><?php echo $this->MSDT->mswDateTimeDisplay($this->ANSWER['ts'],$this->SETTINGS->dateformat); ?></li>
  </ul>
        
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <?php
	  // Search box..
	  define('SEARCH_MARGIN', '10px');
	  include(PATH.'content/'.MS_TEMPLATE_SET.'/faq-search-box.tpl.php');
	  ?>
	  
	  <h2 class="faqQuestion"><i class="icon-file-text-alt"></i> <?php echo mswCleanData($this->ANSWER['question']); ?></h2>
	  
	  <p class="faqAnswer"><?php echo $this->ANSWER_TXT; ?></p>
	  
	  <?php
	  // Attachments..
	  if ($this->ATTACHMENTS) {
	  ?>
	  <div class="faqAttachments">
	  <?php
	  // html/faq-attachment-link.htm
	  echo $this->ATTACHMENTS;
	  ?>
	  </div>
	  <?php
	  }
	  
	  
	  // Print Friendly Service
	  // More info and options @ www.printfriendly.com
	  ?>
	  <div id="printFriendly">
	   
	   <script type="text/javascript">
	   //<![CDATA[
	   var pfHeaderImgUrl      = '';
	   var pfHeaderTagline     = '<?php echo str_replace("'","\'",mswCleanData($this->SETTINGS->website)); ?>';
	   var pfdisableClickToDel = 0;
	   var pfHideImages        = 1;
	   var pfImageDisplayStyle = 'right';
	   var pfDisablePDF        = 0;
	   var pfDisableEmail      = 0;
	   var pfDisablePrint      = 0;
	   var pfCustomCSS         = '';
	   var pfBtVersion         = '1';
	   (function(){
	    var js, pf;
		pf      = document.createElement('script');
		pf.type = 'text/javascript';
		if('https:' == document.location.protocol){
		 js = 'https://pf-cdn.printfriendly.com/ssl/main.js'
		} else {
		 js = 'http://cdn.printfriendly.com/printfriendly.js'
		}
		pf.src = js;
		document.getElementsByTagName('head')[0].appendChild(pf)
	   })();
	   //]]>
	   </script>
	   
	   <div class="row-fluid">
	     <div class="span6">
		 
		 <div class="btn-toolbar">
          <button class="btn btn-primary printQuestion" type="button" onclick="window.print()"><i class="icon-print"></i> <?php echo $this->TXT[3]; ?></button>
         </div>
		 </div>
         
		 <div class="span6 votingSystem" id="vote">
		 <input type="hidden" name="id" value="<?php echo (int)$_GET['a']; ?>">
		 <?php
		 // Is the voting system enabled..
		 if ($this->SETTINGS->enableVotes=='yes' && $this->FAQ_COOKIE_SET=='no') {
		   echo $this->TXT[2];
		 }
		 ?>
		 </div>
	   </div>
	   
	  </div>
	  
	  <?php
	  // Footer..
	  include(PATH.'content/'.MS_TEMPLATE_SET.'/footer-right.tpl.php');
	  ?>
	  
	</div>
  
  </div>

</div>