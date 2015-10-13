<?php if (!defined('PARENT')) { exit; }
$countOfCusFields  = mswRowCount('cusfields WHERE `enField` = \'yes\'');
$repType           = ($REPLY->replyType=='admin' ? 'admin' : 'reply');
?>
<div class="content">
  
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_viewticket36; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader41; ?> <span class="divider">/</span></li>
	<li><a href="?p=view-ticket&amp;id=<?php echo $REPLY->ticketID; ?>"><?php echo $msg_portal8; ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_viewticket37; ?></li>
  </ul>
  
  <?php
  // Updated..
  if (isset($OK)) {
    echo mswActionCompleted($msg_viewticket38);
  }
  ?>

  <form method="post" action="?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('comments','tabAreaAdd')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabAreaAdd">
       <li class="active"><a href="#one" data-toggle="tab" onclick="jQuery('#prev').show();return false;"><i class="icon-file-text-alt"></i> <?php echo $msg_edit; ?></a></li>
       <?php
	   if ($countOfCusFields>0) {
	   ?>
       <li><a href="#two" data-toggle="tab" onclick="jQuery('#prev').hide();return false"><i class="icon-list-alt"></i> <?php echo $msg_add2; ?></a></li>
	   <?php
	   }
	   ?>
	  </ul>

	  <div id="myTabContent" class="tab-content">
	   <div class="tab-pane active in" id="one">
	    <div class="well">
		
		  <div class="addCommsWrapper">
		  <?php
		  // BBCode..
		  include(PATH.'templates/system/bbcode-buttons.php');
		  ?>
		  <textarea name="comments" rows="15" cols="40" id="comments" tabindex="<?php echo (++$tabIndex); ?>"><?php echo mswSpecialChars($REPLY->comments); ?></textarea>
		  <?php
		  // Preview area..do not remove empty div
		  ?>
		  <div id="previewArea" class="previewArea prevTickets" onclick="ms_closePreview('comments','previewArea')"></div>
		  </div>
		
		</div>
	   </div>
	   <?php
	   if ($countOfCusFields>0) {
	   ?>
	   <div class="tab-pane fade" id="two">
	    <div class="well">
		 
		<?php
		// Custom fields..
        $qF = mysql_query("SELECT * FROM `".DB_PREFIX."cusfields`
              WHERE FIND_IN_SET('{$repType}',`fieldLoc`)              > 0
              AND `enField`                                           = 'yes'
			  AND FIND_IN_SET('{$SUPTICK->department}',`departments`) > 0
              ORDER BY `orderBy`
              ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        if (mysql_num_rows($qF)>0) {
        ?>
        <div class="customFields collapse in" id="customFieldsArea">
        <?php
         while ($FIELDS = mysql_fetch_object($qF)) {
          $TF = mswGetTableData('ticketfields','ticketID',$REPLY->ticketID,' AND `replyID` = \''.$REPLY->id.'\' AND `fieldID` = \''.$FIELDS->id.'\'');
          switch ($FIELDS->fieldType) {
            case 'textarea':
            echo $MSFM->buildTextArea(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,(++$tabIndex),(isset($TF->fieldData) ? $TF->fieldData : ''));
            break;
            case 'input':
            echo $MSFM->buildInputBox(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,(++$tabIndex),(isset($TF->fieldData) ? $TF->fieldData : ''));
            break;
            case 'select':
            echo $MSFM->buildSelect(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,$FIELDS->fieldOptions,(++$tabIndex),(isset($TF->fieldData) ? $TF->fieldData : ''));
            break;
            case 'checkbox':
            echo $MSFM->buildCheckBox(mswCleanData($FIELDS->fieldInstructions),$FIELDS->id,$FIELDS->fieldOptions,(isset($TF->fieldData) ? $TF->fieldData : ''));
            break;
          }
         }
        ?>
        </div>
        <?php
        }
	    ?>
		 
		</div>
	   </div>
	   <?php
	   }
	   ?>
	   </div>
	  </div>
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
       <input type="hidden" name="process" value="1">
	   <input type="hidden" name="ticketID" value="<?php echo $SUPTICK->id; ?>">
       <button class="btn btn-primary" type="submit"><i class="icon-ok"></i> <?php echo $msg_viewticket37; ?></button>
       <button class="btn" type="button" onclick="ms_textPreview('view-ticket','comments','previewArea')" id="prev"><i class="icon-search"></i> <?php echo mswCleanData($msg_viewticket55); ?></button>
	   <button class="btn" type="button" onclick="ms_closePreview('comments','previewArea')" style="display:none" id="clse"><i class="icon-remove"></i> <?php echo mswCleanData($msg_viewticket101); ?></button>
       <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=view-ticket&amp;id=<?php echo $REPLY->ticketID; ?>')"><i class="icon-remove"></i> <?php echo mswCleanData($msg_levels11); ?></button>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
  
  </div>
  </form>

</div>