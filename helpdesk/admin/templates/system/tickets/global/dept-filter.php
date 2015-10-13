        <?php
		$_GET['p'] = (isset($_GET['p']) ? $_GET['p'] : 'x');
        if (!defined('PARENT') || !in_array($_GET['p'],array('open','close','disputes','cdisputes','search','assign','acchistory','spam','submit'))) { exit; } 
        
		//===========================================
		// DEPARTMENT FILTERS..
		//===========================================
		
		$links   = array();
        $links[] = array('link' => '?p='.$_GET['p'].mswQueryParams(array('p','dept','next')),'name' => $msg_open2);
        $q_dept = mysql_query("SELECT * FROM `".DB_PREFIX."departments` ".mswSQLDepartmentFilter($mswDeptFilterAccess,'WHERE')." ORDER BY `name`") 
                  or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
        while ($DEPT = mysql_fetch_object($q_dept)) {
		  $links[] = array('link' => '?p='.$_GET['p'].'&amp;dept='.$DEPT->id.mswQueryParams(array('p','dept','next')),'name' => mswCleanData($DEPT->name));
        }
        
		//=========================================================
		// FOR ADMINISTRATOR, SHOW ALL ASSIGNED USERS IN FILTER
		//=========================================================
		
        if (!defined('HIDE_ASSIGN_FILTERS') && $MSTEAM->id=='1') {
		  $q_users  = mysql_query("SELECT * FROM `".DB_PREFIX."users` ORDER BY `name`") 
                      or die(mswMysqlErrMsg(mysql_errno(),mysql_error(),__LINE__,__FILE__));
          while ($U = mysql_fetch_object($q_users)) {
		    $links[] = array('link' => '?p='.$_GET['p'].'&amp;dept=u'.$U->id.mswQueryParams(array('p','dept','next')),'name' => $msg_open31.' '.mswSpecialChars($U->name));
          }
		}
		echo $MSBOOTSTRAP->button($msg_viewticket107,$links);
        ?>