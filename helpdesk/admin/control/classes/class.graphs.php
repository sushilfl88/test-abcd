<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.graphs.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class graphs {

public $settings;
public $datetime;
public $team;
public $range          = array();
protected $date_format = '%c/%e/%Y'; // DO NOT change..

public function graph($area) {
  $lines = array(array(),array());
  switch ($area) {
    case 'responses':
	// Selected range..
	$from     = $this->datetime->mswDatePickerFormat($this->range[0]);
    $to       = $this->datetime->mswDatePickerFormat($this->range[1]);
	$lines[0] = graphs::responses($from,$to);
	// Selected range - 1 Year earlier..
	$from     = date('Y-m-d',strtotime('-1 year',strtotime($from)));
	$to       = date('Y-m-d',strtotime('-1 year',strtotime($to)));
	$lines[1] = graphs::responses($from,$to);
	break;
  }
  return array($lines[0],$lines[1]);
}

// Data for responses..
private function responses($from,$to) {
  $data  = array();
  $id    = (int)$_GET['id'];
  $q     = mysql_query("SELECT DATE_FORMAT(DATE(FROM_UNIXTIME(".DB_PREFIX."replies.`ts`)),'{$this->date_format}') AS `dt`,count(*) AS `c` 
           FROM `".DB_PREFIX."replies`
           WHERE DATE(FROM_UNIXTIME(`".DB_PREFIX."replies`.`ts`)) BETWEEN '{$from}' AND '{$to}'
		   AND `replyType` = 'admin'
		   AND `replyUser` = '{$id}'
           GROUP BY DATE(FROM_UNIXTIME(`".DB_PREFIX."replies`.`ts`))
           ");  
  while ($TD = mysql_fetch_object($q)) {
    $data[] = "['".$TD->dt."',".$TD->c."]";
  }
  // JQPLOT Note
  // A bug apears to exist in jqplot that prevents correct tick date display for one data point..
  // We can fix this by adding a blank entry for the initial view cut off point..
  if (count($data)==1) {
    array_unshift($data,"['".date('n/j/Y',strtotime('-6 months',strtotime($to)))."',0]");
  }
  return $data;
}

// Data for homepage..
public function home($from,$to,$filter) {
  $t  = array();
  $d  = array();
  // Ticket data..
  $q  = mysql_query("SELECT DATE_FORMAT(DATE(FROM_UNIXTIME(".DB_PREFIX."tickets.`ts`)),'{$this->date_format}') AS `dt`,count(*) AS `c` 
        FROM `".DB_PREFIX."tickets`
        WHERE DATE(FROM_UNIXTIME(`".DB_PREFIX."tickets`.`ts`)) BETWEEN '".$this->datetime->mswDatePickerFormat($from)."' AND '".$this->datetime->mswDatePickerFormat($to)."'
		AND `isDisputed`  = 'no'
		AND `assignedto` != 'waiting'
	    AND `spamFlag`    = 'no'
		".mswSQLDepartmentFilter($filter)."
		GROUP BY DATE(FROM_UNIXTIME(`".DB_PREFIX."tickets`.`ts`))
        ");  
  while ($TD = mysql_fetch_object($q)) {
    $t[] = "['".$TD->dt."',".$TD->c."]";
  }
  // Dispute data..
  if ($this->settings->disputes=='yes') {
    $q2     = mysql_query("SELECT DATE_FORMAT(DATE(FROM_UNIXTIME(".DB_PREFIX."tickets.`ts`)),'{$this->date_format}') AS `dt`,count(*) AS `c` 
              FROM `".DB_PREFIX."tickets`
              WHERE DATE(FROM_UNIXTIME(`".DB_PREFIX."tickets`.`ts`)) BETWEEN '".$this->datetime->mswDatePickerFormat($from)."' AND '".$this->datetime->mswDatePickerFormat($to)."'
		      AND `isDisputed`  = 'yes'
		      AND `assignedto` != 'waiting'
	          AND `spamFlag`    = 'no'
		      ".mswSQLDepartmentFilter($filter)."
		      GROUP BY DATE(FROM_UNIXTIME(`".DB_PREFIX."tickets`.`ts`))
              ");  
    while ($TD2 = mysql_fetch_object($q2)) {
      $d[] = "['".$TD2->dt."',".$TD2->c."]";
    }
  }
  // JQPLOT Note
  // A bug apears to exist in jqplot that prevents correct tick date display for one data point..
  // We can fix this by adding a blank entry for the initial view cut off point..
  if (count($t)==1) {
    array_unshift($t,"['".date('n/j/Y',strtotime('-'.($this->team->defDays>0 ? $this->team->defDays : 45).' day'))."',0]");
  }
  return array($t,$d);
}

}

?>