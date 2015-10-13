<?php if (!defined('PARENT')) { exit; } 
// Pre-populate search box if query exists.
$searchTxt = '';
// Ticket search..
if (isset($_GET['qt'])) {
  $searchTxt = mswSpecialChars($_GET['qt']);
}
if (isset($_GET['qd'])) {
  $searchTxt = mswSpecialChars($_GET['qd']);
}
$pageParam = ($this->IS_DISPUTED=='yes' ? 'disputes' : 'history');
?>
<div class="content">
        
  <div class="header">
    
    <div class="btn-group">
     <button class="btn"><?php echo ($this->IS_DISPUTED=='yes' ? $this->TXT[10] : $this->TXT[9]); ?></button>
     <button class="btn dropdown-toggle" data-toggle="dropdown">
     <span class="caret"></span>
     </button>
     <ul class="dropdown-menu topbar-dropdowns">
	  <?php
	  foreach ($this->DD_ORDER AS $fk1 => $fv1) {
	  ?>
      <li><a href="?p=<?php echo $pageParam; ?>&amp;order=<?php echo $fk1.mswQueryParams(array('p','order','next')); ?>"><?php echo $fv1; ?></a></li>
	  <?php
	  }
	  ?>
     </ul>
    </div>
	<div class="btn-group">
     <button class="btn"><?php echo ($this->IS_DISPUTED=='yes' ? $this->TXT[11] : $this->TXT[10]); ?></button>
     <button class="btn dropdown-toggle" data-toggle="dropdown">
     <span class="caret"></span>
     </button>
     <ul class="dropdown-menu topbar-dropdowns">
      <li><a href="?p=<?php echo $pageParam.mswQueryParams(array('p','filter','next')); ?>"><?php echo ($this->IS_DISPUTED=='yes' ? $this->TXT[14] : $this->TXT[13]); ?></a></li>
	  <?php
	  foreach ($this->DD_FILTERS AS $fk2 => $fv2) {
	  ?>
      <li><a href="?p=<?php echo $pageParam; ?>&amp;filter=<?php echo $fk2.mswQueryParams(array('p','filter','next')); ?>"><?php echo $fv2; ?></a></li>
	  <?php
	  }
	  ?>
     </ul>
    </div>
	<div class="btn-group">
     <button class="btn"><?php echo ($this->IS_DISPUTED=='yes' ? $this->TXT[12] : $this->TXT[11]); ?></button>
     <button class="btn dropdown-toggle" data-toggle="dropdown">
     <span class="caret"></span>
     </button>
     <ul class="dropdown-menu topbar-dropdowns">
      <li><a href="?p=<?php echo $pageParam.mswQueryParams(array('p','dept','next')); ?>"><?php echo ($this->IS_DISPUTED=='yes' ? $this->TXT[13] : $this->TXT[12]); ?></a></li>
	  <?php
	  foreach ($this->DD_DEPT AS $fk3 => $fv3) {
	  ?>
      <li><a href="?p=<?php echo $pageParam; ?>&amp;dept=<?php echo $fk3.mswQueryParams(array('p','dept','next')); ?>"><?php echo $fv3; ?></a></li>
	  <?php
	  }
	  ?>
     </ul>
    </div>
	<button class="btn search-bar-button" type="button" onclick="mswToggleSearch()"><i class="icon-search" id="search-icon-button"></i></button>
	<h1 class="page-title"><?php echo $this->TXT[2]; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
  
  <ul class="breadcrumb">
    <li><a href="index.php"><?php echo $this->TXT[1]; ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo ($this->IS_DISPUTED=='yes' ? $this->TXT[9] : $this->TXT[0]); ?></li>
  </ul>
        
  <div class="container-fluid">
    
	<div class="row-fluid">
	
	  <form method="get" action="index.php" style="margin:0;padding:0" onsubmit="return checkSearch('<?php echo ($this->IS_DISPUTED=='yes' ? 'qd' : 'qt'); ?>')">
	  <p><input type="hidden" name="p" value="<?php echo $pageParam; ?>"></p>
	  <div class="btn-toolbar" id="sbox" style="display:none">
       <div class="input-append">
        <input class="input-large" type="text" name="<?php echo ($this->IS_DISPUTED=='yes' ? 'qd' : 'qt'); ?>" value="<?php echo $searchTxt; ?>">
        <button class="btn btn-info" type="submit"><i class="icon-search"></i></button>
       </div>
      </div>
	  </form>
	  
	  <div class="well" style="margin-top:15px">
	   
	   <table class="table table-hover">
	    <thead>
         <tr>
          <th style="width:12%"><?php echo $this->TXT[7]; ?></th>
		  <th style="width:48%"><?php echo $this->TXT[8]; ?></th>
		  <th style="width:20%"><?php echo $this->TXT[5]; ?></th>
          <th style="width:20%"><?php echo $this->TXT[6]; ?></th>
         </tr>
        </thead>
	    <tbody>
	    <?php
	    // TICKETS
	    // html/tickets/ticket-list-entry.htm
	    // html/tickets/tickets-no-data.htm
		// html/tickets/tickets-last-reply-date.htm
	    echo $this->TICKETS;
	    ?>
	    </tbody>
	   </table>
	  
	  </div>
	  
	  <?php
	  // PAGE NUMBERS
	  if ($this->PAGES) {
	  ?>
	  <div class="pagination pagination-small pagination-right">
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