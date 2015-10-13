<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.faq.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class msFAQ {

// Voting system..
public function vote($s) {
  $id     = (int)$_GET['v'];
  $votes  = array();
  if ($id>0) {
    switch ($_GET['vote']) {
      case 'no':
      $table = '`knotuseful` = (`knotuseful`+1)';
      break;
	  default:
      $table = '`kuseful` = (`kuseful`+1)';
      break;
    }
    mysql_query("UPDATE `".DB_PREFIX."faq` SET
    `kviews`   = (`kviews`+1),
    $table
    WHERE `id` = '{$id}'
    ");
    // If multiple votes are allowed, don`t set cookie and return..
    if ($s->multiplevotes=='yes') {
      return;
    } else {
      // If cookie is set, get array of ids and update with new id..
	  // If not set, just add id to array..
	  if (isset($_COOKIE[md5(SECRET_KEY).COOKIE_NAME])) {
	    $votes   = unserialize($_COOKIE[md5(SECRET_KEY).COOKIE_NAME]);
		$votes[] = $id;
		// Clear the cookie..
        setcookie(md5(SECRET_KEY).COOKIE_NAME,'');
        unset($_COOKIE[md5(SECRET_KEY).COOKIE_NAME]);
      } else {
	    $votes[] = $id;
	  }
	  // Set cookie..
      @setcookie(md5(SECRET_KEY).COOKIE_NAME,serialize($votes),time()+60*60*24*$s->cookiedays);
	}
  }
}

// Attachments..
public function attachments($s) {
  $html  = '';
  $id    = (int)$_GET['a'];
  $q     = mysql_query("SELECT *,
           `".DB_PREFIX."faqattach`.`id` AS `attachID`
		   FROM `".DB_PREFIX."faqassign`
           LEFT JOIN `".DB_PREFIX."faqattach`
           ON `".DB_PREFIX."faqassign`.`itemID`      = `".DB_PREFIX."faqattach`.`id`
           WHERE `".DB_PREFIX."faqassign`.`question` = '{$id}'
		   AND `".DB_PREFIX."faqassign`.`desc`       = 'attachment'
           GROUP BY `".DB_PREFIX."faqassign`.`itemID`
           ORDER BY `".DB_PREFIX."faqattach`.`orderBy`
           ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($ATT = mysql_fetch_object($q)) {
    $ext   = substr(strrchr(($ATT->path ? $ATT->path : $ATT->remote), '.'),1);
    $html .= str_replace(
     array(
	  '{url}',
	  '{name}',
	  '{name_alt}',
	  '{size}',
	  '{filetype}'
     ),
	 array(
	  ($ATT->remote ? $ATT->remote : '?fattachment='.$ATT->attachID),
      ($ATT->name ? mswCleanData($ATT->name) : ($ATT->remote ? basename($ATT->remote) : $ATT->path)),
	  ($ATT->name ? mswSpecialChars($ATT->name) : ($ATT->remote ? basename($ATT->remote) : $ATT->path)),
      mswFileSizeConversion($ATT->size),
      strtoupper($ext)
     ),
     file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/faq-attachment-link.htm')
    ).mswDefineNewline();
  }
  return ($html ? $html : ''); 
}

// FAQ questions..
public function questions($id=0,$limit,$s,$search=array(),$orderOverride='',$queryAdd='') {
  global $msg_pkbase8;
  $data   = '';
  // Search mode..
  if (isset($search[0],$search[1])) {
    $q  = mysql_query("SELECT SQL_CALC_FOUND_ROWS *,
	      `".DB_PREFIX."faq`.`id` AS `faqID`,
		  `".DB_PREFIX."faq`.`question` AS `faqQuestion` 
		  FROM `".DB_PREFIX."faq`
          ".$search[0]."
		  AND `enFaq` = 'yes'
		  ORDER BY `orderBy`
		  LIMIT $limit,".$s->quePerPage
	      ) or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    if ($search[1]=='yes') {
      $c            = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS `rows`"));
      return (isset($c->rows) ? $c->rows : '0');
	}
  } else {
    $q  = mysql_query("SELECT *,
	      `".DB_PREFIX."faq`.`id` AS `faqID`,
		  `".DB_PREFIX."faq`.`question` AS `faqQuestion` 
		  FROM `".DB_PREFIX."faq`
	      LEFT JOIN `".DB_PREFIX."faqassign`
		  ON `".DB_PREFIX."faq`.`id`            = `".DB_PREFIX."faqassign`.`question`
          WHERE `enFaq`                         = 'yes'
		  ".($id>0 ? 'AND `'.DB_PREFIX.'faqassign`.`itemID` = \''.$id.'\'' : '')."
		  AND `".DB_PREFIX."faqassign`.`desc`   = 'category'
		  ".$queryAdd."
		  ORDER BY ".($orderOverride ? $orderOverride : '`orderBy`')."
		  LIMIT $limit,".$s->quePerPage
		  ) or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
  while ($LINKS = mysql_fetch_object($q)) {
    $data .= str_replace(
	 array('{article}','{url_params}','{question}','{count}'),
     array(
	  $LINKS->faqID,
	  mswQueryParams(array('a','p')),
	  mswCleanData($LINKS->faqQuestion),
      number_format($LINKS->kviews)
     ),
     file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/faq-question-link.htm')
    ); 
  }
  return ($data ? 
            trim($data) : 
              str_replace('{text}',
                  $msg_pkbase8,
                  file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/nothing-found.htm')
                )
              );
}

// FAQ menu links..
public function menu() {
  $data   = '';
  $q      = mysql_query("SELECT * FROM `".DB_PREFIX."categories` 
            WHERE `enCat` = 'yes' 
            AND `subcat`  = '0'
            ORDER BY `orderBy`
            ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  while ($CATS = mysql_fetch_object($q)) {
    $data .= str_replace(
	 array('{cat}','{category}','{count}'),
     array(
	  $CATS->id,
	  mswSpecialChars($CATS->name), 
      mswRowCount('faqassign LEFT JOIN `'.DB_PREFIX.'faq` ON `'.DB_PREFIX.'faq`.`id` = `'.DB_PREFIX.'faqassign`.`question` 
	               WHERE `itemID` = \''.$CATS->id.'\' AND `desc` = \'category\' AND `'.DB_PREFIX.'faq`.`enFaq` = \'yes\'')
     ),
     file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/faq-cat-menu-link.htm')
    );
	// Sub categories..
	if ((isset($_GET['c']) && $_GET['c']==$CATS->id) || (defined('IS_SUB') && IS_SUB==$CATS->id)) {
	  $qS = mysql_query("SELECT * FROM `".DB_PREFIX."categories` 
            WHERE `enCat` = 'yes' 
            AND `subcat`  = '{$CATS->id}'
            ORDER BY `orderBy`
            ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
      while ($SUBS = mysql_fetch_object($qS)) {
        $data .= str_replace(
	     array('{cat}','{category}','{category-alt}','{count}'),
         array(
	      $SUBS->id,
	      mswSpecialChars($SUBS->name),
		  mswSpecialChars($SUBS->name. ' ('.$CATS->name.')'),
          mswRowCount('faqassign LEFT JOIN `'.DB_PREFIX.'faq` ON `'.DB_PREFIX.'faq`.`id` = `'.DB_PREFIX.'faqassign`.`question` 
	                   WHERE `itemID` = \''.$SUBS->id.'\' AND `desc` = \'category\' AND `'.DB_PREFIX.'faq`.`enFaq` = \'yes\'')
         ),
         file_get_contents(PATH.'content/'.MS_TEMPLATE_SET.'/html/faq-sub-menu-link.htm')
        );
      }		
	}
  }
  return trim($data);
}

}

?>