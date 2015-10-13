<?php if (!defined('PARENT')) { exit; } 
$totalBackup = 0;
$msSPScheme  = mswDBSchemaArray();
?>
<div class="content">
  <script type="text/javascript">
  //<![CDATA[
  function doBackup() {
    if (!jQuery('input[name="download"]:checked').val()){
	  return ms_fieldCheck('none','none');
	}
  }
  //]]>
  </script>
  <div class="header">
    
	<h1 class="page-title"><?php echo $msg_adheader30.' ('.str_replace('{count}',count($msSPScheme),$msg_backup16).')'; ?></h1>
	
	<span class="clearfix"></span>
	
  </div>
        
  <ul class="breadcrumb">
    <li><?php echo $msg_adheader37; ?> <span class="divider">/</span></li>
    <li class="active"><?php echo $msg_adheader30; ?></li>
  </ul>
  
  <?php
  // Updated..
  if (isset($OK)) {
    echo mswActionCompleted($msg_backup15);
  }
  ?>

  <form method="post" id="form" action="index.php?p=<?php echo $_GET['p']; ?>" onsubmit="doBackup()">
  <div class="container-fluid" style="margin-top:20px">
    
	<div class="row-fluid">
	
	  <div class="well">
       <table class="table table-striped table-hover">
        <thead>
         <tr>
          <th style="width:21%"><?php echo $msg_backup3; ?></th>
		  <th style="width:10%"><?php echo $msg_backup4; ?></th>
          <th style="width:14%"><?php echo $msg_backup5; ?></th>
          <th style="width:25%"><?php echo $msg_backup6; ?></th>
          <th style="width:22%"><?php echo $msg_backup7; ?></th>
		  <th style="width:17%"><?php echo $msg_backup8; ?></th>
         </tr>
        </thead>
        <tbody>
		 <?php
		 $q = mysql_query("SHOW TABLE STATUS FROM ".DB_NAME);
         while ($DB = mysql_fetch_object($q)) {
         $SCHEMA = (array)$DB;
         if (in_array($SCHEMA['Name'],$msSPScheme)) {
         $size   = ($SCHEMA['Rows']>0 ? $SCHEMA['Data_length']+$SCHEMA['Index_length'] : '0');
         $ctTS   = strtotime($SCHEMA['Create_time']);
         $utTS   = strtotime($SCHEMA['Update_time']);
		 ?>
         <tr>
          <td><?php echo $SCHEMA['Name']; ?></td>
		  <td><?php echo $SCHEMA['Rows']; ?></td>
          <td><?php echo ($SCHEMA['Rows']>0 ? mswFileSizeConversion($size) : '0'); ?></td>
          <td><?php echo date($SETTINGS->dateformat,$ctTS); ?></td>
          <td><?php echo date($SETTINGS->dateformat,$utTS); ?></td>
		  <td><?php echo $SCHEMA['Engine']; ?></td>
         </tr>
		 <?php
		 $totalBackup = ($totalBackup+$size);
		 }
		 }
		 ?>
        </tbody>
       </table>
      </div>
	  
	  <div class="well" style="margin-bottom:10px;padding-bottom:0">
	    
		<div class="row-fluid">
         <div class="span5">
		  <label class="checkbox">
           <input type="checkbox" name="download" value="yes" checked="checked"> <?php echo $msg_backup11; ?> <b>(<?php echo $msg_settings102.' '.mswFileSizeConversion($totalBackup); ?>)</b>
          </label>
		  <label class="checkbox">
          <input type="checkbox" name="compress" value="yes"> <?php echo $msg_backup13; ?> <b>(GZ)</b>
         </label>
		 </div>
         <div class="span7">
		 <label><?php echo $msg_backup12; ?>:</label>
         <input type="text" tabindex="<?php echo ++$tabIndex; ?>" name="emails" class="input-xxlarge" value="<?php echo mswSpecialChars($SETTINGS->backupEmails); ?>">
		 </div>
        </div>
	    
	  </div>
	  
	  <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	    <input type="hidden" name="process" value="1">
        <button class="btn btn-primary" type="submit"><i class="icon-save"></i> <?php echo mswCleanData($msg_backup14); ?></button>
      </div>
	  <?php
	  // Footer links..
	  include(PATH.'templates/footer-links.php');
	  ?>
    </div>
	
  </div>
  </form>

</div>