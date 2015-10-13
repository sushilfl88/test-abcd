<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: defined.inc.php
  Description: User Defined Admin Functions

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

/*
 Relative path to files in the support root. In most cases this will NOT need changing.
 Some servers may require the full server path. If this is the case enter the path below.
 Example:
 define('REL_PATH', '/home/serverpath/public_html/support/');
*/ 
define('REL_PATH', '../');

/*
 If a support team member clicks on an e-mail ticket link and is directed to the admin log in
 page, do you want them to be directed to the ticket after login? Can save time locating
 tickets and be a big time saver.
 1 = Enabled, 0 = Disabled
*/ 
define('REDIRECT_TO_TICKET_ON_LOGIN', 1);

/*
  TICKET SEARCH AUTO CHECK OPTIONS
  Which ticket type checkboxes should be auto checked on search tickets page
*/
define('SEARCH_AUTO_CHECK_TICKETS', 'yes'); 
define('SEARCH_AUTO_CHECK_DISPUTES', 'yes');  
define('SEARCH_AUTO_CHECK_RESPONSES', 'no');  

/*
  AUTO CREATE API KEY - KEY LENGTH
  Max 100 characters
*/
define('API_KEY_LENGTH', 30);

/*
  ENABLE SOFTWARE VERSION CHECK
  Displays on the top bar and is an easy check option to see if new versions have
  been release. You may wish to disable this for clients.
  0 = Disabled, 1 = Enabled
*/
define('DISPLAY_SOFTWARE_VERSION_CHECK', 1);  

/*
  ADMIN MAX ATTACHMENT BOXES
  Admin override for max attachments. Can be higher than visitor restriction.
  Applies only in commercial version.
*/  
define('ADMIN_ATTACH_BOX_OVERRIDE', 20);

/*
  STANDARD RESPONSES SELECT TEXT DISPLAY LIMIT
  Restrict display in standard response drop downs to this limit
*/
define('STANDARD_RESPONSE_DD_TEXT_LIMIT', 115);

/*
  CATEGORIES SUMMARY TEXT DISPLAY LIMIT
  Restrict display for category summary in admin
*/
define('CATEGORIES_SUMMARY_TEXT_LIMIT', 115);

/*
  IP LOOKUP
  Service for url lookup. Use {ip} where IP address must be in url
*/
define('IP_LOOKUP', 'http://whatismyipaddress.com/ip/{ip}');

?>