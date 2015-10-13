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

class faqCentre {

public $settings;

public $internal = array(
 'chmod'       => 0777,
 'chmod-after' => 0644
);

// Rebuild attachment order sequence..
public function rebuildAttSequence() {
  $seq = 0;
  $q   = mysql_query("SELECT `id` FROM `".DB_PREFIX."faqattach` ORDER BY IF(`orderBy`>0,`orderBy`,9999)");
  while ($AT = mysql_fetch_object($q)) {
    $n = (++$seq);
	mysql_query("UPDATE `".DB_PREFIX."faqattach` SET
	`ts`       = UNIX_TIMESTAMP(UTC_TIMESTAMP),
	`orderBy`  = '{$n}'
	WHERE `id` = '{$AT->id}'
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Order sequence for attachments..
public function orderAttSequence() {
  foreach ($_POST['order'] AS $k => $v) {
    mysql_query("UPDATE `".DB_PREFIX."faqattach` SET
	`ts`       = UNIX_TIMESTAMP(UTC_TIMESTAMP),
	`orderBy`  = '{$v}'
    WHERE `id` = '{$k}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Enable/disable attachment..
public function enableDisableAtt() {
  $_GET['id'] = (int)$_GET['id'];
  mysql_query("UPDATE `".DB_PREFIX."faqattach` SET
  `ts`       = UNIX_TIMESTAMP(UTC_TIMESTAMP),
  `enAtt`    = '".($_GET['changeState']=='icon-flag' ? 'no' : 'yes')."'
  WHERE `id` = '{$_GET['id']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

// Delete attachment..
public function deleteAttachments() {
  if (!empty($_POST['del'])) {
    // Remove attachment files..
    $q = mysql_query("SELECT `path` FROM `".DB_PREFIX."faqattach`
          WHERE `id` IN(".implode(',',$_POST['del']).")
		  AND `path` != ''
          ORDER BY `id`
		  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	while ($AT = mysql_fetch_object($q)) {
	  if (file_exists($this->settings->attachpathfaq.'/'.$AT->path)) {
        @unlink($this->settings->attachpathfaq.'/'.$AT->path);
      }
	}
	// Delete data..
	mysql_query("DELETE FROM `".DB_PREFIX."faqattach`
    WHERE `id` IN(".implode(',',$_POST['del']).")
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
	if (mswRowCount('faqattach')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faqattach`");
    }
  }
  // Rebuild sequence..
  faqCentre::rebuildAttSequence();
  return $rows;
}

// Update attachment..
public function updateAttachment() {
  $_GET['edit'] = (int)$_GET['edit'];
  $display      = $_POST['name'][0];
  $remote       = $_POST['remote'][0];
  $f_name       = $_FILES['file']['name'][0];
  $f_temp       = $_FILES['file']['tmp_name'][0];
  $f_mime       = $_FILES['file']['type'][0];
  $f_size       = ($f_name && $f_temp ? $_FILES['file']['size'][0] : $_POST['osize']);
  $path         = $_POST['opath'];
  $ext          = substr(strrchr(strtolower($f_name),'.'),1);
  // Update file..
  if ($remote=='' && $f_size>0 && is_uploaded_file($f_temp)) {
    // Delete original..
    if (file_exists($this->settings->attachpathfaq.'/'.$_POST['opath'])) {
      @unlink($this->settings->attachpathfaq.'/'.$_POST['opath']);
    }
    // Does file exist?
    if (file_exists($this->settings->attachpathfaq.'/'.$f_name)) {
      // Are we renaming attachments..
	  if ($this->settings->renamefaq=='yes') {
	    $path = $_GET['edit'].'-'.time().'.'.$ext;
	  } else {
        $path = $_GET['edit'].'_'.mswCleanFile($f_name);
	  }
      move_uploaded_file($f_temp,$this->settings->attachpathfaq.'/'.$path);
	  // Required by some servers to make image viewable and accessible via FTP..
      @chmod($this->settings->attachpathfaq.'/'.$path,$this->internal['chmod-after']);
    } else {
	  // Are we renaming attachments..
	  if ($this->settings->renamefaq=='yes') {
	    $path = $_GET['edit'].'.'.$ext;
	  } else {
        $path = mswCleanFile($f_name);
	  }
      move_uploaded_file($f_temp,$this->settings->attachpathfaq.'/'.$path);
	  // Required by some servers to make image viewable and accessible via FTP..
      @chmod($this->settings->attachpathfaq.'/'.$path,$this->internal['chmod-after']);
    }
    // Remove temp file if it still exists..
    if (file_exists($f_temp)) {
      @unlink($f_temp);
    }
  }
  // Try and get remote filesize..
  if ($remote) {
    $f_size = faqCentre::remoteSize($remote);
  }
  // Add to database..
  mysql_query("UPDATE `".DB_PREFIX."faqattach` SET
  `ts`       = UNIX_TIMESTAMP(UTC_TIMESTAMP),
  `name`     = '".mswSafeImportString($display)."',
  `remote`   = '".mswSafeImportString($remote)."',
  `path`     = '".mswSafeImportString($path)."',
  `size`     = '{$f_size}',
  `mimeType` = '{$f_mime}'
  WHERE `id` = '{$_GET['edit']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

// Remote file size
public function remoteSize($file) {
  return (@ini_get('allow_url_fopen') ? @filesize(@file_get_contents($file)) : '0');
}

// Add attachments..
public function addAttachments() {
  if (!is_dir($this->settings->attachpathfaq) || !is_writeable($this->settings->attachpathfaq)) {
    die('FAQ attachments folder (<b>'.$this->settings->attachpathfaq.'</b>) doesn`t exist or is not writeable. Please check this folder exists and has write permissions.');
  }
  $count = 0;
  for ($i=0; $i<count($_FILES['file']['tmp_name']); $i++) {
    $display = $_POST['name'][$i];
    $remote  = $_POST['remote'][$i];
    $f_name  = $_FILES['file']['name'][$i];
    $f_temp  = $_FILES['file']['tmp_name'][$i];
	$f_mime  = $_FILES['file']['type'][$i];
    $f_size  = ($f_name && $f_temp ? $_FILES['file']['size'][$i] : ($remote ? faqCentre::remoteSize($remote) : '0'));
	$ext     = substr(strrchr(strtolower($f_name),'.'),1);
    $new     = '';
    // Add to database..
    mysql_query("INSERT INTO `".DB_PREFIX."faqattach` (
    `ts`,
    `name`,
    `remote`,
    `path`,
    `size`,
	`mimeType`
    ) VALUES (
    UNIX_TIMESTAMP(UTC_TIMESTAMP),
    '".mswSafeImportString($display)."',
    '".mswSafeImportString($remote)."',
    '".mswSafeImportString($f_name)."',
    '{$f_size}',
	'{$f_mime}'
    )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $ID = mysql_insert_id();
    // Now upload file if applicable..
    if ($ID>0) {
      if ($remote=='' && $f_size>0 && is_uploaded_file($f_temp)) {
        // Does file exist?
        if (file_exists($this->settings->attachpathfaq.'/'.$f_name)) {
          // Are we renaming attachments..
	      if ($this->settings->renamefaq=='yes') {
	        $new = $ID.'-'.time().'.'.$ext;
	      } else {
            $new = $ID.'_'.mswCleanFile($f_name);
	      }
		  move_uploaded_file($f_temp,$this->settings->attachpathfaq.'/'.$new);
		  // Required by some servers to make image viewable and accessible via FTP..
          @chmod($this->settings->attachpathfaq.'/'.$new,$this->internal['chmod-after']);
        } else {
		  // Are we renaming attachments..
	      if ($this->settings->renamefaq=='yes') {
	        $new = $ID.'.'.$ext;
	      } else {
            $new = mswCleanFile($f_name);
	      }
          move_uploaded_file($f_temp,$this->settings->attachpathfaq.'/'.$new);
		  // Required by some servers to make image viewable and accessible via FTP..
          @chmod($this->settings->attachpathfaq.'/'.$new,$this->internal['chmod-after']);
        }
      }
      // Was file renamed?
      mysql_query("UPDATE `".DB_PREFIX."faqattach` SET `path` = '{$new}' WHERE `id` = '{$ID}'");
      ++$count;
    }
    // Remove temp file if it still exists..
    if (file_exists($f_temp)) {
      @unlink($f_temp);
    }
  }
  // Rebuild sequence..
  faqCentre::rebuildAttSequence();
  return $count;
}

// Enable/disable cats..
public function enableDisableCats() {
  $_GET['id'] = (int)$_GET['id'];
  mysql_query("UPDATE `".DB_PREFIX."categories` SET
  `enCat`    = '".($_GET['changeState']=='icon-flag' ? 'no' : 'yes')."'
  WHERE `id` = '{$_GET['id']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

// Re-order categories..
public function orderCatSequence() {
  // Parents..
  foreach ($_POST['order'] AS $k => $v) {
    mysql_query("UPDATE `".DB_PREFIX."categories` SET
	`orderBy`  = '{$v}'
    WHERE `id` = '{$k}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
  // Children..
  if (!empty($_POST['orderSub'])) {
    foreach ($_POST['orderSub'] AS $k => $v) {
      mysql_query("UPDATE `".DB_PREFIX."categories` SET
	  `orderBy`  = '{$v}'
      WHERE `id` = '{$k}'
      ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  }
}

// Rebuild category sequence..
public function rebuildCatSequence() {
  $seq = 0;
  $q   = mysql_query("SELECT `id` FROM `".DB_PREFIX."categories` WHERE `subcat` = '0' ORDER BY IF(`orderBy`>0,`orderBy`,9999)");
  while ($CT = mysql_fetch_object($q)) {
    $n = (++$seq);
	mysql_query("UPDATE `".DB_PREFIX."categories` SET
	`orderBy`  = '{$n}'
	WHERE `id` = '{$CT->id}'
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	// Subs..
	$seqs = 0;
	$q2   = mysql_query("SELECT `id` FROM `".DB_PREFIX."categories` WHERE `subcat` = '{$CT->id}' ORDER BY IF(`orderBy`>0,`orderBy`,9999)");
    while ($SB = mysql_fetch_object($q2)) {
	  $ns = (++$seqs);
	  mysql_query("UPDATE `".DB_PREFIX."categories` SET
	  `orderBy`  = '{$ns}'
	  WHERE `id` = '{$SB->id}'
	  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	}
  }
}

// Add category..
public function addCategory() {
  $_POST['subcat'] = (int)$_POST['subcat'];
  mysql_query("INSERT INTO `".DB_PREFIX."categories` (
  `name`,
  `summary`,
  `enCat`,
  `subcat`
  ) VALUES (
  '".mswSafeImportString($_POST['name'])."',
  '".mswSafeImportString($_POST['summary'])."',
  '".(isset($_POST['enCat']) ? 'yes' : 'no')."',
  '{$_POST['subcat']}'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $last = mysql_insert_id();
  // Rebuild order sequence..
  faqCentre::rebuildCatSequence();
}

// Update category..
public function updateCategory() {
  $_GET['edit']    = (int)$_GET['edit'];
  $_POST['subcat'] = (int)$_POST['subcat'];
  mysql_query("UPDATE `".DB_PREFIX."categories` SET
  `name`      = '".mswSafeImportString($_POST['name'])."',
  `summary`   = '".mswSafeImportString($_POST['summary'])."',
  `enCat`     = '".(isset($_POST['enCat']) && in_array($_POST['enCat'],array('yes','no')) ? $_POST['enCat'] : 'no')."',
  `subcat`    = '{$_POST['subcat']}'
  WHERE `id`  = '{$_GET['edit']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

// Delete categories..
public function deleteCategories() {
  $que = array();
  if (!empty($_POST['del'])) {
    // Clear cats..
    mysql_query("DELETE FROM `".DB_PREFIX."categories`
    WHERE `id` IN(".implode(',',$_POST['del']).")
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
    // Clear assigned data..
    mysql_query("DELETE FROM `".DB_PREFIX."faqassign`
    WHERE `itemID` IN(".implode(',',$_POST['del']).")
	AND `desc`      = 'category'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	// Table cleanup..
	if (mswRowCount('categories')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."categories`");
	  @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faq`");
	  @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faqassign`");
    } else {
	  if (mswRowCount('faq')==0) {
        @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faq`");
		@mysql_query("TRUNCATE TABLE `".DB_PREFIX."faqassign`");
      } else {
	    if (mswRowCount('faqassign')==0) {
          @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faqassign`");
        }
	  }
	}
	// Rebuild sequence..
    faqCentre::rebuildCatSequence();
    return $rows;
  }
}

// Enable/disable questions..
public function enableDisableQuestions() {
  $_GET['id'] = (int)$_GET['id'];
  mysql_query("UPDATE `".DB_PREFIX."faq` SET
  `enFaq`    = '".($_GET['changeState']=='icon-flag' ? 'no' : 'yes')."'
  WHERE `id` = '{$_GET['id']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
}

// Add question..
public function addQuestion() {
  mysql_query("INSERT INTO `".DB_PREFIX."faq` (
  `ts`,
  `question`,
  `answer`,
  `enFaq`
  ) VALUES (
  UNIX_TIMESTAMP(UTC_TIMESTAMP),
  '".mswSafeImportString($_POST['question'])."',
  '".mswSafeImportString($_POST['answer'])."',
  '".(isset($_POST['enFaq']) ? 'yes' : 'no')."'
  )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  $ID = mysql_insert_id();
  // Assign attachments..
  if (!empty($_POST['att']) && $ID>0) {
    foreach ($_POST['att'] AS $aID) {
      mysql_query("INSERT INTO `".DB_PREFIX."faqassign` (
      `question`,`itemID`,`desc`
      ) VALUES (
      '{$ID}','{$aID}','attachment'
      )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  }
  // Assign categories..
  $assign = (empty($_POST['cat']) ? $_POST['catall'] : $_POST['cat']);
  if (!empty($assign) && $ID>0) {
    foreach ($assign AS $aID) {
      mysql_query("INSERT INTO `".DB_PREFIX."faqassign` (
      `question`,`itemID`,`desc`
      ) VALUES (
      '{$ID}','{$aID}','category'
      )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  }
  // Rebuild sequence..
  faqCentre::rebuildQueSequence();
}

// Update question..
public function updateQuestion() {
  $_GET['edit'] = (int)$_GET['edit'];
  mysql_query("UPDATE `".DB_PREFIX."faq` SET
  `ts`        = UNIX_TIMESTAMP(UTC_TIMESTAMP),
  `question`  = '".mswSafeImportString($_POST['question'])."',
  `answer`    = '".mswSafeImportString($_POST['answer'])."',
  `enFaq`     = '".(isset($_POST['enFaq']) ? 'yes' : 'no')."'
  WHERE `id`  = '{$_GET['edit']}'
  ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  // Update attachments..
  if (!empty($_POST['att'])) {
    mysql_query("DELETE FROM `".DB_PREFIX."faqassign` WHERE `question` = '{$_GET['edit']}' AND `desc` = 'attachment'");
    if (mswRowCount('faqassign')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faqassign`");
    }
    foreach ($_POST['att'] AS $aID) {
      mysql_query("INSERT INTO `".DB_PREFIX."faqassign` (
      `question`,`itemID`,`desc`
      ) VALUES (
      '{$_GET['edit']}','{$aID}','attachment'
      )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  }
  // Update categories..
  $assign = (empty($_POST['cat']) ? $_POST['catall'] : $_POST['cat']);
  if (!empty($assign)) {
    mysql_query("DELETE FROM `".DB_PREFIX."faqassign` WHERE `question` = '{$_GET['edit']}' AND `desc` = 'category'");
    if (mswRowCount('faqassign')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faqassign`");
    }
	foreach ($assign AS $aID) {
      mysql_query("INSERT INTO `".DB_PREFIX."faqassign` (
      `question`,`itemID`,`desc`
      ) VALUES (
      '{$_GET['edit']}','{$aID}','category'
      )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    }
  }
}

// Delete question..
public function deleteQuestions() {
  if (!empty($_POST['del'])) {
    mysql_query("DELETE FROM `".DB_PREFIX."faq`
    WHERE `id` IN(".implode(',',$_POST['del']).")
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    $rows = mysql_affected_rows();
    mysql_query("DELETE FROM `".DB_PREFIX."faqassign`
    WHERE `question` IN(".implode(',',$_POST['del']).")
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
    if (mswRowCount('faq')==0) {
      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faq`");
	  @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faqassign`");
    } else {
      if (mswRowCount('faqassign')==0) {
        @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faqassign`");
      }
	}
	// Rebuild sequence..
	faqCentre::rebuildQueSequence();
    return $rows;
  }
}

// Rebuild question order sequence..
public function rebuildQueSequence() {
  $seq = 0;
  $q   = mysql_query("SELECT `id` FROM `".DB_PREFIX."faq` ORDER BY IF(`orderBy`>0,`orderBy`,9999)");
  while ($RB = mysql_fetch_object($q)) {
    $n = (++$seq);
	mysql_query("UPDATE `".DB_PREFIX."faq` SET
	`orderBy`  = '{$n}'
	WHERE `id` = '{$RB->id}'
	") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Order sequence..
public function orderQueSequence() {
  foreach ($_POST['order'] AS $k => $v) {
    mysql_query("UPDATE `".DB_PREFIX."faq` SET
	`ts`       = UNIX_TIMESTAMP(UTC_TIMESTAMP),
    `orderBy`  = '{$v}'
    WHERE `id` = '{$k}'
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Reset counts..
public function resetCounts() {
  if (!empty($_POST['del'])) {
    mysql_query("UPDATE `".DB_PREFIX."faq` SET
    `ts`          = UNIX_TIMESTAMP(UTC_TIMESTAMP),
    `kviews`      = '0',
    `kuseful`     = '0',
    `knotuseful`  = '0'
    WHERE `id`   IN(".implode(',',$_POST['del']).")
    ") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
  }
}

// Batch import..
public function batchImportQuestions($lines,$del,$enc) {
  $count = 0;
  // Clear current questions..
  if (isset($_POST['clear'])) {
    $que  = array();
	$chop = (empty($_POST['cat']) ? $_POST['catall'] : $_POST['cat']);
	if (!empty($chop)) {
	  $q    = mysql_query("SELECT `question` FROM `".DB_PREFIX."faqassign`
	          WHERE `itemID` IN(".implode(',',$chop).")
			  AND `desc`      = 'category'
			  GROUP BY `question`
			  ORDER BY `itemID`
			  ");
      while ($QUE = mysql_fetch_object($q)) {
	    $que[] = $QUE->question;
      }
	  if (!empty($que)) {
        mysql_query("DELETE FROM `".DB_PREFIX."faq` WHERE `id` IN(".implode(',',$que).")")
		or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        if (mswRowCount('faq')==0) {
          @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faq`");
	      @mysql_query("TRUNCATE TABLE `".DB_PREFIX."faqassign`");
        }
	  }
	}
  }
  // Upload CSV file..
  if (is_uploaded_file($_FILES['file']['tmp_name'])) {
    // If uploaded file exists, read CSV data...
    $handle = fopen($_FILES['file']['tmp_name'],'r');
	if ($handle) {
      while (($CSV = fgetcsv($handle,$lines,$del,$enc))!==false) {
        // Clean array..
        $CSV  = array_map('trim',$CSV);
        mysql_query("INSERT INTO `".DB_PREFIX."faq` (
        `ts`,
        `question`,
        `answer`
        ) VALUES (
        UNIX_TIMESTAMP(UTC_TIMESTAMP),
        '".mswSafeImportString($CSV[0])."',
        '".mswSafeImportString($CSV[1])."'
        )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
	    $ID = mysql_insert_id();
	    // Assign categories..
	    $assign = (empty($_POST['cat']) ? $_POST['catall'] : $_POST['cat']);
	    if (!empty($assign) && $ID>0) {
	      foreach ($assign AS $aID) {
            mysql_query("INSERT INTO `".DB_PREFIX."faqassign` (
            `question`,`itemID`,`desc`
            ) VALUES (
            '{$ID}','{$aID}','category'
            )") or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
          }
	    }
        ++$count;
      }
      fclose($handle);
	}
  }
  // Clear temp file..
  if (file_exists($_FILES['file']['tmp_name'])) {
    @unlink($_FILES['file']['tmp_name']);
  }
  // Rebuild sequence..
  faqCentre::rebuildQueSequence();
  return $count;
}

}

?>