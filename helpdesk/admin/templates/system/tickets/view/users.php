<?php if (!defined('TICKET_LOADER')) { exit; } ?> 
      <div id="userAssignArea">
	   <div class="block">
	    <p class="block-heading"><?php echo strtoupper($msg_viewticket92); ?></p>
	    <div class="block-body">
	    <?php
        $boomUsers    = explode(',',$SUPTICK->assignedto);
        $q_users      = mysql_query("SELECT * FROM `".DB_PREFIX."users` ORDER BY `name`") 
                        or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));

        while ($USERS = mysql_fetch_object($q_users)) {
          $checked='';
          $toggleHideShow="style=''";
          $class='';
          if($MSTEAM->id == $USERS->id){
            $checked='checked';
            $toggleHideShow="style='display:none;'";
            $class="class='creater'";
          }
        ?>
	      <label class="checkbox" <?php echo $toggleHideShow; ?> >
         <input type="checkbox" name="assigned[]" <?php echo $checked; ?> <?php echo $class; ?> value="<?php echo $USERS->id; ?>"<?php echo (in_array($USERS->id,$boomUsers) ? ' checked="checked"' : ''); ?>> <?php echo mswCleanData(mswSpecialChars($USERS->name).' <span class="email">('.$USERS->email.')</span> '.(in_array('users',$userAccess) || $MSTEAM->id=='1' ? '<a href="?p=team&amp;edit='.$USERS->id.'" title="'.mswSpecialChars($msg_user14).'"><i class="icon-pencil"></i></a>' : '')); ?>
        </label>
	    <?php
        } 
        ?>
	    </div>
	   </div>
	   <div class="btn-toolbar" style="margin-top:0;padding-top:0;text-align:center">
        <button class="btn btn-primary" type="button" onclick="ms_updateAssignedUsers('<?php echo $_GET['id']; ?>')"><i class="icon-ok"></i> <?php echo $msg_viewticket121; ?></button>
	    <button class="btn btn-link" type="button" onclick="jQuery('#userAssignArea').slideUp()"><i class="icon-remove"></i> <?php echo $msg_viewticket100; ?></button>
       </div>
	  </div>