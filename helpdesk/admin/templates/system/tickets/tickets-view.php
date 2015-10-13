<?php if (!defined('PARENT')) { exit; }
define('TICKET_LOADER',1);
define('TICKET_TYPE','ticket');
$_GET['id'] = (int)$_GET['id'];
if ($_GET['id']==0) { exit; }
checkIsValid($SUPTICK);
?>
<div class="content">
  
  <div class="header">
    
	<?php
	if ($SUPTICK->spamFlag=='no') {
    if ($SUPTICK->ticketStatus!='open') {
    ?>
    <i class="icon-unlock"></i> <a href="?p=view-ticket&amp;id=<?php echo $_GET['id']; ?>&amp;act=reopen" title="<?php echo mswSpecialChars($msg_viewticket26); ?>"><?php echo $msg_viewticket26; ?></a>&nbsp;&nbsp;&nbsp;
    <?php
    } else {
    ?>
    <i class="icon-pencil"></i> <a href="#" onclick="ms_scrollToArea('replyArea');return false" title="<?php echo mswSpecialChars($msg_viewticket75); ?>"><?php echo $msg_viewticket75; ?></a>&nbsp;&nbsp;&nbsp;
    <i class="icon-off"></i> <a href="?p=view-ticket&amp;id=<?php echo $_GET['id']; ?>&amp;act=close" title="<?php echo mswSpecialChars($msg_viewticket27); ?>"><?php echo $msg_viewticket27; ?></a>&nbsp;&nbsp;&nbsp;
    <i class="icon-lock"></i> <a href="?p=view-ticket&amp;id=<?php echo $_GET['id']; ?>&amp;act=lock" title="<?php echo mswSpecialChars($msg_viewticket28); ?>"><?php echo $msg_viewticket28; ?></a>&nbsp;&nbsp;&nbsp;
    <?php
	if ($SETTINGS->disputes=='yes') {
	?>
	<i class="icon-comments"></i> <a href="?p=view-ticket&amp;id=<?php echo $_GET['id']; ?>&amp;act=dispute" onclick="ms_confirmActionMessage(this,'<?php echo mswSpecialChars($msg_script_action); ?>');return false" title="<?php echo mswSpecialChars($msg_disputes3); ?>"><?php echo $msg_disputes3; ?></a>&nbsp;&nbsp;&nbsp;
    <?php
    }
	}
    // Is notepad available..
    if ($MSTEAM->notePadEnable=='yes' || $MSTEAM->id=='1') {
    ?>
	<i class="icon-file-text"></i> <a href="#" title="<?php echo $msg_viewticket54; ?>" onclick="jQuery('#ticketPadArea').slideDown('slow');return false"><?php echo $msg_viewticket54; ?></a>
    <?php
	}
	}
	?>
	<h1 class="page-title"><?php echo $title; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb" id="ticketCrumbs">
    <li><?php echo $msg_adheader41; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_viewticket95; ?></li>
  </ul>
  
  <?php
  if (isset($OK)) {
    echo mswActionCompleted($msg_viewticket47);
  }
  if (isset($OK2)) {
    echo mswActionCompleted(str_replace('{ticket}',mswTicketNumber(ltrim($_POST['mergeid'],'0')),$msg_viewticket90));
  }
  if (isset($OK3)) {
    echo mswActionCompleted($actionMsg);
  }
  
  // Reload for merge...
  if (isset($OK2)) {
  ?>
  <div class="container-fluid">
    
	<div class="row-fluid">
	  <div class="well" style="text-align:center;margin-top:10px;padding-top:25px">
	    <img src="templates/images/loading.gif" alt="" title="">
		<p style="margin-top:20px"><?php echo str_replace('{id}',mswTicketNumber(ltrim($_POST['mergeid'],'0')),$msg_viewticket122); ?></p>
	  </div>
	  <?php
	  // Footer..
	  include(PATH.'templates/footer-links.php');
	  ?>
	</div>
	
  </div>	
  <?php
  } else {
  ?>
  <form method="post" action="?p=<?php echo $_GET['p']; ?>&amp;id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data" id="formfield">
  <?php
  // Load ticket..
  include(PATH.'templates/system/tickets/view/ticket.php');
  // Hidden fields.
  ?>
  <div>
   <input type="hidden" name="process" value="yes">
   <input type="hidden" name="isDisputed" value="no">
   <?php
   if ($MSTEAM->id!='1') {
   ?>
   <input type="hidden" name="history" value="yes">
   <?php
   }
   ?>
  </div>
  </form>
  <?php
  }
  ?>

</div>