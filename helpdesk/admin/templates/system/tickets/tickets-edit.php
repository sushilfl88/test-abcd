<?php if (!defined('PARENT')) { exit; } 
checkIsValid($SUPTICK);
$countOfCusFields  = mswRowCount('cusfields WHERE `enField` = \'yes\'');
$dept              = array();
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  function addTicketCusFields(dept) {
    jQuery(document).ready(function() {
     jQuery.ajax({
      url: 'index.php',
      data: 'ajax=add-cus-field&dept='+dept,
      dataType: 'json',
      success: function (data) {
	    if (data['fields']) {
		  if (jQuery('#cusFieldsTab').css('display')=='none') {
		    jQuery('#cusFieldsTab').show();
		  }
		  jQuery('#customFieldsArea').html(data['fields']);
	    } else {
		  if (jQuery('#cusFieldsTab').css('display')!='none') {
		    jQuery('#cusFieldsTab').hide();
		  }
	      jQuery('#customFieldsArea').html(data['fields']);
	    }
      }
     });
    });
    return false;
  }
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo str_replace('{ticket}',mswTicketNumber($SUPTICK->id),$msg_viewticket20); ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader41; ?> <span class="divider">/</span></li>
	<li><a href="?p=view-ticket&amp;id=<?php echo $_GET['id']; ?>"><?php echo $msg_portal8; ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_viewticket21; ?></li>
  </ul>
  
  <?php
  // Updated..
  if (isset($OK)) {
    echo mswActionCompleted($msg_viewticket23);
  }
  ?>

  <form method="post" action="?p=<?php echo $_GET['p'].mswQueryParams(array('p','next')); ?>" onsubmit="return ms_fieldCheck('subject,comments,name,email','tabAreaAdd')">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	  <ul class="nav nav-tabs" id="tabAreaAdd">
       <li class="active"><a href="#one" data-toggle="tab" onclick="jQuery('#prev').show();return false;"><i class="icon-file-text-alt"></i> <?php echo $msg_add; ?></a></li>
       <?php
	   if ($countOfCusFields>0) {
	   ?>
       <li id="cusFieldsTab"><a href="#two" data-toggle="tab" onclick="jQuery('#prev').hide();return false"><i class="icon-list-alt"></i> <?php echo $msg_add2; ?></a></li>
	   <?php
	   }
	   ?>
	  </ul>

	  <div id="myTabContent" class="tab-content">
	   <div class="tab-pane active in" id="one">
	    <div class="well">
		
		  <label><?php echo $msg_newticket15; ?></label>
          <input type="text" class="input-xxlarge" name="subject" tabindex="<?php echo (++$tabIndex); ?>" value="<?php echo mswSpecialChars($SUPTICK->subject); ?>">
		  
		  <label><?php echo $msg_newticket6; ?></label>
		  <select name="dept" tabindex="<?php echo (++$tabIndex); ?>" onchange="addTicketCusFields(this.value)">
		  <?php
          $q_dept = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                    or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
          while ($DEPT = mysql_fetch_object($q_dept)) {
		  $dept[] = $DEPT->id;
          ?>
          <option value="<?php echo $DEPT->id; ?>"<?php echo mswSelectedItem($DEPT->id,$SUPTICK->department); ?>><?php echo mswCleanData($DEPT->name); ?></option>
          <?php
          }
          ?>
		  </select>
		  
		  <label><?php echo $msg_newticket8; ?></label>
		  <select name="priority" tabindex="<?php echo (++$tabIndex); ?>">
		  <?php
          foreach ($ticketLevelSel AS $k => $v) {
          ?>
          <option value="<?php echo $k; ?>"<?php echo mswSelectedItem($k,$SUPTICK->priority); ?>><?php echo $v; ?></option>
          <?php
          }
          ?>
		  </select>
		  
		  <div class="addCommsWrapper">
		  <?php
		  // BBCode..
		  include(PATH.'templates/system/bbcode-buttons.php');
		  ?>
		  <textarea name="comments" rows="15" cols="40" id="comments" tabindex="<?php echo (++$tabIndex); ?>"><?php echo mswSpecialChars($SUPTICK->comments); ?></textarea>
		  <?php
		  // Preview area..do not remove empty div
		  ?>
		  <div id="previewArea" class="previewArea prevTickets" onclick="ms_closePreview('comments','previewArea')"></div>
		  </div>
		
		</div>
	   </div>
	   <?php
	   if ($countOfCusFields>0 && isset($dept[0])) {
	   ?>
	   <div class="tab-pane fade" id="two">
	    <div class="well">
		 
		<?php
		// Custom fields..
        $qF = mysql_query("SELECT * FROM `".DB_PREFIX."cusfields`
              WHERE FIND_IN_SET('ticket',`fieldLoc`)       > 0
              AND `enField`                               = 'yes'
			  AND FIND_IN_SET('{$dept[0]}',`departments`) > 0
              ORDER BY `orderBy`
              ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        if (mysql_num_rows($qF)>0) {
        ?>
        <div class="customFields collapse in" id="customFieldsArea">
        <?php
         while ($FIELDS = mysql_fetch_object($qF)) {
          $TF = mswGetTableData('ticketfields','ticketID',(int)$_GET['id'],' AND `replyID` = \'0\' AND `fieldID` = \''.$FIELDS->id.'\'');
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
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
       <input type="hidden" name="process" value="1">
	   <input type="hidden" name="odeptid" value="<?php echo $SUPTICK->department; ?>">
       <button class="btn btn-primary" type="submit"><i class="icon-ok"></i> <?php echo $msg_viewticket21; ?></button>
       <button class="btn" type="button" onclick="ms_textPreview('view-ticket','comments','previewArea')" id="prev"><i class="icon-search"></i> <?php echo $msg_viewticket55; ?></button>
	   <button class="btn" type="button" onclick="ms_closePreview('comments','previewArea')" style="display:none" id="clse"><i class="icon-remove"></i> <?php echo $msg_viewticket101; ?></button>
       <button class="btn btn-link" type="button" onclick="ms_windowLoc('?p=view-ticket&amp;id=<?php echo $_GET['id']; ?>')"><i class="icon-remove"></i> <?php echo $msg_levels11; ?></button>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
  
  </div>
  </form>

</div>