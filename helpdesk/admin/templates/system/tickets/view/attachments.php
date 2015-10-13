<?php if (!defined('TICKET_LOADER')) { exit; }
$aTickID     = (int)$aTickID;
$aTickReply  = (int)$aTickReply;
$qA = mysql_query("SELECT *,DATE(FROM_UNIXTIME(`ts`)) AS `addDate` FROM `".DB_PREFIX."attachments`
      WHERE `ticketID` = '{$aTickID}' AND `replyID` = '{$aTickReply}'
      ORDER BY `fileName`
      ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
if (mysql_num_rows($qA)>0) {
?>
<div id="attachments_<?php echo $aTickID; ?>_<?php echo $aTickReply; ?>" class="block" style="display:none">

<table class="table table-striped table-hover">
<thead>
 <tr class="attachmentTRBG">
  <?php
  if (USER_DEL_PRIV=='yes') {
  ?>
  <th style="width:5%">
  <input onclick="selectAll('attachments_<?php echo $aTickID; ?>_<?php echo $aTickReply; ?>',(this.checked ? 'on' : 'off'))" name="log" type="checkbox" value="1">
  </th>
  <?php
  }
  ?>
  <th style="width:<?php echo (USER_DEL_PRIV=='yes' ? '85' : '90'); ?>%"><?php echo $msg_viewticket32; ?></th>
  <th style="width:10%"><?php echo $msg_viewticket33; ?></th>
 </tr>
</thead>
<tbody>
 <?php
 while ($ATT = mysql_fetch_object($qA)) {
  $ext     = strrchr($ATT->fileName, '.');
  $split   = explode('-',$ATT->addDate);
  $folder  = '';
  // Check for newer folder structure..
  if (file_exists($SETTINGS->attachpath.'/'.$split[0].'/'.$split[1].'/'.$ATT->fileName)) {
    $folder  = $split[0].'/'.$split[1].'/';
  }
  ?>
  <tr id="attrow<?php echo $ATT->id; ?>">
   <?php
   if (USER_DEL_PRIV=='yes') {
   ?>
   <td>
   <input type="checkbox" name="att<?php echo $aTickID; ?>_<?php echo $aTickReply; ?>[]" value="<?php echo $ATT->id; ?>">
   </td>
   <?php
   }
   ?>
   <td>[<?php echo substr(strtoupper($ext),1); ?>] <a href="?attachment=<?php echo $ATT->id; ?>" title="<?php echo mswSpecialChars($msg_viewticket50); ?>"><?php echo substr($ATT->fileName,0,strpos($ATT->fileName,'.')); ?></a></td>
   <td><?php echo mswFileSizeConversion($ATT->fileSize); ?></td>
  </tr>
  <?php
  }
 ?>
</tbody>
</table>

<?php
if (USER_DEL_PRIV=='yes') {
?>
<div class="btn-toolbar" style="margin-top:0;padding-top:0;text-align:center" id="but_ar_<?php echo $aTickID; ?>_<?php echo $aTickReply; ?>">
 <button class="btn btn-danger" type="button" onclick="ms_deleteAttachments('<?php echo $aTickID; ?>','<?php echo $aTickReply; ?>','<?php echo mswSpecialChars($msg_script_action); ?>')"><i class="icon-trash"></i> <?php echo $msg_viewticket34; ?></button>
 <button class="btn btn-link" type="button" onclick="jQuery('#attachments_<?php echo $aTickID; ?>_<?php echo $aTickReply; ?>').slideUp()"><i class="icon-remove"></i> <?php echo $msg_viewticket100; ?></button>
</div>
<?php
}
?>

</div>
<?php
}
?>