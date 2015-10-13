<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: version-check.php
  Description: System File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!defined('PARENT')) {
  $HEADERS->err403(true);
}

if (isset($_GET['vck'])) {
  $html = $MSSET->mswSoftwareVersionCheck();
  echo $JSON->encode(
   array(
    'html' => mswNL2BR($html)
   )
  );
  exit;
}

$title = $msg_versioncheck;

include(PATH.'templates/header.php');
include(PATH.'templates/system/version-check.php');
include(PATH.'templates/footer.php');

?>
