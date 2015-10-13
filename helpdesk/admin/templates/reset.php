<?php if (!defined('RESET_LOADER')) { exit; } ?>
<!DOCTYPE html>
<html lang="<?php echo (isset($html_lang) ? $html_lang : 'en'); ?>" dir="<?php echo $lang_dir; ?>">
  <head>
    <meta charset="<?php echo $msg_charset; ?>">
    <title><?php echo ($title ? mswSpecialChars($title).': ' : '').$msg_script.' - '.mswCleanData($msg_adheader); ?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Global CSS -->
    <link rel="stylesheet" href="templates/css/bootstrap.css" type="text/css">
	<link rel="stylesheet" href="templates/css/bootstrap-responsive.css" type="text/css">
    <link rel="stylesheet" href="templates/css/theme.css" type="text/css">
    <link rel="stylesheet" href="templates/css/font-awesome.css">
	<script src="templates/js/jquery.js" type="text/javascript"></script>
	<?php
	// Load additional JS/CSS..
	include(PATH.'templates/head-loader.php');
	?>
	<link rel="stylesheet" href="templates/css/ms.css" type="text/css">
	<script src="templates/js/ms-ops.js" type="text/javascript"></script>
	<script src="templates/js/ms-global.js" type="text/javascript"></script>
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="templates/js/html5.js"></script>
    <![endif]-->

    <link rel="SHORTCUT ICON" href="favicon.ico">
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
  <body class="">
  <!--<![endif]-->

  <form method="post" id="form" action="?p=reset">

  <?php
  if (isset($OK)) {
    echo mswActionCompleted($msg_passreset6);
  }
  ?>

  <div class="container-fluid" style="margin-top:5px">

    <div class="row-fluid">

	 <div class="block">
      <p class="block-heading"><?php echo strtoupper($title.' - '.$msg_passreset); ?></p>
	  <table class="table table-striped table-hover">
        <thead>
         <tr>
          <th style="width:33%"><?php echo strtoupper($msg_passreset7); ?></th>
		  <th style="width:33%"><?php echo strtoupper($msg_passreset2); ?></th>
		  <th style="width:33%"><?php echo strtoupper($msg_passreset3); ?></th>
		 </tr>
        </thead>
        <tbody>
	    <?php
        $q = mysql_query("SELECT `id`,`email`,`accpass`,`name` FROM `".DB_PREFIX."users` ORDER BY `name`")
             or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        while ($U = mysql_fetch_object($q)) {
        ?>
		<tr>
		  <td<?php echo ($U->id=='1' ? ' style="color:#434b5c"' : ''); ?>><i class="icon-user"></i> <b><?php echo mswSpecialChars($U->name).($U->id=='1' ? ' (ADMIN)' : ''); ?></b><input type="hidden" name="name[]" value="<?php echo mswSpecialChars($U->name); ?>"></td>
          <td><input type="hidden" name="id[]" value="<?php echo $U->id; ?>"><input type="text" name="mail[]" value="<?php echo mswSpecialChars($U->email); ?>" class="input-xlarge"></td>
		  <td><input type="hidden" name="password2[]" value="<?php echo $U->accpass; ?>"><input type="password" id="<?php echo $U->id; ?>" name="password[]" value="" class="input-xlarge"></td>
		</tr>
	    <?php
	    }
	    ?>
		</tbody>
	  </table>
	 </div>

	 <div class="btn-toolbar" style="margin-top:0;padding-top:0">
	   <label class="checkbox">
	    <input type="checkbox" name="email" value="yes" checked="checked"> <?php echo $msg_passreset5; ?>
	   </label><br>
       <button onclick="ms_confirmButtonAction('form','<?php echo mswSpecialChars($msg_script_action); ?>','process');return false;" class="btn btn-primary" type="submit"><i class="icon-ok"></i> <?php echo $msg_passreset4; ?></button>
	 </div>

    </div>

  </div>
  </form>

  </body>
</html>