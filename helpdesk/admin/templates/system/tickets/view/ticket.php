<?php if (!defined('TICKET_LOADER')) { exit; } ?>  
  <div class="container-fluid">
    
	<div class="row-fluid">
	  <?php
	  // Assigned area..
	  if (($MSTEAM->id=='1' || in_array('assign',$userAccess)) && mswRowCount('departments WHERE `id` = \''.$SUPTICK->department.'\' AND `manual_assign` = \'yes\'')>0) {
	    include(PATH.'templates/system/tickets/view/users.php');
	  }
	  // Notepad area..
	  if ($MSTEAM->notePadEnable=='yes' || $MSTEAM->id=='1') {
	    include(PATH.'templates/system/tickets/view/notepad.php');
	  }
	  // Is this a dispute?
	  if (TICKET_TYPE=='dispute') {
	  include(REL_PATH.'control/classes/class.tickets.php');
	  $MST            = new tickets();
	  $usersInDispute = $MST->disputeUserNames($SUPTICK,$SUPTICK->name);
	  ?>
	  <div class="block">
	   <p class="block-heading"><?php echo strtoupper(str_replace('{count}',count($usersInDispute),$msg_showticket30)); ?></p>
	   <div class="block-body">
	   <span class="pull-right"><a href="?p=view-dispute&amp;disputeUsers=<?php echo $_GET['id']; ?>" style="font-size:11px"><i class="icon-cog"></i> <?php echo $msg_disputes8; ?></a></span>
	   <?php
	   echo implode(', ',$usersInDispute);
	   ?>
	   </div>
	  </div>
	  <?php
	  }
	  ?>
	  <div class="block mainticket">
	   <p class="block-heading"><?php echo $msg_viewticket108; ?> <span class="label label-info"><?php echo mswSpecialChars($SUPTICK->name); ?> &#8226; <?php echo $MSYS->levels($SUPTICK->priority); ?> &#8226; <?php echo $MSDT->mswDateTimeDisplay($SUPTICK->ts,$SETTINGS->dateformat).' &#8226; '.$MSDT->mswDateTimeDisplay($SUPTICK->ts,$SETTINGS->timeformat); ?></span></p>
	   <div class="block-body">
	     <div class="ticketSubject">
		  <?php echo mswSpecialChars($SUPTICK->subject); ?>
		 </div>
		 <i class="icon-quote-left"></i>
	     <?php 
		 echo $MSPARSER->mswTxtParsingEngine($SUPTICK->comments);
		 ?>
		 <i class="icon-quote-right"></i>
	     <?php
		 $qT       = mysql_query("SELECT * FROM `".DB_PREFIX."ticketfields`
                     LEFT JOIN `".DB_PREFIX."cusfields`
                     ON `".DB_PREFIX."ticketfields`.`fieldID`   = `".DB_PREFIX."cusfields`.`id`
                     WHERE `ticketID`                           = '{$_GET['id']}'
                     AND `".DB_PREFIX."ticketfields`.`replyID`  = '0'
                     AND `enField`                              = 'yes'
                     ORDER BY `orderBy`
                     ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         if (mysql_num_rows($qT)>0) {
         ?>
         <div class="cusTickFields">
         <?php
         while ($TS = mysql_fetch_object($qT)) {
           if ($TS->fieldData!='nothing-selected' && $TS->fieldData!='') {
             switch ($TS->fieldType) {
               case 'textarea':
               case 'input':
               case 'select':
               ?>
               <p class="head"><?php echo mswSpecialChars($TS->fieldInstructions); ?></p>
			   <p class="text"><?php echo $MSPARSER->mswTxtParsingEngine($TS->fieldData); ?></p>
               <?php
               break;
               case 'checkbox':
               ?>
               <p class="head"><?php echo mswSpecialChars($TS->fieldInstructions); ?></p>
			   <p class="text"><?php echo str_replace('#####','<br>',mswSpecialChars($TS->fieldData)); ?></p>
               <?php
               break;
             }  
           }
          }
		  ?>
		  </div>
		  <?php
		  }
		  ?>
		 <div class="ticketInfoBox">
		  <?php
		  // Does initial ticket have attachments..
		  $attText = '';
          if ($SETTINGS->attachment=='yes') {
            $aCount  = mswRowCount('attachments WHERE `ticketID` = \''.$_GET['id'].'\' AND `replyID` = \'0\'');
			if ($aCount==0) {
			  $attText = str_replace('{count}',$aCount,$msg_viewticket41);
			} else {
			  $attText = str_replace('{count}',$aCount,'<a href="#" onclick="jQuery(\'#attachments_'.$_GET['id'].'_0\').slideDown(\'slow\');return false">'.$msg_viewticket41.'</a>');
			}
		  }
		  ?>
		  <p><?php echo ($SETTINGS->attachment=='yes' ? '<span class="pull-left" id="link'.$_GET['id'].'_0"><i class="icon-paper-clip"></i> '.$attText.'</span>' : ''); ?><?php echo $msg_viewticket4; ?>: <?php echo $MSYS->department($SUPTICK->department,$msg_script30); ?> &#8226; <?php echo $msg_viewticket3; ?>: <?php echo $SUPTICK->email; ?> &#8226; <?php echo $msg_viewticket6; ?>: <span style="margin-right:15px"><?php echo ($SUPTICK->ipAddresses ? $SUPTICK->ipAddresses : 'N/A'); ?></span><i class="icon-edit"></i> <a href="?p=edit-ticket&amp;id=<?php echo $_GET['id']; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>" class="edit_link"><?php echo $msg_script9; ?></a><?php echo ($SUPTICK->assignedto && $SUPTICK->spamFlag=='no' ? '<br>'.str_replace('{users}','<span class="highlighter" style="margin-right:15px">'.$MSTICKET->assignedTeam($SUPTICK->assignedto).'</span> <span style="cursor:pointer" onclick="mswJumpWait(\'ticketCrumbs\',\'userAssignArea\')" title="'.mswSpecialChars($msg_script9).'"><i class="icon-edit" title="'.mswSpecialChars($msg_script9).'"></i> '.$msg_script9.'</span>',$msg_open35) : ''); ?></p>
		 </div>
	   </div>
	  </div>
	  
	  <?php
	  // Load attachments area for main ticket message..
	  if ($SETTINGS->attachment=='yes') {
	    $aTickID     = $_GET['id'];
		$aTickReply  = '0';
	    include(PATH.'templates/system/tickets/view/attachments.php');
	  }
	  
      // Replies..
	  $reps      = 0;
      $q_replies = mysql_query("SELECT * FROM `".DB_PREFIX."replies`
                   WHERE `ticketID` = '{$_GET['id']}'
                   ORDER BY `id`
                   ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
      while ($REPLIES = mysql_fetch_object($q_replies)) {
       switch ($REPLIES->replyType) {
        case 'admin':
        $USER       = mswGetTableData('users','id',$REPLIES->replyUser);
        $replyName  = (isset($USER->name) ? mswSpecialChars($USER->name) : 'N/A');
        $label      = ' label-important';
		$icon       = '<i class="icon-user"></i> ';
        break;
        case 'visitor':
		if ($REPLIES->disputeUser>0) {
		  $DU         = mswGetTableData('portal','id',$REPLIES->disputeUser,'','`name`');
		  $replyName  = (isset($DU->name) ? mswSpecialChars($DU->name) : 'N/A');
		} else {
          $replyName  = mswSpecialChars($SUPTICK->name);
		}
        $label      = ' label-info';
		$icon       = '';
        break;
       }
	   ?>
	   <div class="block reptype<?php echo $REPLIES->replyType; ?>" id="reply-<?php echo $REPLIES->id; ?>">
	    <p class="block-heading"><?php echo $icon.$replyName; ?> &#8226; <?php echo $MSDT->mswDateTimeDisplay($REPLIES->ts,$SETTINGS->dateformat).' &#8226; '.$MSDT->mswDateTimeDisplay($REPLIES->ts,$SETTINGS->timeformat); ?> <span class="label<?php echo $label; ?>">R<?php echo (++$reps); ?> (ID:<?php echo $REPLIES->id; ?>)</span></p>
	    <div class="block-body">
		 <i class="icon-quote-left"></i>
	     <?php 
		 echo $MSPARSER->mswTxtParsingEngine($REPLIES->comments); 
		 ?>
		 <i class="icon-quote-right"></i>
		 <?php
		 // User signature?
		 if ($REPLIES->replyType=='admin' && $USER->signature) {
         ?>
         <p class="userSignature">
		  <?php echo mswNL2BR($MSPARSER->mswAutoLinkParser(mswSpecialChars($USER->signature))); ?>
		 </p>
         <?php
         }
		 $qT       = mysql_query("SELECT * FROM `".DB_PREFIX."ticketfields`
                     LEFT JOIN `".DB_PREFIX."cusfields`
                     ON `".DB_PREFIX."ticketfields`.`fieldID`  = `".DB_PREFIX."cusfields`.`id`
                     WHERE `ticketID`                          = '{$_GET['id']}'
                     AND `".DB_PREFIX."ticketfields`.`replyID` = '{$REPLIES->id}'
                     AND `enField`                             = 'yes'
                     ORDER BY `orderBy`
                     ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
         if (mysql_num_rows($qT)>0) {
         ?>
         <div class="cusTickFields">
         <?php
         while ($TS = mysql_fetch_object($qT)) {
           if ($TS->fieldData!='nothing-selected' && $TS->fieldData!='') {
             switch ($TS->fieldType) {
               case 'textarea':
               case 'input':
               case 'select':
               ?>
               <p class="head"><?php echo mswSpecialChars($TS->fieldInstructions); ?></p>
			   <p class="text"><?php echo $MSPARSER->mswTxtParsingEngine($TS->fieldData); ?></p>
               <?php
               break;
               case 'checkbox':
               ?>
               <p class="head"><?php echo mswSpecialChars($TS->fieldInstructions); ?></p>
			   <p class="text"><?php echo str_replace('#####','<br>',mswSpecialChars($TS->fieldData)); ?></p>
               <?php
               break;
             }  
           }
          }
		  ?>
		  </div>
		  <?php
		  }
		  // Count attachments for reply..
          $attText = '';
          if ($SETTINGS->attachment=='yes') {
            $arCount = mswRowCount('attachments WHERE `ticketID` = \''.$_GET['id'].'\' AND `replyID` = \''.$REPLIES->id.'\'');
			if ($arCount==0) {
			  $attText = str_replace('{count}',$arCount,$msg_viewticket41);
			} else {
			  $attText = str_replace('{count}',$arCount,'<a href="#" onclick="jQuery(\'#attachments_'.$_GET['id'].'_'.$REPLIES->id.'\').slideDown(\'slow\');return false">'.$msg_viewticket41.'</a>');
			}
          }
		  ?>
		 <div class="ticketInfoBox">
		  <p><?php echo ($SETTINGS->attachment=='yes' ? '<span class="pull-left" id="link'.$_GET['id'].'_'.$REPLIES->id.'"><i class="icon-paper-clip"></i> '.$attText.'</span>' : ''); ?><?php echo $msg_viewticket6; ?>: <span style="margin-right:30px"><?php echo ($REPLIES->ipAddresses ? $REPLIES->ipAddresses : 'N/A'); ?></span> <i class="icon-edit"></i> <a href="?p=edit-reply&amp;id=<?php echo $REPLIES->id; ?>" title="<?php echo mswSpecialChars($msg_script9); ?>" ><?php echo $msg_script9; ?></a><?php echo (USER_DEL_PRIV=='yes' ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon-trash"></i> <a onclick="deleteOps(\'reply\',\''.$REPLIES->id.'\',\''.mswSpecialChars($msg_script_action).'\');return false" href="#" title="'.mswSpecialChars($msg_script8).'">'.$msg_script8.'</a>' : ''); ?></p>
		 </div>
	    </div>
	   </div>
	   <?php
	   
	   // Load attachments area for reply..
	   if ($SETTINGS->attachment=='yes') {
	     $aTickID     = $_GET['id'];
		 $aTickReply  = $REPLIES->id;
	     include(PATH.'templates/system/tickets/view/attachments.php');
	   }
	   
	  } 
	  
	  // Reply area..
	  if ($SUPTICK->ticketStatus=='open' && $SUPTICK->spamFlag=='no') {
	  ?>
	  <div class="block" id="replyArea">
	    <?php
		// Custom fields..
        $qF = mysql_query("SELECT * FROM `".DB_PREFIX."cusfields`
              WHERE FIND_IN_SET('admin',`fieldLoc`) > 0
              AND `enField`                         = 'yes'
              ORDER BY `orderBy`
              ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        $cusFieldRows = mysql_num_rows($qF);
		?>
		<p class="block-heading"><?php echo strtoupper($msg_viewticket11).($cusFieldRows>0 ? ' <a class="toggleFields pull-right" href="#customFieldsArea" data-toggle="collapse" onclick="ms_chevronUpDown(\'chevron\')">'.$msg_viewticket97.' <i class="icon-chevron-up" id="chevron"></i></a>' : ''); ?></p>
	    <div class="block-body">
		<?php
        if ($cusFieldRows>0) {
        ?>
        <div class="customFields collapse in" id="customFieldsArea">
        <?php
         while ($FIELDS = mysql_fetch_object($qF)) {
          switch ($FIELDS->fieldType) {
            case 'textarea':
            echo $MSFM->buildTextArea(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,(++$tabIndex));
            break;
            case 'input':
            echo $MSFM->buildInputBox(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,(++$tabIndex));
            break;
            case 'select':
            echo $MSFM->buildSelect(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,(++$tabIndex),$FIELDS->fieldOptions);
            break;
            case 'checkbox':
            echo $MSFM->buildCheckBox(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,$FIELDS->fieldOptions);
            break;
          }
         }
         ?>
         </div>
         <?php
         }
	     ?>
		 <div class="row-fluid<?php echo ($cusFieldRows>0 ? ' replyArea' : ''); ?>">
          
		  <div class="span8">
		   <?php
		   // Standard responses..
		   $q_str  = mysql_query("SELECT * FROM `".DB_PREFIX."responses`
		             WHERE FIND_IN_SET('{$SUPTICK->department}',`departments`) > 0 
					 AND `enResponse` = 'yes'
					 ORDER BY `title`
					 ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
		   if (mysql_num_rows($q_str)>0) {
		     define('STANDARD_RESPONSES',1);
           }
		   // BBCode..
		   include(PATH.'templates/system/bbcode-buttons.php');
		   ?>
		   <div class="standardResponse" id="standardResponses" style="display:none">
		   <p>
		   <select id="response" onchange="getStandardResponse()" class="span6">
           <option value="0"><?php echo $msg_viewticket12.': '.$msg_viewticket5; ?></option>
           <?php
           while ($RESPONSES = mysql_fetch_object($q_str)) {
           ?>
           <option value="<?php echo $RESPONSES->id; ?>"><?php echo (strlen($RESPONSES->title)>STANDARD_RESPONSE_DD_TEXT_LIMIT ? substr(mswSpecialChars($RESPONSES->title),0,STANDARD_RESPONSE_DD_TEXT_LIMIT).'..' : mswSpecialChars($RESPONSES->title)); ?></option>
           <?php
           }
           ?>
           </select> <a href="#" onclick="jQuery('#standardResponses').slideUp();return false" title="<?php echo mswSpecialChars($msg_script15); ?>"><i class="icon-remove"></i></a>
		   </p>
           </div>
		   <textarea name="comments" rows="15" cols="40" id="comments"></textarea>
		   <?php
		   // Preview area..do not remove empty div
		   ?>
		   <div id="previewArea" class="previewArea prevTickets" onclick="ms_closePreview('comments','previewArea')"></div>
		   
		   <?php
		   if (in_array('standard-responses',$userAccess) || $MSTEAM->id=='1') {
		   ?>
		   <label><?php echo $msg_viewticket103; ?></label>
           <input type="text" class="input-xxlarge" name="response" tabindex="<?php echo (++$tabIndex); ?>" value="" onkeyup="if(this.value!=''){jQuery('.respDeptArea').slideDown()}else{jQuery('.respDeptArea').slideUp()}">
		   
		   <div class="respDeptArea">
		   <label><?php echo $msg_viewticket104; ?></label>
		   <?php
           // If global log in no filter necessary..
           $q_dept = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                     or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
           while ($DEPT = mysql_fetch_object($q_dept)) {
           ?>
           <label class="checkbox">
		    <input type="checkbox" name="dept[]" value="<?php echo $DEPT->id; ?>" checked="checked"> <?php echo mswSpecialChars($DEPT->name); ?>
           </label>
		   <input type="hidden" name="deptall[]" value="<?php echo $DEPT->id; ?>">
		   <?php
           }
           ?>
           </div>
		   <?php
		   }
		   ?>
		   
		  </div>
          
		  <div class="span4">
		   <label><?php echo $msg_viewticket78; ?></label>
		   <div>
            <span class="attachBox"><input type="file" class="input-small" name="attachment[]"></span>
		   </div>
           <?php
           if (LICENCE_VER=='unlocked') {
           ?>
           <p class="attachlinks">
            <button class="btn" type="button" title="<?php echo mswSpecialChars($msg_newticket37); ?>" onclick="ms_attachBox('add','<?php echo ADMIN_ATTACH_BOX_OVERRIDE; ?>')">+</button>
            <button class="btn" type="button" title="<?php echo mswSpecialChars($msg_newticket38); ?>" onclick="ms_attachBox('remove')">-</button>
           </p>
           <?php
           }
           
		   ?>
		   <div class="ticketOptionsArea">
		   <?php
		   // Merging only allowed for standard tickets..
		   if (TICKET_TYPE=='ticket' && ($MSTEAM->id=='1' || $MSTEAM->mergeperms=='yes')) {
		   ?>
		   <div id="mergeBoxArea">
		   <script type="text/javascript">
           //<![CDATA[
           jQuery(document).ready(function() {
             jQuery('.nyroModal').nyroModal();
           });
           //]]>
           </script>
		   <label><?php echo $msg_viewticket102; ?></label>
		   <div class="input-prepend input-append">
		    <span class="add-on"><?php echo $msg_viewticket98; ?>:</span>
		    <input type="text" class="input-small" placeholder="00000" name="mergeid">
			<span class="add-on"><a href="?p=view-ticket&amp;merge=<?php echo $_GET['id']; ?>&amp;vis=<?php echo $SUPTICK->visitorID; ?>" class="nyroModal"><i class="icon-search"></i></a></span>
		   </div> 
		   </div>
		   <?php
		   }
		   ?>
		   
		   <div class="row-fluid">
		     
			 <div class="span5">
			 <label><?php echo $msg_viewticket17; ?></label>
			 <select name="status" class="span6" style="margin-right:20px;width:80px">
              <option value="open" selected="selected"><?php echo $msg_viewticket14; ?></option>
              <option value="close"><?php echo $msg_viewticket15; ?></option>
              <option value="closed"><?php echo $msg_viewticket16; ?></option>
              <option value="submit_report"><?php echo $msg_viewticket91; ?></option>
             </select>
			 </div>
			 
			 <div class="span7">
			 <label><?php echo $msg_viewticket18; ?></label>
			 <select name="mail" class="span6" style="width:70px">
              <option value="yes" selected="selected"><?php echo $msg_script4; ?></option>
              <option value="no"><?php echo $msg_script5; ?></option>
             </select>
			 </div>
		   
		   </div>
		   
		   <?php
		   if ($MSTEAM->id=='1') {
		   ?>
		   <label style="margin-top:7px"><?php echo $msg_viewticket109; ?></label>
		   <label class="checkbox">
            <input type="checkbox" name="history" value="yes" checked="checked"> <?php echo $msg_script4; ?>
           </label>
		   <?php
		   }
		   ?>
		   
		   </div>
		   
		  </div>
         
		 </div>
		 
		</div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <button class="btn btn-primary" type="button" onclick="addReply()"><i class="icon-plus"></i> <?php echo mswCleanData($msg_viewticket13); ?></button>
	   <button class="btn" type="button" onclick="ms_textPreview('view-ticket','comments','previewArea')" id="prev"><i class="icon-search"></i> <?php echo mswCleanData($msg_viewticket55); ?></button>
	   <button class="btn" type="button" onclick="ms_closePreview('comments','previewArea')" style="display:none" id="clse"><i class="icon-remove"></i> <?php echo mswCleanData($msg_viewticket101); ?></button>
       <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=<?php echo (TICKET_TYPE=='dispute' ? 'disputes' : 'open'); ?>')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
      </div>
	  <?php
	  }else {
	  $url = (TICKET_TYPE=='dispute' ? '?p=view-dispute&amp;id='.$_GET['id'].'&amp;act=reopen' : '?p=view-ticket&amp;id='.$_GET['id'].'&amp;act=reopen');
	  if ($SUPTICK->spamFlag=='yes') {
	  ?>
      <div class="alert alert-success" id="replyArea">
	   <p class="nodata"><?php echo $msg_spam3; ?></p>
      </div>
      <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=spam')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
      <?php
	  }else if ($SUPTICK->ticketStatus=='submit_report' && $SUPTICK->spamFlag=='no') {
	  	?>
      <div class="alert alert-success" id="replyArea">
	   <p class="nodata"><?php echo str_replace('{url}',$url,$msg_viewticket123); ?></p>
      </div>
      <?php
	  } else {
	  ?>
      <div class="alert alert-success" id="replyArea">
	   <p class="nodata"><?php echo str_replace('{url}',$url,$msg_viewticket45); ?></p>
      </div>
      <?php
	  }
	  }
	  // Show ticket history..
	  if ($SETTINGS->ticketHistory=='yes' && $MSTEAM->ticketHistory=='yes') {
	  $qTH = mysql_query("SELECT * FROM `".DB_PREFIX."tickethistory`
             WHERE `ticketID` = '{$_GET['id']}'
             ORDER BY `ts` DESC
             ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	  $historyRows = mysql_num_rows($qTH);
	  ?>
	  <div class="block" style="margin-top:40px">
	   <p class="block-heading" id="hisblockhead">(<span id="hiscount"><?php echo @number_format($historyRows); ?></span>) <?php echo strtoupper($msg_viewticket110).' (#'.mswTicketNumber($_GET['id']); ?>)<?php echo ($historyRows>0 ? (USER_DEL_PRIV=='yes' ? ' <a class="toggleFields pull-right" href="#" onclick="confirmMessageExecute(\''.mswSpecialChars($msg_script_action).'\',\'history\',\'0##'.$_GET['id'].'\');return false"><i class="icon-remove"></i> '.$msg_viewticket118.'</a> ' : '').'<a class="toggleFields pull-right" href="index.php?p=view-ticket&amp;exportHistory='.$_GET['id'].'"><i class="icon-save"></i> '.$msg_viewticket112.'</a>' : ''); ?></p>
	   <div class="block-body" style="max-height:300px;overflow:auto" id="historyArea">
	     <?php
		 if ($historyRows>0) {
		   while ($HIS = mysql_fetch_object($qTH)) {
		     echo '<span class="historyEntry" id="history_entry_'.$HIS->id.'"><i class="icon-caret-right"></i> <span class="highlighter">'.$MSDT->mswDateTimeDisplay($HIS->ts,$SETTINGS->dateformat).' - '.$MSDT->mswDateTimeDisplay($HIS->ts,$SETTINGS->timeformat).'</span> - '.mswCleanData($HIS->action).(USER_DEL_PRIV=='yes' ? ' <i class="icon-remove" style="cursor:pointer" onclick="mswRemoveHistory(\''.$HIS->id.'\',\'0\')" title="'.mswSpecialChars($msg_public_history12).'"></i>' : '').'</span>';
		   }
		 } else {
		 ?>
		 <p class="nodata"><?php echo $msg_viewticket111; ?></p>
		 <?php
		 }
		 ?>
	   </div>
	  </div>
	  <?php
	  }
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
  
  </div>