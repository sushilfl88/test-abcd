<?php if (!defined('PARENT')) { exit; }
$ID  = (isset($_GET['merge']) ? (int)$_GET['merge'] : '0');
$VIS = (isset($_GET['vis']) ? (int)$_GET['vis'] : '0');
if ($ID==0 || $VIS==0) { exit; }
?>
<!DOCTYPE html>
<html lang="<?php echo (isset($html_lang) ? $html_lang : 'en'); ?>" dir="<?php echo $lang_dir; ?>">
  <head>
    <meta charset="<?php echo $msg_charset; ?>">
    <title></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="templates/css/bootstrap.css" type="text/css">
	<link rel="stylesheet" href="templates/css/bootstrap-responsive.css" type="text/css">
    <link rel="stylesheet" href="templates/css/theme.css" type="text/css">
    <link rel="stylesheet" href="templates/css/font-awesome.css">
	<script src="templates/js/jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" href="templates/css/ms.css" type="text/css">
    <script src="templates/js/ms-global.js" type="text/javascript"></script>
	<script src="templates/js/ms-ops.js" type="text/javascript"></script>
	<script src="templates/js/plugins/jquery.nyroModal.js" type="text/javascript"></script>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="templates/js/html5.js"></script>
    <![endif]-->

    <link rel="SHORTCUT ICON" href="favicon.ico">
  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7"> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8"> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9"> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
  <body class="">
  <!--<![endif]-->

  <div class="block">

	<p class="block-heading"><?php echo strtoupper($msg_merge); ?></p>

	<?php
	$depFilters = mswSQLDepartmentFilter($ticketFilterAccess,'WHERE');
	$q = mysql_query("SELECT `id`,`subject` FROM ".DB_PREFIX."tickets
         $depFilters
		 ".($depFilters ? 'AND' : 'WHERE')." `visitorID`    = '{$VIS}'
         AND `id`          != '{$ID}'
         AND `assignedto`  != 'waiting'
		 AND `ticketStatus` = 'open'
		 AND `isDisputed`   = 'no'
		 AND `spamFlag`     = 'no'
         ORDER BY `id`
		 ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    if (mysql_num_rows($q)>0) {
	while ($TICKET = mysql_fetch_object($q)) {
	?>
	<div class="block-body mergehover" style="padding:5px;margin:5px">

	 <div class="pull-left">
      [<b>#<?php echo mswTicketNumber($TICKET->id); ?></b>] <?php echo mswSpecialChars($TICKET->subject); ?>
     </div>

	 <div class="pull-right">
      <a href="#" class="nyroModalClose" onclick="mswSelectMerge('<?php echo mswTicketNumber($TICKET->id); ?>');return false" title="<?php echo mswSpecialChars($msg_merge2); ?> (#<?php echo mswTicketNumber($TICKET->id); ?>)"><i class="icon-plus"></i> <?php echo $msg_viewticket119; ?></a>
     </div>

	 <span class="clearfix"></span>

	</div>
	<?php
	}
	} else {
	?>
	<p class="nodata" style="padding:20px;font-size:11px"><?php echo $msg_open10; ?></p>
	<?php
	}
	?>

  </div>

  <!-- Twitter Bootstrap -->
  <script src="templates/js/plugins/jquery.bootstrap.js"></script>
  <script type="text/javascript">
  //<![CDATA[
  function mswSelectMerge(ticket) {
    jQuery('input[name="mergeid"]').val(ticket);
  }
  //]]>
  </script>

  </body>
</html>