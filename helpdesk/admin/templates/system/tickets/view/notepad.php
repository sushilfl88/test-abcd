<?php if (!defined('TICKET_LOADER')) { exit; } ?> 
      <div class="block" style="border:0" id="ticketPadArea">
	   <div id="ticketPad">
	    <textarea name="notes" rows="8" cols="40" id="notes"><?php echo mswSpecialChars($SUPTICK->ticketNotes); ?></textarea>
		<div class="btn-toolbar" style="margin-top:0;padding-top:0;text-align:center">
         <button class="btn btn-primary" type="button" onclick="ms_updateTicketNotes('<?php echo $_GET['id']; ?>')"><i class="icon-ok"></i> <?php echo $msg_viewticket99; ?></button>
	     <button class="btn btn-link" type="button" onclick="jQuery('#ticketPadArea').slideUp()"><i class="icon-remove"></i> <?php echo $msg_viewticket100; ?></button>
        </div>
	   </div>
	  </div>