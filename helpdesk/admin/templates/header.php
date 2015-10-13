<?php if (!defined('PARENT')) { exit; } ?>
<!DOCTYPE html>
<html lang="<?php echo (isset($html_lang) ? $html_lang : 'en'); ?>" dir="<?php echo $lang_dir; ?>">

  <head>

	<meta charset="<?php echo $msg_charset; ?>">
    <title><?php echo ($title ? $title.': ' : '').$msg_script.' - '.$msg_adheader.(LICENCE_VER!='unlocked' ? ' (Free Version)' : '').(mswCheckBetaVersion()=='yes' ? ' - BETA VERSION' : ''); ?></title>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
	<?php
	// HTML5 shiv - IE6-8 support of HTML5 elements
	?>
    <!--[if lt IE 9]>
    <script src="templates/js/html5.js"></script>
    <![endif]-->

    <link rel="SHORTCUT ICON" href="favicon.ico">
	<?php
	// For meta reloads, do NOT remove..
	if (isset($metaReload)) {
	echo $metaReload;
	}
	?>

  </head>

  <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
  <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
  <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
  <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
  <!--[if (gt IE 9)|!(IE)]><!-->
  <body class="">
  <!--<![endif]-->

   <div class="navbar" id="navbar">
        <div class="navbar-inner">
                <ul class="nav pull-right">

                    <?php
                    if (LICENCE_VER=='locked') {
                    ?>
					<!-- <li>
					   <a href="?p=purchase" class="visible-phone visible-tablet visible-desktop" role="button" title="Purchase Full Version">
					        <i class="icon-shopping-cart"></i> Purchase
					   </a>
					</li> -->
                    <?php
					}
					?>
					<li>
					   <a href="index.php" class="visible-phone visible-tablet visible-desktop" role="button" title="<?php echo mswSpecialChars($msg_adheader11); ?>">
					        <i class="icon-dashboard"></i> <?php echo $msg_adheader11; ?>
					   </a>
					</li>
					<?php
					if ($MSTEAM->mailbox=='yes') {
					?>
					<li>
					   <a href="index.php?p=mailbox" class="visible-phone visible-tablet visible-desktop" role="button" title="<?php echo mswSpecialChars($msg_adheader61); ?>">
					        <i class="icon-envelope"></i> <?php echo $msg_adheader61; ?>
					   </a>
					</li>
					<?php
					}
					if ($MSTEAM->helplink=='yes') {
					?>
					<li>
					   <a href="../docs/<?php echo (isset($_GET['p']) ? helpPageLoader($_GET['p']).'.html' : 'admin-home.html'); ?>" class="visible-phone visible-tablet visible-desktop" role="button" title="<?php echo mswSpecialChars($msg_adheader12); ?>" onclick="window.open(this);return false">
					        <i class="icon-question-sign"></i> <?php echo $msg_adheader12; ?>
					   </a>
					</li>
					<?php
					}
					?>
                    <li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"></i> <?php echo mswCleanData($MSTEAM->name); ?>
                            <i class="icon-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li class="divider visible-phone"></li>
							<?php
							if ($MSTEAM->profile=='yes' || $MSTEAM->id=='1') {
							?>
							<li><a tabindex="-1" href="<?php echo ($MSTEAM->id=='1' ? '?p=team&amp;edit=1' : '?p=cp'); ?>" title="<?php echo mswSpecialChars($msg_header17); ?>"><?php echo $msg_header17; ?></a></li>
                            <?php
							}
							// Show version check..
							if ($MSTEAM->id=='1' && DISPLAY_SOFTWARE_VERSION_CHECK && mswCheckBetaVersion()=='no') {
							?>
							<li><a href="?p=vc" title="<?php echo mswSpecialChars($msg_adheader27); ?>"><?php echo $msg_adheader27; ?> (<?php echo $SETTINGS->softwareVersion; ?>)</a></li>
                            <li class="divider"></li>
							<?php
							}
							?>
							<li><a tabindex="-1" href="?p=logout" title="<?php echo mswSpecialChars($msg_adheader10); ?>"><?php echo $msg_adheader10; ?></a></li>
                        </ul>
                    </li>

                </ul>

				<?php
                // Display version check option..
                // Disable for beta versions..
                if (mswCheckBetaVersion()=='yes') {
                ?>
                <a class="brand" href="index.php" title="<?php echo mswSpecialChars($msg_script." - ".$msg_adheader); ?>"><img src="http://localhost/maian_support/helpdesk/admin/templates/images/ost-logo.png" width="232" height="66"><span class="first">qweqwe<?php echo mswCleanData($msg_script." - ".$msg_adheader); ?>&nbsp;&nbsp;&nbsp;(v<?php echo $SETTINGS->softwareVersion; ?> - Beta <?php echo mswGetBetaVersion(); ?>)</span></a>
				<?php
				} else {
				?>
				<a class="brand" href="index.php" title="<?php echo mswSpecialChars($msg_script." - ".$msg_adheader); ?>"><img src="http://localhost/maian_support/helpdesk/admin/templates/images/ost-logo.png" width="232" height="66"><span class="first"><?php echo mswCleanData($msg_script." - ".$msg_adheader); ?></span></a>
				<?php
				}
				?>
        </div>
    </div>

    <div class="sidebar-nav">

        <?php
		// Navigation menu..
		include(PATH.'templates/menu.php');
		?>

	</div>