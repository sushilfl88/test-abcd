<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Programmed & Designed by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk
  
  B8 Spam System Created by: Tobias Leupold
  Website: http://nasauber.de/opensource/b8/
  
  Configured & Implemented in Maian Support by: David Ian Bennett

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: call.php
  Description: System File - Loads B8 Spam System
  
  DO NOT change anything here unless you know what you are doing

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
  
if (!defined('PARENT')) {
  $HEADERS->err403();
}  
 
// Load B8 classes..
include(dirname(__file__).'/b8.php');

// Get config from DB..
$B8_CFG = mswGetTableData('imap_b8','id','1');
if (!isset($B8_CFG->id)) {
  die('B8 DB Information Not Found. Imap Terminated.'); 
}

// Set config options..
$b8_config      =  array(
 'storage'      => 'mysql',
 'use_relevant' => ($B8_CFG->tokens>0 ? $B8_CFG->tokens : 15),
 'min_dev'      => $B8_CFG->min_dev,
 'rob_x'        => $B8_CFG->x_constant,
 'rob_s'        => $B8_CFG->s_constant
);

// Database connection..
$b8_storage    =  array(
 'database'    => DB_NAME,
 'table_name'  => DB_PREFIX.'imap_b8_filter',
 'host'        => DB_HOST,
 'user'        => DB_USER,
 'pass'        => DB_PASS
);

// Lexer settings..
$b8_lexer        =  array(
 'min_size'      => ($B8_CFG->min_size>0 ? $B8_CFG->min_size : 3),
 'max_size'      => ($B8_CFG->max_size>0 ? $B8_CFG->max_size : 30),
 'allow_numbers' => ($B8_CFG->num_parse=='yes' ? true : false),
 'get_uris'      => ($B8_CFG->uri_parse=='yes' ? true : false),
 'get_bbcode'    => false,
 'old_get_html'  => false,
 'get_html'      => ($B8_CFG->html_parse=='yes' ? true : false)
);

// Degenerator settings..
$b8_degenerator =  array(
 'multibyte'    => ($B8_CFG->multibyte=='yes' ? true : false),
 'encoding'     => ($B8_CFG->encoder ? $B8_CFG->encoder : 'UTF-8')
);

// Create class..
try {
 $MSB8 = new b8(
  $b8_config,
  $b8_storage,
  $b8_lexer,
  $b8_degenerator
 );
}

// Catch error..
catch (Exception $e) {
  $b8_err = $e->getMessage();
}

?>