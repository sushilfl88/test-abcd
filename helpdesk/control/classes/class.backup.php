<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk
  
  Based on original code by:
   Daniele Vigan - Creative Factory
   daniele.vigano@creativefactory.it

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.backup.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class dbBackup {

public $database   = null;
public $compress   = false;
public $hexValue   = false;
public $filename   = null;
public $file       = null;
public $isWritten  = false;
public $settings;

public function dbBackup($filepath,$compress=false) {
  $this->compress  = $compress;
  if (!dbBackup::setOutputFile($filepath)) {
    return false;
  }
  return dbBackup::setDatabase(DB_NAME);
}

public function setDatabase($db) {
  $this->database = $db;
  if (!mysql_select_db($this->database)) {
    return false;
  }
  return true;
}

public function getDatabase() {
  return $this->database;
}

public function setCompress($compress) {
  if ($this->isWritten) {
    return false;
  }
  $this->compress = $compress;
  dbBackup::openFile($this->filename);
  return true;
}

public function getCompress() {
  return $this->compress;
}

public function setOutputFile($filepath){
  if ($this->isWritten) {
    return false;
  }
  $this->filename = $filepath;
  $this->file     = dbBackup::openFile($this->filename);
  return $this->file;
}

public function getOutputFile() {
  return $this->filename;
}

public function getTableStructure($table) {
  if (!dbBackup::setDatabase($this->database)) {
    return false;
  }
  $structure  = '--'.mswDefineNewline();
  $structure .= '-- Table structure for table `'.$table.'` '.mswDefineNewline();
  $structure .= '--'.mswDefineNewline().mswDefineNewline();
  $structure .= 'DROP TABLE IF EXISTS `'.$table.'`;'.mswDefineNewline();
  $structure .= 'CREATE TABLE `'.$table.'` ('.mswDefineNewline();
  $records    = mysql_query('SHOW FIELDS FROM `'.$table.'`');
  if (mysql_num_rows($records)==0) {
    return false;
  }
  while ($record = mysql_fetch_assoc($records)) {
    $structure .= '`'.$record['Field'].'` '.$record['Type'];
	if (!empty($record['Default'])) {
	  $structure .= ' DEFAULT \''.$record['Default'].'\'';
    }
	if (strcmp($record['Null'],'YES')!=0) {
	  $structure .= ' NOT NULL';
    }
	if (!empty($record['Extra'])) {
	  $structure .= ' '.$record['Extra'];
	}
	$structure .= ','.mswDefineNewline();
  }
  $structure  = substr_replace(trim($structure),'',-1);
  $structure .= dbBackup::getSqlKeysTable($table);
  $structure .= mswDefineNewline().")";
  $records    = mysql_query("SHOW TABLE STATUS LIKE '".$table."'");
  if ($record = mysql_fetch_assoc($records)) {
    if (!empty($record['Engine'])) {
	  $structure .= ' ENGINE='.$record['Engine'];
    }
	if (!empty($record['Auto_increment'])) {
	  $structure .= ' AUTO_INCREMENT='.$record['Auto_increment'];
    }
  }
  $structure .= ";".mswDefineNewline().mswDefineNewline()."-- --------------------------------------------------------".mswDefineNewline().mswDefineNewline();
  dbBackup::saveToFile($this->file,$structure);
}

public function mswGetTableData($table,$hexValue = true) {
  if (!dbBackup::setDatabase($this->database)) {
    return false;
  }
  $data        = '--'.mswDefineNewline();
  $data       .= '-- Dumping data for table `'.$table.'`'.mswDefineNewline();
  $data       .= '--'.mswDefineNewline().mswDefineNewline();
  $records     = mysql_query('SHOW FIELDS FROM `'.$table.'`');
  $num_fields  = mysql_num_rows($records);
  if ($num_fields==0) {
    return false;
  }
  $selectStatement = "SELECT ";
  $insertStatement = "INSERT INTO `$table` (";
  $hexField        = array();
  for ($x=0; $x<$num_fields; $x++) {
    $record = mysql_fetch_assoc($records);
	if (($hexValue) && (dbBackup::isTextValue($record['Type']))) {
	  $selectStatement .= 'HEX(`'.$record['Field'].'`)';
	  $hexField[$x]    = true;
	} else {
	  $selectStatement .= '`'.$record['Field'].'`';
	  $insertStatement .= '`'.$record['Field'].'`';
	  $insertStatement .= ", ";
	  $selectStatement .= ", ";
	}
  }
  $insertStatement  = substr($insertStatement,0,-2).') VALUES';
  $selectStatement  = substr($selectStatement,0,-2).' FROM `'.$table.'`';
  $records          = mysql_query($selectStatement);
  $num_rows         = mysql_num_rows($records);
  $num_fields       = mysql_num_fields($records);
  if ($num_rows>0) {
    $data .= $insertStatement;
	for ($i = 0; $i < $num_rows; $i++) {
	  $record = mysql_fetch_assoc($records);
	  $data  .= ' (';
	  for ($j=0; $j<$num_fields; $j++) {
	    $field_name = mysql_field_name($records, $j);
		if (isset($hexField[$j]) && $hexField[$j] && (strlen($record[$field_name])>0)) {
		  $data .= "0x".$record[$field_name];
		} else {
		  $data .= "'".str_replace('\"','"',mysql_real_escape_string($record[$field_name]))."'";
        }
		$data .= ',';
	  }
	  $data  = substr($data,0,-1).")";
	  $data .= ($i<($num_rows-1)) ? ',' : ';';
	  $data .= mswDefineNewline();
	  if (strlen($data)>1048576) {
	    dbBackup::saveToFile($this->file,$data);
		$data = '';
	  }
	}
	$data .= mswDefineNewline()."-- --------------------------------------------------------".mswDefineNewline().mswDefineNewline();
	dbBackup::saveToFile($this->file,$data);
  }
}

public function getDatabaseStructure() {
  $structure    = '';
  $records      = mysql_query('SHOW TABLES');
  $scriptSchema = mswDBSchemaArray();
  if (mysql_num_rows($records)==0) {
    return false;
  }
  while ($record = mysql_fetch_row($records)) {
    if (in_array($record[0],$scriptSchema)) {
	  $structure .= dbBackup::getTableStructure($record[0]);
	}
  }
  return true;
}

public function getDatabaseData($hexValue = true){
  $scriptSchema = mswDBSchemaArray();
  $records      = mysql_query('SHOW TABLES');
  if (mysql_num_rows($records)==0) {
    return false;
  }
  while ($record = mysql_fetch_row($records)) {
    if (in_array($record[0],$scriptSchema)) {
	  dbBackup::mswGetTableData($record[0],$hexValue);
	}
  }
}

public function getMySQLVersion() {
  $q  = @mysql_query("SELECT VERSION() AS v");
  $V  = @mysql_fetch_object($q);
  return (isset($V->v) ? $V->v : 'Unknown');
}

public function doDump() {
  global $SETTINGS,$MSDT;
  $header  = '#--------------------------------------------------------'.mswDefineNewline();
  $header .= '# MYSQL DATABASE SCHEMATIC'.mswDefineNewline();
  $header .= '# HelpDesk: '.mswCleanData($this->settings->website).mswDefineNewline();
  $header .= '# '.SCRIPT_NAME.' Version: '.SCRIPT_VERSION.mswDefineNewline();
  $header .= '# Date Created: '.$MSDT->mswDateTimeDisplay(0,$SETTINGS->dateformat).' @ '.$MSDT->mswDateTimeDisplay(0,$SETTINGS->timeformat).mswDefineNewline();
  $header .= '# MySQL Version: '.dbBackup::getMySQLVersion().mswDefineNewline();
  $header .= '#--------------------------------------------------------'.mswDefineNewline().mswDefineNewline();
  dbBackup::saveToFile($this->file,$header.'SET FOREIGN_KEY_CHECKS = 0;'.mswDefineNewline().mswDefineNewline());
  dbBackup::getDatabaseStructure();
  dbBackup::getDatabaseData($this->hexValue);
  dbBackup::saveToFile($this->file,'SET FOREIGN_KEY_CHECKS = 1;'.mswDefineNewline().mswDefineNewline());
  dbBackup::closeFile($this->file);
  return true;
}
	
public function writeDump($filename) {
  if (!dbBackup::setOutputFile($filename)) {
    return false;
  }
  dbBackup::doDump();
  dbBackup::closeFile($this->file);
  return true;
}

public function getSqlKeysTable ($table) {
  $primary         = '';
  $sqlKeyStatement = '';
  $unique          = array();
  $index           = array();
  $fulltext        = array();
  $results         = mysql_query("SHOW KEYS FROM `{$table}`");
  if (mysql_num_rows($results)==0) {
    return false;
  }
  while($row = mysql_fetch_object($results)) {
    if (($row->Key_name == 'PRIMARY') AND ($row->Index_type == 'BTREE')) {
	  if ($primary=='') {
	    $primary  = "  PRIMARY KEY  (`{$row->Column_name}`";
	  } else {
	    $primary .= ", `{$row->Column_name}`";
      }
	}
	if (($row->Key_name != 'PRIMARY') AND ($row->Non_unique == '0') AND ($row->Index_type == 'BTREE')) {
	  if (!isset($unique[$row->Key_name])) {
	    $unique[$row->Key_name]  = "  UNIQUE KEY `{$row->Key_name}` (`{$row->Column_name}`";
	  } else {
	    $unique[$row->Key_name] .= ", `{$row->Column_name}`";
	  }
    }
	if (($row->Key_name != 'PRIMARY') AND ($row->Non_unique == '1') AND ($row->Index_type == 'BTREE')) {
	  if (!isset($index[$row->Key_name])) {
	    $index[$row->Key_name]  = "  KEY `{$row->Key_name}` (`{$row->Column_name}`";
	  } else {
	    $index[$row->Key_name] .= ", `{$row->Column_name}`";
      }
	}
	if (($row->Key_name != 'PRIMARY') AND ($row->Non_unique == '1') AND ($row->Index_type == 'FULLTEXT')) {
	  if (!isset($fulltext[$row->Key_name])) {
	    $fulltext[$row->Key_name]  = "  FULLTEXT `{$row->Key_name}` (`{$row->Column_name}`";
	  } else {
	    $fulltext[$row->Key_name] .= ", `{$row->Column_name}`";
      }
    }
  }
  if ($primary!='') {
    $sqlKeyStatement .= ",".mswDefineNewline();
	$primary         .= ")";
	$sqlKeyStatement .= $primary;
  }
  if (is_array($unique)) {
    foreach ($unique AS $keyName => $keyDef) {
	  $sqlKeyStatement .= ",".mswDefineNewline();
	  $keyDef          .= ")";
	  $sqlKeyStatement .= $keyDef;
	}
  }
  if (is_array($index)) {
    foreach ($index AS $keyName => $keyDef) {
	  $sqlKeyStatement .= ",".mswDefineNewline();
	  $keyDef          .= ")";
	  $sqlKeyStatement .= $keyDef;
	}
  }
  if (is_array($fulltext)) {
    foreach ($fulltext AS $keyName => $keyDef) {
	  $sqlKeyStatement .= ",".mswDefineNewline();
	  $keyDef          .= ")";
	  $sqlKeyStatement .= $keyDef;
	}
  }
  return $sqlKeyStatement;
}

public function isTextValue($field_type) {
  switch ($field_type) {
    case 'tinytext':
	case 'text':
	case 'mediumtext':
	case 'longtext':
	case 'binary':
	case 'varbinary':
	case 'tinyblob':
	case 'blob':
	case 'mediumblob':
	case 'longblob':
	return true;
	break;
	default:
	return false;
  }
}
	
public function openFile($filename) {
  $file = false;
  if ($this->compress) {
    $file = gzopen($filename,'w9');
  } else {
    $file = fopen($filename,'ab');
  }
  return $file;
}

public function saveToFile($file,$data) {
  if ($this->compress) {
    if ($file) {
	  gzwrite($file,$data);
    }
  } else {
    if ($file) {
	  fwrite($file,$data);
    }
  }
  $this->isWritten = true;
}

public function closeFile($file) {
  if ($this->compress) {
    if ($file) {
	  gzclose($file);
    }
  } else {
    if ($file) {
	  fclose($file);
    }
  }
}

}

?>