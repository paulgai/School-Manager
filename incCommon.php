<?php

	#########################################################
	/*
	~~~~~~ LIST OF FUNCTIONS ~~~~~~
		get_table_groups() -- returns an associative array (table_group => tables_array)
		getTablePermissions($tn) -- returns an array of permissions allowed for logged member to given table (allowAccess, allowInsert, allowView, allowEdit, allowDelete) -- allowAccess is set to true if any access level is allowed
		get_sql_fields($tn) -- returns the SELECT part of the table view query
		get_sql_from($tn[, true, [, false]]) -- returns the FROM part of the table view query, with full joins (unless third paramaeter is set to true), optionally skipping permissions if true passed as 2nd param.
		get_joined_record($table, $id[, true]) -- returns assoc array of record values for given PK value of given table, with full joins, optionally skipping permissions if true passed as 3rd param.
		get_defaults($table) -- returns assoc array of table fields as array keys and default values (or empty), excluding automatic values as array values
		htmlUserBar() -- returns html code for displaying user login status to be used on top of pages.
		showNotifications($msg, $class) -- returns html code for displaying a notification. If no parameters provided, processes the GET request for possible notifications.
		parseMySQLDate(a, b) -- returns a if valid mysql date, or b if valid mysql date, or today if b is true, or empty if b is false.
		parseCode(code) -- calculates and returns special values to be inserted in automatic fields.
		addFilter(i, filterAnd, filterField, filterOperator, filterValue) -- enforce a filter over data
		clearFilters() -- clear all filters
		loadView($view, $data) -- passes $data to templates/{$view}.php and returns the output
		loadTable($table, $data) -- loads table template, passing $data to it
		br2nl($text) -- replaces all variations of HTML <br> tags with a new line character
		entitiesToUTF8($text) -- convert unicode entities (e.g. &#1234;) to actual UTF8 characters, requires multibyte string PHP extension
		func_get_args_byref() -- returns an array of arguments passed to a function, by reference
		permissions_sql($table, $level) -- returns an array containing the FROM and WHERE additions for applying permissions to an SQL query
		error_message($msg[, $back_url]) -- returns html code for a styled error message .. pass explicit false in second param to suppress back button
		toMySQLDate($formattedDate, $sep = datalist_date_separator, $ord = datalist_date_format)
		reIndex(&$arr) -- returns a copy of the given array, with keys replaced by 1-based numeric indices, and values replaced by original keys
		get_embed($provider, $url[, $width, $height, $retrieve]) -- returns embed code for a given url (supported providers: youtube, googlemap)
		check_record_permission($table, $id, $perm = 'view') -- returns true if current user has the specified permission $perm ('view', 'edit' or 'delete') for the given recors, false otherwise
		NavMenus($options) -- returns the HTML code for the top navigation menus. $options is not implemented currently.
		StyleSheet() -- returns the HTML code for included style sheet files to be placed in the <head> section.
		getUploadDir($dir) -- if dir is empty, returns upload dir configured in defaultLang.php, else returns $dir.
		PrepareUploadedFile($FieldName, $MaxSize, $FileTypes={image file types}, $NoRename=false, $dir="") -- validates and moves uploaded file for given $FieldName into the given $dir (or the default one if empty)
		get_home_links($homeLinks, $default_classes, $tgroup) -- process $homeLinks array and return custom links for homepage. Applies $default_classes to links if links have classes defined, and filters links by $tgroup (using '*' matches all table_group values)
		quick_search_html($search_term, $label, $separate_dv = true) -- returns HTML code for the quick search box.
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/

	#########################################################

	function get_table_groups($skip_authentication = false) {
		$tables = getTableList($skip_authentication);
		$all_groups = ['&#904;&#947;&#947;&#961;&#945;&#966;&#945;', '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;', '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;', '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;', 'None'];

		$groups = [];
		foreach($all_groups as $grp) {
			foreach($tables as $tn => $td) {
				if($td[3] && $td[3] == $grp) $groups[$grp][] = $tn;
				if(!$td[3]) $groups[0][] = $tn;
			}
		}

		return $groups;
	}

	#########################################################

	function getTablePermissions($tn) {
		static $table_permissions = [];
		if(isset($table_permissions[$tn])) return $table_permissions[$tn];

		$groupID = getLoggedGroupID();
		$memberID = makeSafe(getLoggedMemberID());
		$res_group = sql("SELECT `tableName`, `allowInsert`, `allowView`, `allowEdit`, `allowDelete` FROM `membership_grouppermissions` WHERE `groupID`='{$groupID}'", $eo);
		$res_user  = sql("SELECT `tableName`, `allowInsert`, `allowView`, `allowEdit`, `allowDelete` FROM `membership_userpermissions`  WHERE LCASE(`memberID`)='{$memberID}'", $eo);

		while($row = db_fetch_assoc($res_group)) {
			$table_permissions[$row['tableName']] = [
				1 => intval($row['allowInsert']),
				2 => intval($row['allowView']),
				3 => intval($row['allowEdit']),
				4 => intval($row['allowDelete']),
				'insert' => intval($row['allowInsert']),
				'view' => intval($row['allowView']),
				'edit' => intval($row['allowEdit']),
				'delete' => intval($row['allowDelete'])
			];
		}

		// user-specific permissions, if specified, overwrite his group permissions
		while($row = db_fetch_assoc($res_user)) {
			$table_permissions[$row['tableName']] = [
				1 => intval($row['allowInsert']),
				2 => intval($row['allowView']),
				3 => intval($row['allowEdit']),
				4 => intval($row['allowDelete']),
				'insert' => intval($row['allowInsert']),
				'view' => intval($row['allowView']),
				'edit' => intval($row['allowEdit']),
				'delete' => intval($row['allowDelete'])
			];
		}

		// if user has any type of access, set 'access' flag
		foreach($table_permissions as $t => $p) {
			$table_permissions[$t]['access'] = $table_permissions[$t][0] = false;

			if($p['insert'] || $p['view'] || $p['edit'] || $p['delete']) {
				$table_permissions[$t]['access'] = $table_permissions[$t][0] = true;
			}
		}

		return $table_permissions[$tn] ?? [];
	}

	#########################################################

	function get_sql_fields($table_name) {
		$sql_fields = [
			'Protocol' => "`Protocol`.`id` as 'id', `Protocol`.`serial_number` as 'serial_number', if(`Protocol`.`receipt_date`,date_format(`Protocol`.`receipt_date`,'%d-%m-%Y'),'') as 'receipt_date', `Protocol`.`doc_number` as 'doc_number', `Protocol`.`place` as 'place', `Protocol`.`authority_issuing` as 'authority_issuing', if(`Protocol`.`date_issuing`,date_format(`Protocol`.`date_issuing`,'%d-%m-%Y'),'') as 'date_issuing', `Protocol`.`summary_incoming` as 'summary_incoming', `Protocol`.`to_whom` as 'to_whom', `Protocol`.`authority_outcoming` as 'authority_outcoming', `Protocol`.`summary_outcoming` as 'summary_outcoming', if(`Protocol`.`outcoming_date`,date_format(`Protocol`.`outcoming_date`,'%d-%m-%Y'),'') as 'outcoming_date', if(`Protocol`.`processing_date`,date_format(`Protocol`.`processing_date`,'%d-%m-%Y'),'') as 'processing_date', IF(    CHAR_LENGTH(`Folders1`.`folder`), CONCAT_WS('',   `Folders1`.`folder`), '') as 'folder_id', IF(    CHAR_LENGTH(`Subfolders1`.`subfolder`), CONCAT_WS('',   `Subfolders1`.`subfolder`), '') as 'subfolder_id', `Protocol`.`comments` as 'comments'",
			'Incoming_Files' => "`Incoming_Files`.`id` as 'id', IF(    CHAR_LENGTH(`Protocol1`.`serial_number`) || CHAR_LENGTH(if(`Protocol1`.`receipt_date`,date_format(`Protocol1`.`receipt_date`,'%d-%m-%Y'),'')), CONCAT_WS('',   `Protocol1`.`serial_number`, '/', if(`Protocol1`.`receipt_date`,date_format(`Protocol1`.`receipt_date`,'%d-%m-%Y'),'')), '') as 'protocol_id', `Incoming_Files`.`title` as 'title', `Incoming_Files`.`file` as 'file'",
			'Outcoming_Files' => "`Outcoming_Files`.`id` as 'id', IF(    CHAR_LENGTH(`Protocol1`.`serial_number`) || CHAR_LENGTH(if(`Protocol1`.`outcoming_date`,date_format(`Protocol1`.`outcoming_date`,'%d-%m-%Y'),'')), CONCAT_WS('',   `Protocol1`.`serial_number`, '/', if(`Protocol1`.`outcoming_date`,date_format(`Protocol1`.`outcoming_date`,'%d-%m-%Y'),'')), '') as 'protocol_id', `Outcoming_Files`.`title` as 'title', IF(    CHAR_LENGTH(`Documents_templates1`.`title`), CONCAT_WS('',   `Documents_templates1`.`title`), '') as 'doc_template_id', `Outcoming_Files`.`hex_doc` as 'hex_doc', `Outcoming_Files`.`file` as 'file'",
			'Documents_templates' => "`Documents_templates`.`id` as 'id', `Documents_templates`.`title` as 'title', `Documents_templates`.`hex_doc` as 'hex_doc'",
			'TeachersName' => "`TeachersName`.`id` as 'id', `TeachersName`.`Name` as 'Name'",
			'Assignments' => "`Assignments`.`id` as 'id', IF(    CHAR_LENGTH(`Classes1`.`Class`), CONCAT_WS('',   `Classes1`.`Class`), '') as 'ClassID', IF(    CHAR_LENGTH(`Lessons1`.`NameTypeHours`), CONCAT_WS('',   `Lessons1`.`NameTypeHours`), '') as 'LessonID', `Assignments`.`LessonName` as 'LessonName', `Assignments`.`Lesson_A_Assignment` as 'Lesson_A_Assignment', `Assignments`.`Lesson_B_Assignment` as 'Lesson_B_Assignment', `Assignments`.`Lesson_C_Assignment` as 'Lesson_C_Assignment', `Assignments`.`LessonType` as 'LessonType', `Assignments`.`LessonHours` as 'LessonHours', IF(    CHAR_LENGTH(`Teachers1`.`Name_Sector`), CONCAT_WS('',   `Teachers1`.`Name_Sector`), '') as 'TeacherID', IF(    CHAR_LENGTH(`Teachers2`.`Name_Sector`), CONCAT_WS('',   `Teachers2`.`Name_Sector`), '') as 'TeacherID2', if(`Assignments`.`datetime`,date_format(`Assignments`.`datetime`,'%d-%m-%Y %H:%i'),'') as 'datetime'",
			'Teachers' => "`Teachers`.`id` as 'id', IF(    CHAR_LENGTH(`Sectors1`.`Sector`), CONCAT_WS('',   `Sectors1`.`Sector`), '') as 'SectorID', `Teachers`.`Name_Sector` as 'Name_Sector', `Teachers`.`Name` as 'Name', IF(    CHAR_LENGTH(`TeachersName1`.`Name`), CONCAT_WS('',   `TeachersName1`.`Name`), '') as 'Name2', `Teachers`.`Placement` as 'Placement', DATE_FORMAT(`Teachers`.`Assumption_Date`, '%d/%m/%Y') as 'Assumption_Date', `Teachers`.`Mandatory_Hours` as 'Mandatory_Hours', `Teachers`.`Main_Sector` as 'Main_Sector', `Teachers`.`Assigned_Hours_Theory` as 'Assigned_Hours_Theory', `Teachers`.`Assigned_Hours_Lab` as 'Assigned_Hours_Lab', `Teachers`.`Assigned_Hours` as 'Assigned_Hours', `Teachers`.`Diff` as 'Diff'",
			'Lessons' => "`Lessons`.`id` as 'id', `Lessons`.`Class` as 'Class', `Lessons`.`Name` as 'Name', `Lessons`.`Type` as 'Type', `Lessons`.`Hours` as 'Hours', `Lessons`.`NameTypeHours` as 'NameTypeHours', `Lessons`.`A_assignment` as 'A_assignment', `Lessons`.`B_assignment` as 'B_assignment', `Lessons`.`C_assignment` as 'C_assignment', `Lessons`.`bank` as 'bank'",
			'Classes' => "`Classes`.`id` as 'id', `Classes`.`Class` as 'Class', `Classes`.`Students_Number` as 'Students_Number', `Classes`.`Type` as 'Type'",
			'Sectors' => "`Sectors`.`id` as 'id', `Sectors`.`Sector` as 'Sector'",
			'Heads' => "`Heads`.`id` as 'id', IF(    CHAR_LENGTH(`TeachersName1`.`Name`), CONCAT_WS('',   `TeachersName1`.`Name`), '') as 'teacher_id', IF(    CHAR_LENGTH(`Classes1`.`Class`), CONCAT_WS('',   `Classes1`.`Class`), '') as 'class_id'",
			'Projectors_timetable' => "`Projectors_timetable`.`id` as 'id', IF(    CHAR_LENGTH(`TeachersName1`.`Name`), CONCAT_WS('',   `TeachersName1`.`Name`), '') as 'teacher_id', if(`Projectors_timetable`.`date`,date_format(`Projectors_timetable`.`date`,'%d-%m-%Y'),'') as 'date', IF(    CHAR_LENGTH(`Hours1`.`name`), CONCAT_WS('',   `Hours1`.`name`), '') as 'hour_id', IF(    CHAR_LENGTH(`Projectors1`.`name`), CONCAT_WS('',   `Projectors1`.`name`), '') as 'projector_id', `Projectors_timetable`.`star_time` as 'star_time', `Projectors_timetable`.`end_time` as 'end_time'",
			'Hours' => "`Hours`.`id` as 'id', `Hours`.`name` as 'name', `Hours`.`start_time` as 'start_time', `Hours`.`end_time` as 'end_time', `Hours`.`start_end_text` as 'start_end_text'",
			'Projectors' => "`Projectors`.`id` as 'id', `Projectors`.`img` as 'img', `Projectors`.`name` as 'name'",
			'Tests' => "`Tests`.`id` as 'id', IF(    CHAR_LENGTH(`Teachers1`.`Name`), CONCAT_WS('',   `Teachers1`.`Name`), '') as 'teacher_id', IF(    CHAR_LENGTH(`Assignments1`.`LessonName`), CONCAT_WS('',   `Assignments1`.`LessonName`), '') as 'assignments_id', if(`Tests`.`date`,date_format(`Tests`.`date`,'%d-%m-%Y'),'') as 'date', IF(    CHAR_LENGTH(`Hours1`.`name`), CONCAT_WS('',   `Hours1`.`name`), '') as 'hour_id', `Tests`.`file` as 'file'",
			'Folders' => "`Folders`.`id` as 'id', `Folders`.`folder` as 'folder'",
			'Subfolders' => "`Subfolders`.`id` as 'id', IF(    CHAR_LENGTH(`Folders1`.`folder`), CONCAT_WS('',   `Folders1`.`folder`), '') as 'folder_id', `Subfolders`.`subfolder` as 'subfolder'",
			'Examinations' => "`Examinations`.`id` as 'id'",
		];

		if(isset($sql_fields[$table_name])) return $sql_fields[$table_name];

		return false;
	}

	#########################################################

	function get_sql_from($table_name, $skip_permissions = false, $skip_joins = false, $lower_permissions = false) {
		$sql_from = [
			'Protocol' => "`Protocol` LEFT JOIN `Folders` as Folders1 ON `Folders1`.`id`=`Protocol`.`folder_id` LEFT JOIN `Subfolders` as Subfolders1 ON `Subfolders1`.`id`=`Protocol`.`subfolder_id` ",
			'Incoming_Files' => "`Incoming_Files` LEFT JOIN `Protocol` as Protocol1 ON `Protocol1`.`id`=`Incoming_Files`.`protocol_id` ",
			'Outcoming_Files' => "`Outcoming_Files` LEFT JOIN `Protocol` as Protocol1 ON `Protocol1`.`id`=`Outcoming_Files`.`protocol_id` LEFT JOIN `Documents_templates` as Documents_templates1 ON `Documents_templates1`.`id`=`Outcoming_Files`.`doc_template_id` ",
			'Documents_templates' => "`Documents_templates` ",
			'TeachersName' => "`TeachersName` ",
			'Assignments' => "`Assignments` LEFT JOIN `Classes` as Classes1 ON `Classes1`.`id`=`Assignments`.`ClassID` LEFT JOIN `Lessons` as Lessons1 ON `Lessons1`.`id`=`Assignments`.`LessonID` LEFT JOIN `Teachers` as Teachers1 ON `Teachers1`.`id`=`Assignments`.`TeacherID` LEFT JOIN `Teachers` as Teachers2 ON `Teachers2`.`id`=`Assignments`.`TeacherID2` ",
			'Teachers' => "`Teachers` LEFT JOIN `Sectors` as Sectors1 ON `Sectors1`.`id`=`Teachers`.`SectorID` LEFT JOIN `TeachersName` as TeachersName1 ON `TeachersName1`.`id`=`Teachers`.`Name2` ",
			'Lessons' => "`Lessons` ",
			'Classes' => "`Classes` ",
			'Sectors' => "`Sectors` ",
			'Heads' => "`Heads` LEFT JOIN `TeachersName` as TeachersName1 ON `TeachersName1`.`id`=`Heads`.`teacher_id` LEFT JOIN `Classes` as Classes1 ON `Classes1`.`id`=`Heads`.`class_id` ",
			'Projectors_timetable' => "`Projectors_timetable` LEFT JOIN `TeachersName` as TeachersName1 ON `TeachersName1`.`id`=`Projectors_timetable`.`teacher_id` LEFT JOIN `Hours` as Hours1 ON `Hours1`.`id`=`Projectors_timetable`.`hour_id` LEFT JOIN `Projectors` as Projectors1 ON `Projectors1`.`id`=`Projectors_timetable`.`projector_id` ",
			'Hours' => "`Hours` ",
			'Projectors' => "`Projectors` ",
			'Tests' => "`Tests` LEFT JOIN `Teachers` as Teachers1 ON `Teachers1`.`id`=`Tests`.`teacher_id` LEFT JOIN `Assignments` as Assignments1 ON `Assignments1`.`id`=`Tests`.`assignments_id` LEFT JOIN `Hours` as Hours1 ON `Hours1`.`id`=`Tests`.`hour_id` ",
			'Folders' => "`Folders` ",
			'Subfolders' => "`Subfolders` LEFT JOIN `Folders` as Folders1 ON `Folders1`.`id`=`Subfolders`.`folder_id` ",
			'Examinations' => "`Examinations` ",
		];

		$pkey = [
			'Protocol' => 'id',
			'Incoming_Files' => 'id',
			'Outcoming_Files' => 'id',
			'Documents_templates' => 'id',
			'TeachersName' => 'id',
			'Assignments' => 'id',
			'Teachers' => 'id',
			'Lessons' => 'id',
			'Classes' => 'id',
			'Sectors' => 'id',
			'Heads' => 'id',
			'Projectors_timetable' => 'id',
			'Hours' => 'id',
			'Projectors' => 'id',
			'Tests' => 'id',
			'Folders' => 'id',
			'Subfolders' => 'id',
			'Examinations' => 'id',
		];

		if(!isset($sql_from[$table_name])) return false;

		$from = ($skip_joins ? "`{$table_name}`" : $sql_from[$table_name]);

		if($skip_permissions) return $from . ' WHERE 1=1';

		// mm: build the query based on current member's permissions
		// allowing lower permissions if $lower_permissions set to 'user' or 'group'
		$perm = getTablePermissions($table_name);
		if($perm['view'] == 1 || ($perm['view'] > 1 && $lower_permissions == 'user')) { // view owner only
			$from .= ", `membership_userrecords` WHERE `{$table_name}`.`{$pkey[$table_name]}`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='{$table_name}' AND LCASE(`membership_userrecords`.`memberID`)='" . getLoggedMemberID() . "'";
		} elseif($perm['view'] == 2 || ($perm['view'] > 2 && $lower_permissions == 'group')) { // view group only
			$from .= ", `membership_userrecords` WHERE `{$table_name}`.`{$pkey[$table_name]}`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='{$table_name}' AND `membership_userrecords`.`groupID`='" . getLoggedGroupID() . "'";
		} elseif($perm['view'] == 3) { // view all
			$from .= ' WHERE 1=1';
		} else { // view none
			return false;
		}

		return $from;
	}

	#########################################################

	function get_joined_record($table, $id, $skip_permissions = false) {
		$sql_fields = get_sql_fields($table);
		$sql_from = get_sql_from($table, $skip_permissions);

		if(!$sql_fields || !$sql_from) return false;

		$pk = getPKFieldName($table);
		if(!$pk) return false;

		$safe_id = makeSafe($id, false);
		$sql = "SELECT {$sql_fields} FROM {$sql_from} AND `{$table}`.`{$pk}`='{$safe_id}'";
		$eo = ['silentErrors' => true];
		$res = sql($sql, $eo);
		if($row = db_fetch_assoc($res)) return $row;

		return false;
	}

	#########################################################

	function get_defaults($table) {
		/* array of tables and their fields, with default values (or empty), excluding automatic values */
		$defaults = [
			'Protocol' => [
				'id' => '',
				'serial_number' => '',
				'receipt_date' => '',
				'doc_number' => '',
				'place' => '',
				'authority_issuing' => '',
				'date_issuing' => '',
				'summary_incoming' => '',
				'to_whom' => '',
				'authority_outcoming' => '',
				'summary_outcoming' => '',
				'outcoming_date' => '',
				'processing_date' => '',
				'folder_id' => '',
				'subfolder_id' => '',
				'comments' => '',
			],
			'Incoming_Files' => [
				'id' => '',
				'protocol_id' => '',
				'title' => '',
				'file' => '',
			],
			'Outcoming_Files' => [
				'id' => '',
				'protocol_id' => '',
				'title' => '',
				'doc_template_id' => '',
				'hex_doc' => '',
				'file' => '',
			],
			'Documents_templates' => [
				'id' => '',
				'title' => '',
				'hex_doc' => '',
			],
			'TeachersName' => [
				'id' => '',
				'Name' => '',
			],
			'Assignments' => [
				'id' => '',
				'ClassID' => '',
				'LessonID' => '',
				'LessonName' => '',
				'Lesson_A_Assignment' => '',
				'Lesson_B_Assignment' => '',
				'Lesson_C_Assignment' => '',
				'LessonType' => '',
				'LessonHours' => '',
				'TeacherID' => '',
				'TeacherID2' => '',
				'datetime' => '',
			],
			'Teachers' => [
				'id' => '',
				'SectorID' => '',
				'Name_Sector' => '',
				'Name' => '',
				'Name2' => '',
				'Placement' => '',
				'Assumption_Date' => '',
				'Mandatory_Hours' => '0',
				'Main_Sector' => '',
				'Assigned_Hours_Theory' => '',
				'Assigned_Hours_Lab' => '',
				'Assigned_Hours' => '',
				'Diff' => '',
			],
			'Lessons' => [
				'id' => '',
				'Class' => '',
				'Name' => '',
				'Type' => '',
				'Hours' => '',
				'NameTypeHours' => '',
				'A_assignment' => '',
				'B_assignment' => '',
				'C_assignment' => '',
				'bank' => '0',
			],
			'Classes' => [
				'id' => '',
				'Class' => '',
				'Students_Number' => '',
				'Type' => '',
			],
			'Sectors' => [
				'id' => '',
				'Sector' => '',
			],
			'Heads' => [
				'id' => '',
				'teacher_id' => '',
				'class_id' => '',
			],
			'Projectors_timetable' => [
				'id' => '',
				'teacher_id' => '',
				'date' => '',
				'hour_id' => '',
				'projector_id' => '',
				'star_time' => '',
				'end_time' => '',
			],
			'Hours' => [
				'id' => '',
				'name' => '',
				'start_time' => '',
				'end_time' => '',
				'start_end_text' => '',
			],
			'Projectors' => [
				'id' => '',
				'img' => '',
				'name' => '',
			],
			'Tests' => [
				'id' => '',
				'teacher_id' => '',
				'assignments_id' => '',
				'date' => '',
				'hour_id' => '',
				'file' => '',
			],
			'Folders' => [
				'id' => '',
				'folder' => '',
			],
			'Subfolders' => [
				'id' => '',
				'folder_id' => '',
				'subfolder' => '',
			],
			'Examinations' => [
				'id' => '',
			],
		];

		return isset($defaults[$table]) ? $defaults[$table] : [];
	}

	#########################################################

	function htmlUserBar() {
		global $Translation;
		if(!defined('PREPEND_PATH')) define('PREPEND_PATH', '');

		$mi = getMemberInfo();
		$adminConfig = config('adminConfig');
		$home_page = (basename($_SERVER['PHP_SELF']) == 'index.php');
		ob_start();

		?>
		<nav class="navbar navbar-default navbar-fixed-top hidden-print" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- application title is obtained from the name besides the yellow database icon in AppGini, use underscores for spaces -->
				<a class="navbar-brand" href="<?php echo PREPEND_PATH; ?>index.php"><i class="glyphicon glyphicon-home"></i> <?php echo APP_TITLE; ?></a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav"><?php echo ($home_page ? '' : NavMenus()); ?></ul>

				<?php if(userCanImport()){ ?>
					<ul class="nav navbar-nav">
						<a href="<?php echo PREPEND_PATH; ?>import-csv.php" class="btn btn-default navbar-btn hidden-xs btn-import-csv" title="<?php echo html_attr($Translation['import csv file']); ?>"><i class="glyphicon glyphicon-th"></i> <?php echo $Translation['import CSV']; ?></a>
						<a href="<?php echo PREPEND_PATH; ?>import-csv.php" class="btn btn-default navbar-btn visible-xs btn-lg btn-import-csv" title="<?php echo html_attr($Translation['import csv file']); ?>"><i class="glyphicon glyphicon-th"></i> <?php echo $Translation['import CSV']; ?></a>
					</ul>
				<?php } ?>

				<?php if(getLoggedAdmin() !== false) { ?>
					<ul class="nav navbar-nav">
						<a href="<?php echo PREPEND_PATH; ?>admin/pageHome.php" class="btn btn-danger navbar-btn hidden-xs" title="<?php echo html_attr($Translation['admin area']); ?>"><i class="glyphicon glyphicon-cog"></i> <?php echo $Translation['admin area']; ?></a>
						<a href="<?php echo PREPEND_PATH; ?>admin/pageHome.php" class="btn btn-danger navbar-btn visible-xs btn-lg" title="<?php echo html_attr($Translation['admin area']); ?>"><i class="glyphicon glyphicon-cog"></i> <?php echo $Translation['admin area']; ?></a>
					</ul>
				<?php } ?>

				<?php if(!Request::val('signIn') && !Request::val('loginFailed')) { ?>
					<?php if(!$mi['username'] || $mi['username'] == $adminConfig['anonymousMember']) { ?>
						<p class="navbar-text navbar-right hidden-xs">&nbsp;</p>
						<a href="<?php echo PREPEND_PATH; ?>index.php?signIn=1" class="btn btn-success navbar-btn navbar-right hidden-xs"><?php echo $Translation['sign in']; ?></a>
						<p class="navbar-text navbar-right hidden-xs">
							<?php echo $Translation['not signed in']; ?>
						</p>
						<a href="<?php echo PREPEND_PATH; ?>index.php?signIn=1" class="btn btn-success btn-block btn-lg navbar-btn visible-xs">
							<?php echo $Translation['not signed in']; ?>
							<i class="glyphicon glyphicon-chevron-right"></i> 
							<?php echo $Translation['sign in']; ?>
						</a>
					<?php } else { ?>
						<ul class="nav navbar-nav navbar-right hidden-xs">
							<!-- logged user profile menu -->
							<li class="dropdown" title="<?php echo html_attr("{$Translation['signed as']} {$mi['username']}"); ?>">
								<a href="#" class="dropdown-toggle profile-menu-icon" data-toggle="dropdown"><i class="glyphicon glyphicon-user icon"></i><span class="profile-menu-text"><?php echo $mi['username']; ?></span><b class="caret"></b></a>
								<ul class="dropdown-menu profile-menu">
									<li class="user-profile-menu-item" title="<?php echo html_attr("{$Translation['Your info']}"); ?>">
										<a href="<?php echo PREPEND_PATH; ?>membership_profile.php"><i class="glyphicon glyphicon-user"></i> <span class="username"><?php echo $mi['username']; ?></span></a>
									</li>
									<li class="keyboard-shortcuts-menu-item" title="<?php echo html_attr("{$Translation['keyboard shortcuts']}"); ?>" class="hidden-xs">
										<a href="#" class="help-shortcuts-launcher">
											<img src="<?php echo PREPEND_PATH; ?>resources/images/keyboard.png">
											<?php echo html_attr($Translation['keyboard shortcuts']); ?>
										</a>
									</li>
									<li class="sign-out-menu-item" title="<?php echo html_attr("{$Translation['sign out']}"); ?>">
										<a href="<?php echo PREPEND_PATH; ?>index.php?signOut=1"><i class="glyphicon glyphicon-log-out"></i> <?php echo $Translation['sign out']; ?></a>
									</li>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav visible-xs">
							<a class="btn navbar-btn btn-default btn-lg visible-xs" href="<?php echo PREPEND_PATH; ?>index.php?signOut=1"><i class="glyphicon glyphicon-log-out"></i> <?php echo $Translation['sign out']; ?></a>
							<p class="navbar-text text-center signed-in-as">
								<?php echo $Translation['signed as']; ?> <strong><a href="<?php echo PREPEND_PATH; ?>membership_profile.php" class="navbar-link username"><?php echo $mi['username']; ?></a></strong>
							</p>
						</ul>
						<script>
							/* periodically check if user is still signed in */
							setInterval(function() {
								$j.ajax({
									url: '<?php echo PREPEND_PATH; ?>ajax_check_login.php',
									success: function(username) {
										if(!username.length) window.location = '<?php echo PREPEND_PATH; ?>index.php?signIn=1';
									}
								});
							}, 60000);
						</script>
					<?php } ?>
				<?php } ?>
			</div>
		</nav>
		<?php

		return ob_get_clean();
	}

	#########################################################

	function showNotifications($msg = '', $class = '', $fadeout = true) {
		global $Translation;
		if($error_message = strip_tags(Request::val('error_message')))
			$error_message = '<div class="text-bold">' . $error_message . '</div>';

		if(!$msg) { // if no msg, use url to detect message to display
			if(Request::val('record-added-ok')) {
				$msg = $Translation['new record saved'];
				$class = 'alert-success';
			} elseif(Request::val('record-added-error')) {
				$msg = $Translation['Couldn\'t save the new record'] . $error_message;
				$class = 'alert-danger';
				$fadeout = false;
			} elseif(Request::val('record-updated-ok')) {
				$msg = $Translation['record updated'];
				$class = 'alert-success';
			} elseif(Request::val('record-updated-error')) {
				$msg = $Translation['Couldn\'t save changes to the record'] . $error_message;
				$class = 'alert-danger';
				$fadeout = false;
			} elseif(Request::val('record-deleted-ok')) {
				$msg = $Translation['The record has been deleted successfully'];
				$class = 'alert-success';
			} elseif(Request::val('record-deleted-error')) {
				$msg = $Translation['Couldn\'t delete this record'] . $error_message;
				$class = 'alert-danger';
				$fadeout = false;
			} else {
				return '';
			}
		}
		$id = 'notification-' . rand();

		ob_start();
		// notification template
		?>
		<div id="%%ID%%" class="alert alert-dismissable %%CLASS%%" style="opacity: 1; padding-top: 6px; padding-bottom: 6px; animation: fadeIn 1.5s ease-out; z-index: 100; position: relative;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			%%MSG%%
		</div>
		<script>
			$j(function() {
				var autoDismiss = <?php echo $fadeout ? 'true' : 'false'; ?>,
					embedded = !$j('nav').length,
					messageDelay = 10, fadeDelay = 1.5;

				if(!autoDismiss) {
					if(embedded)
						$j('#%%ID%%').before('<div class="modal-top-spacer"></div>');
					else
						$j('#%%ID%%').css({ margin: '0 0 1rem' });

					return;
				}

				// below code runs only in case of autoDismiss

				if(embedded)
					$j('#%%ID%%').css({ margin: '1rem 0 -1rem' });
				else
					$j('#%%ID%%').css({ margin: '-15px 0 -20px' });

				setTimeout(function() {
					$j('#%%ID%%').css({    animation: 'fadeOut ' + fadeDelay + 's ease-out' });
				}, messageDelay * 1000);

				setTimeout(function() {
					$j('#%%ID%%').css({    visibility: 'hidden' });
				}, (messageDelay + fadeDelay) * 1000);
			})
		</script>
		<style>
			@keyframes fadeIn {
				0%   { opacity: 0; }
				100% { opacity: 1; }
			}
			@keyframes fadeOut {
				0%   { opacity: 1; }
				100% { opacity: 0; }
			}
		</style>

		<?php
		$out = ob_get_clean();

		$out = str_replace('%%ID%%', $id, $out);
		$out = str_replace('%%MSG%%', $msg, $out);
		$out = str_replace('%%CLASS%%', $class, $out);

		return $out;
	}

	#########################################################

	function validMySQLDate($date) {
		$date = trim($date);

		try {
			$dtObj = new DateTime($date);
		} catch(Exception $e) {
			return false;
		}

		$parts = explode('-', $date);
		return (
			count($parts) == 3
			// see https://dev.mysql.com/doc/refman/8.0/en/datetime.html
			&& intval($parts[0]) >= 1000
			&& intval($parts[0]) <= 9999
			&& intval($parts[1]) >= 1
			&& intval($parts[1]) <= 12
			&& intval($parts[2]) >= 1
			&& intval($parts[2]) <= 31
		);
	}

	#########################################################

	function parseMySQLDate($date, $altDate) {
		// is $date valid?
		if(validMySQLDate($date)) return trim($date);

		if($date != '--' && validMySQLDate($altDate)) return trim($altDate);

		if($date != '--' && $altDate && is_numeric($altDate))
			return @date('Y-m-d', @time() + ($altDate >= 1 ? $altDate - 1 : $altDate) * 86400);

		return '';
	}

	#########################################################

	function parseCode($code, $isInsert = true, $rawData = false) {
		$mi = Authentication::getUser();

		if($isInsert) {
			$arrCodes = [
				'<%%creatorusername%%>' => $mi['username'],
				'<%%creatorgroupid%%>' => $mi['groupId'],
				'<%%creatorip%%>' => $_SERVER['REMOTE_ADDR'],
				'<%%creatorgroup%%>' => $mi['group'],

				'<%%creationdate%%>' => ($rawData ? date('Y-m-d') : date(app_datetime_format('phps'))),
				'<%%creationtime%%>' => ($rawData ? date('H:i:s') : date(app_datetime_format('phps', 't'))),
				'<%%creationdatetime%%>' => ($rawData ? date('Y-m-d H:i:s') : date(app_datetime_format('phps', 'dt'))),
				'<%%creationtimestamp%%>' => ($rawData ? date('Y-m-d H:i:s') : time()),
			];
		} else {
			$arrCodes = [
				'<%%editorusername%%>' => $mi['username'],
				'<%%editorgroupid%%>' => $mi['groupId'],
				'<%%editorip%%>' => $_SERVER['REMOTE_ADDR'],
				'<%%editorgroup%%>' => $mi['group'],

				'<%%editingdate%%>' => ($rawData ? date('Y-m-d') : date(app_datetime_format('phps'))),
				'<%%editingtime%%>' => ($rawData ? date('H:i:s') : date(app_datetime_format('phps', 't'))),
				'<%%editingdatetime%%>' => ($rawData ? date('Y-m-d H:i:s') : date(app_datetime_format('phps', 'dt'))),
				'<%%editingtimestamp%%>' => ($rawData ? date('Y-m-d H:i:s') : time()),
			];
		}

		$pc = str_ireplace(array_keys($arrCodes), array_values($arrCodes), $code);

		return $pc;
	}

	#########################################################

	function addFilter($index, $filterAnd, $filterField, $filterOperator, $filterValue) {
		// validate input
		if($index < 1 || $index > 80 || !is_int($index)) return false;
		if($filterAnd != 'or')   $filterAnd = 'and';
		$filterField = intval($filterField);

		/* backward compatibility */
		if(in_array($filterOperator, FILTER_OPERATORS)) {
			$filterOperator = array_search($filterOperator, FILTER_OPERATORS);
		}

		if(!in_array($filterOperator, array_keys(FILTER_OPERATORS))) {
			$filterOperator = 'like';
		}

		if(!$filterField) {
			$filterOperator = '';
			$filterValue = '';
		}

		$_REQUEST['FilterAnd'][$index] = $filterAnd;
		$_REQUEST['FilterField'][$index] = $filterField;
		$_REQUEST['FilterOperator'][$index] = $filterOperator;
		$_REQUEST['FilterValue'][$index] = $filterValue;

		return true;
	}

	#########################################################

	function clearFilters() {
		for($i=1; $i<=80; $i++) {
			addFilter($i, '', 0, '', '');
		}
	}

	#########################################################

	/**
	* Loads a given view from the templates folder, passing the given data to it
	* @param $view the name of a php file (without extension) to be loaded from the 'templates' folder
	* @param $the_data_to_pass_to_the_view (optional) associative array containing the data to pass to the view
	* @return string the output of the parsed view
	*/
	function loadView($view, $the_data_to_pass_to_the_view = false) {
		global $Translation;

		$view = __DIR__ . "/templates/$view.php";
		if(!is_file($view)) return false;

		if(is_array($the_data_to_pass_to_the_view)) {
			foreach($the_data_to_pass_to_the_view as $data_k => $data_v)
				$$data_k = $data_v;
		}
		unset($the_data_to_pass_to_the_view, $data_k, $data_v);

		ob_start();
		@include($view);
		return ob_get_clean();
	}

	#########################################################

	/**
	* Loads a table template from the templates folder, passing the given data to it
	* @param $table_name the name of the table whose template is to be loaded from the 'templates' folder
	* @param $the_data_to_pass_to_the_table associative array containing the data to pass to the table template
	* @return the output of the parsed table template as a string
	*/
	function loadTable($table_name, $the_data_to_pass_to_the_table = []) {
		$dont_load_header = $the_data_to_pass_to_the_table['dont_load_header'];
		$dont_load_footer = $the_data_to_pass_to_the_table['dont_load_footer'];

		$header = $table = $footer = '';

		if(!$dont_load_header) {
			// try to load tablename-header
			if(!($header = loadView("{$table_name}-header", $the_data_to_pass_to_the_table))) {
				$header = loadView('table-common-header', $the_data_to_pass_to_the_table);
			}
		}

		$table = loadView($table_name, $the_data_to_pass_to_the_table);

		if(!$dont_load_footer) {
			// try to load tablename-footer
			if(!($footer = loadView("{$table_name}-footer", $the_data_to_pass_to_the_table))) {
				$footer = loadView('table-common-footer', $the_data_to_pass_to_the_table);
			}
		}

		return "{$header}{$table}{$footer}";
	}

	#########################################################

	function br2nl($text) {
		return  preg_replace('/\<br(\s*)?\/?\>/i', "\n", $text);
	}

	#########################################################

	function entitiesToUTF8($input) {
		return preg_replace_callback('/(&#[0-9]+;)/', '_toUTF8', $input);
	}

	function _toUTF8($m) {
		if(function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
		} else {
			return $m[1];
		}
	}

	#########################################################

	function func_get_args_byref() {
		if(!function_exists('debug_backtrace')) return false;

		$trace = debug_backtrace();
		return $trace[1]['args'];
	}

	#########################################################

	function permissions_sql($table, $level = 'all') {
		if(!in_array($level, ['user', 'group'])) { $level = 'all'; }
		$perm = getTablePermissions($table);
		$from = '';
		$where = '';
		$pk = getPKFieldName($table);

		if($perm['view'] == 1 || ($perm['view'] > 1 && $level == 'user')) { // view owner only
			$from = 'membership_userrecords';
			$where = "(`$table`.`$pk`=membership_userrecords.pkValue and membership_userrecords.tableName='$table' and lcase(membership_userrecords.memberID)='" . getLoggedMemberID() . "')";
		} elseif($perm['view'] == 2 || ($perm['view'] > 2 && $level == 'group')) { // view group only
			$from = 'membership_userrecords';
			$where = "(`$table`.`$pk`=membership_userrecords.pkValue and membership_userrecords.tableName='$table' and membership_userrecords.groupID='" . getLoggedGroupID() . "')";
		} elseif($perm['view'] == 3) { // view all
			// no further action
		} elseif($perm['view'] == 0) { // view none
			return false;
		}

		return ['where' => $where, 'from' => $from, 0 => $where, 1 => $from];
	}

	#########################################################

	function error_message($msg, $back_url = '', $full_page = true) {
		global $Translation;

		ob_start();

		if($full_page) include(__DIR__ . '/header.php');

		echo '<div class="panel panel-danger">';
			echo '<div class="panel-heading"><h3 class="panel-title">' . $Translation['error:'] . '</h3></div>';
			echo '<div class="panel-body"><p class="text-danger">' . $msg . '</p>';
			if($back_url !== false) { // explicitly passing false suppresses the back link completely
				echo '<div class="text-center">';
				if($back_url) {
					echo '<a href="' . $back_url . '" class="btn btn-danger btn-lg vspacer-lg"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['< back'] . '</a>';
				// in embedded mode, close modal window
				} elseif(Request::val('Embedded')) {
					echo '<button class="btn btn-danger btn-lg" type="button" onclick="AppGini.closeParentModal();"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['< back'] . '</button>';
				} else {
					echo '<a href="#" class="btn btn-danger btn-lg vspacer-lg" onclick="history.go(-1); return false;"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['< back'] . '</a>';
				}
				echo '</div>';
			}
			echo '</div>';
		echo '</div>';

		if($full_page) include(__DIR__ . '/footer.php');

		return ob_get_clean();
	}

	#########################################################

	function toMySQLDate($formattedDate, $sep = datalist_date_separator, $ord = datalist_date_format) {
		// extract date elements
		$de=explode($sep, $formattedDate);
		$mySQLDate=intval($de[strpos($ord, 'Y')]).'-'.intval($de[strpos($ord, 'm')]).'-'.intval($de[strpos($ord, 'd')]);
		return $mySQLDate;
	}

	#########################################################

	function reIndex(&$arr) {
		$i=1;
		foreach($arr as $n=>$v) {
			$arr2[$i]=$n;
			$i++;
		}
		return $arr2;
	}

	#########################################################

	function get_embed($provider, $url, $max_width = '', $max_height = '', $retrieve = 'html') {
		global $Translation;
		if(!$url) return '';

		$providers = [
			'youtube' => ['oembed' => 'https://www.youtube.com/oembed?'],
			'googlemap' => ['oembed' => '', 'regex' => '/^http.*\.google\..*maps/i'],
		];

		if(!$max_height) $max_height = 360;
		if(!$max_width) $max_width = 480;

		if(!isset($providers[$provider])) {
			return '<div class="text-danger">' . $Translation['invalid provider'] . '</div>';
		}

		if(isset($providers[$provider]['regex']) && !preg_match($providers[$provider]['regex'], $url)) {
			return '<div class="text-danger">' . $Translation['invalid url'] . '</div>';
		}

		if($providers[$provider]['oembed']) {
			$oembed = $providers[$provider]['oembed'] . 'url=' . urlencode($url) . "&amp;maxwidth={$max_width}&amp;maxheight={$max_height}&amp;format=json";
			$data_json = request_cache($oembed);

			$data = json_decode($data_json, true);
			if($data === null) {
				/* an error was returned rather than a json string */
				if($retrieve == 'html') return "<div class=\"text-danger\">{$data_json}\n<!-- {$oembed} --></div>";
				return '';
			}

			return (isset($data[$retrieve]) ? $data[$retrieve] : $data['html']);
		}

		/* special cases (where there is no oEmbed provider) */
		if($provider == 'googlemap') return get_embed_googlemap($url, $max_width, $max_height, $retrieve);

		return '<div class="text-danger">Invalid provider!</div>';
	}

	#########################################################

	function get_embed_googlemap($url, $max_width = '', $max_height = '', $retrieve = 'html') {
		global $Translation;
		$url_parts = parse_url($url);
		$coords_regex = '/-?\d+(\.\d+)?[,+]-?\d+(\.\d+)?(,\d{1,2}z)?/'; /* https://stackoverflow.com/questions/2660201 */

		if(preg_match($coords_regex, $url_parts['path'] . '?' . $url_parts['query'], $m)) {
			list($lat, $long, $zoom) = explode(',', $m[0]);
			$zoom = intval($zoom);
			if(!$zoom) $zoom = 10; /* default zoom */
			if(!$max_height) $max_height = 360;
			if(!$max_width) $max_width = 480;

			$api_key = config('adminConfig')['googleAPIKey'];
			$embed_url = "https://www.google.com/maps/embed/v1/view?key={$api_key}&amp;center={$lat},{$long}&amp;zoom={$zoom}&amp;maptype=roadmap";
			$thumbnail_url = "https://maps.googleapis.com/maps/api/staticmap?key={$api_key}&amp;center={$lat},{$long}&amp;zoom={$zoom}&amp;maptype=roadmap&amp;size={$max_width}x{$max_height}";

			if($retrieve == 'html') {
				return "<iframe width=\"{$max_width}\" height=\"{$max_height}\" frameborder=\"0\" style=\"border:0\" src=\"{$embed_url}\"></iframe>";
			} else {
				return $thumbnail_url;
			}
		} else {
			return '<div class="text-danger">' . $Translation['cant retrieve coordinates from url'] . '</div>';
		}
	}

	#########################################################

	function request_cache($request, $force_fetch = false) {
		$max_cache_lifetime = 7 * 86400; /* max cache lifetime in seconds before refreshing from source */

		// force fetching request if no cache table exists
		$cache_table_exists = sqlValue("show tables like 'membership_cache'");
		if(!$cache_table_exists)
			return request_cache($request, true);

		/* retrieve response from cache if exists */
		if(!$force_fetch) {
			$res = sql("select response, request_ts from membership_cache where request='" . md5($request) . "'", $eo);
			if(!$row = db_fetch_array($res)) return request_cache($request, true);

			$response = $row[0];
			$response_ts = $row[1];
			if($response_ts < time() - $max_cache_lifetime) return request_cache($request, true);
		}

		/* if no response in cache, issue a request */
		if(!$response || $force_fetch) {
			$response = @file_get_contents($request);
			if($response === false) {
				$error = error_get_last();
				$error_message = preg_replace('/.*: (.*)/', '$1', $error['message']);
				return $error_message;
			} elseif($cache_table_exists) {
				/* store response in cache */
				$ts = time();
				sql("replace into membership_cache set request='" . md5($request) . "', request_ts='{$ts}', response='" . makeSafe($response, false) . "'", $eo);
			}
		}

		return $response;
	}

	#########################################################

	function check_record_permission($table, $id, $perm = 'view') {
		if($perm != 'edit' && $perm != 'delete') $perm = 'view';

		$perms = getTablePermissions($table);
		if(!$perms[$perm]) return false;

		$safe_id = makeSafe($id);
		$safe_table = makeSafe($table);

		// fix for zero-fill: quote id only if not numeric
		if(!is_numeric($safe_id)) $safe_id = "'$safe_id'";

		if($perms[$perm] == 1) { // own records only
			$username = getLoggedMemberID();
			$owner = sqlValue("select memberID from membership_userrecords where tableName='{$safe_table}' and pkValue={$safe_id}");
			if($owner == $username) return true;
		} elseif($perms[$perm] == 2) { // group records
			$group_id = getLoggedGroupID();
			$owner_group_id = sqlValue("select groupID from membership_userrecords where tableName='{$safe_table}' and pkValue={$safe_id}");
			if($owner_group_id == $group_id) return true;
		} elseif($perms[$perm] == 3) { // all records
			return true;
		}

		return false;
	}

	#########################################################

	function NavMenus($options = []) {
		if(!defined('PREPEND_PATH')) define('PREPEND_PATH', '');
		global $Translation;
		$prepend_path = PREPEND_PATH;

		/* default options */
		if(empty($options)) {
			$options = ['tabs' => 7];
		}

		$table_group_name = array_keys(get_table_groups()); /* 0 => group1, 1 => group2 .. */
		/* if only one group named 'None', set to translation of 'select a table' */
		if((count($table_group_name) == 1 && $table_group_name[0] == 'None') || count($table_group_name) < 1) $table_group_name[0] = $Translation['select a table'];
		$table_group_index = array_flip($table_group_name); /* group1 => 0, group2 => 1 .. */
		$menu = array_fill(0, count($table_group_name), '');

		$t = time();
		$arrTables = getTableList();
		if(is_array($arrTables)) {
			foreach($arrTables as $tn => $tc) {
				/* ---- list of tables where hide link in nav menu is set ---- */
				$tChkHL = array_search($tn, []);

				/* ---- list of tables where filter first is set ---- */
				$tChkFF = array_search($tn, []);
				if($tChkFF !== false && $tChkFF !== null) {
					$searchFirst = '&Filter_x=1';
				} else {
					$searchFirst = '';
				}

				/* when no groups defined, $table_group_index['None'] is NULL, so $menu_index is still set to 0 */
				$menu_index = intval($table_group_index[$tc[3]]);
				if(!$tChkHL && $tChkHL !== 0) $menu[$menu_index] .= "<li><a href=\"{$prepend_path}{$tn}_view.php?t={$t}{$searchFirst}\"><img src=\"{$prepend_path}" . ($tc[2] ? $tc[2] : 'blank.gif') . "\" height=\"32\"> {$tc[0]}</a></li>";
			}
		}

		// custom nav links, as defined in "hooks/links-navmenu.php" 
		global $navLinks;
		if(is_array($navLinks)) {
			$memberInfo = getMemberInfo();
			$links_added = [];
			foreach($navLinks as $link) {
				if(!isset($link['url']) || !isset($link['title'])) continue;
				if(getLoggedAdmin() !== false || @in_array($memberInfo['group'], $link['groups']) || @in_array('*', $link['groups'])) {
					$menu_index = intval($link['table_group']);
					if(!$links_added[$menu_index]) $menu[$menu_index] .= '<li class="divider"></li>';

					/* add prepend_path to custom links if they aren't absolute links */
					if(!preg_match('/^(http|\/\/)/i', $link['url'])) $link['url'] = $prepend_path . $link['url'];
					if(!preg_match('/^(http|\/\/)/i', $link['icon']) && $link['icon']) $link['icon'] = $prepend_path . $link['icon'];

					$menu[$menu_index] .= "<li><a href=\"{$link['url']}\"><img src=\"" . ($link['icon'] ? $link['icon'] : "{$prepend_path}blank.gif") . "\" height=\"32\"> {$link['title']}</a></li>";
					$links_added[$menu_index]++;
				}
			}
		}

		$menu_wrapper = '';
		for($i = 0; $i < count($menu); $i++) {
			$menu_wrapper .= <<<EOT
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$table_group_name[$i]} <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">{$menu[$i]}</ul>
				</li>
EOT;
		}

		return $menu_wrapper;
	}

	#########################################################

	function StyleSheet() {
		if(!defined('PREPEND_PATH')) define('PREPEND_PATH', '');
		$prepend_path = PREPEND_PATH;
		$mtime = filemtime( __DIR__ . '/dynamic.css');

		$css_links = <<<EOT

			<link rel="stylesheet" href="{$prepend_path}resources/initializr/css/yeti.css">
			<link rel="stylesheet" href="{$prepend_path}resources/lightbox/css/lightbox.css" media="screen">
			<link rel="stylesheet" href="{$prepend_path}resources/select2/select2.css" media="screen">
			<link rel="stylesheet" href="{$prepend_path}resources/timepicker/bootstrap-timepicker.min.css" media="screen">
			<link rel="stylesheet" href="{$prepend_path}dynamic.css?{$mtime}">
EOT;

		return $css_links;
	}

	#########################################################

	function PrepareUploadedFile($FieldName, $MaxSize, $FileTypes = 'jpg|jpeg|gif|png|webp', $NoRename = false, $dir = '') {
		global $Translation;
		$f = $_FILES[$FieldName];
		if($f['error'] == 4 || !$f['name']) return '';

		$dir = getUploadDir($dir);

		/* get php.ini upload_max_filesize in bytes */
		$php_upload_size_limit = toBytes(ini_get('upload_max_filesize'));
		$MaxSize = min($MaxSize, $php_upload_size_limit);

		if($f['size'] > $MaxSize || $f['error']) {
			echo error_message(str_replace(['<MaxSize>', '{MaxSize}'], intval($MaxSize / 1024), $Translation['file too large']));
			exit;
		}
		if(!preg_match('/\.(' . $FileTypes . ')$/i', $f['name'], $ft)) {
			echo error_message(str_replace(['<FileTypes>', '{FileTypes}'], str_replace('|', ', ', $FileTypes), $Translation['invalid file type']));
			exit;
		}

		$name = str_replace(' ', '_', $f['name']);
		if(!$NoRename) $name = substr(md5(microtime() . rand(0, 100000)), -17) . $ft[0];

		if(!file_exists($dir)) @mkdir($dir, 0777);

		if(!@move_uploaded_file($f['tmp_name'], $dir . $name)) {
			echo error_message("Couldn't save the uploaded file. Try chmoding the upload folder '{$dir}' to 777.");
			exit;
		}

		@chmod($dir . $name, 0666);
		return $name;
	}

	#########################################################

	function get_home_links($homeLinks, $default_classes, $tgroup = '') {
		if(!is_array($homeLinks) || !count($homeLinks)) return '';

		$memberInfo = getMemberInfo();

		ob_start();
		foreach($homeLinks as $link) {
			if(!isset($link['url']) || !isset($link['title'])) continue;
			if($tgroup != $link['table_group'] && $tgroup != '*') continue;

			/* fall-back classes if none defined */
			if(!$link['grid_column_classes']) $link['grid_column_classes'] = $default_classes['grid_column'];
			if(!$link['panel_classes']) $link['panel_classes'] = $default_classes['panel'];
			if(!$link['link_classes']) $link['link_classes'] = $default_classes['link'];

			if(getLoggedAdmin() !== false || @in_array($memberInfo['group'], $link['groups']) || @in_array('*', $link['groups'])) {
				?>
				<div class="col-xs-12 <?php echo $link['grid_column_classes']; ?>">
					<div class="panel <?php echo $link['panel_classes']; ?>">
						<div class="panel-body">
							<a class="btn btn-block btn-lg <?php echo $link['link_classes']; ?>" title="<?php echo preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", html_attr(strip_tags($link['description']))); ?>" href="<?php echo $link['url']; ?>"><?php echo ($link['icon'] ? '<img src="' . $link['icon'] . '">' : ''); ?><strong><?php echo $link['title']; ?></strong></a>
							<div class="panel-body-description"><?php echo $link['description']; ?></div>
						</div>
					</div>
				</div>
				<?php
			}
		}

		return ob_get_clean();
	}

	#########################################################

	function quick_search_html($search_term, $label, $separate_dv = true) {
		global $Translation;

		$safe_search = html_attr($search_term);
		$safe_label = html_attr($label);
		$safe_clear_label = html_attr($Translation['Reset Filters']);

		if($separate_dv) {
			$reset_selection = "document.myform.SelectedID.value = '';";
		} else {
			$reset_selection = "document.myform.writeAttribute('novalidate', 'novalidate');";
		}
		$reset_selection .= ' document.myform.NoDV.value=1; return true;';

		$html = <<<EOT
		<div class="input-group" id="quick-search">
			<input type="text" id="SearchString" name="SearchString" value="{$safe_search}" class="form-control" placeholder="{$safe_label}">
			<span class="input-group-btn">
				<button name="Search_x" value="1" id="Search" type="submit" onClick="{$reset_selection}" class="btn btn-default" title="{$safe_label}"><i class="glyphicon glyphicon-search"></i></button>
				<button name="ClearQuickSearch" value="1" id="ClearQuickSearch" type="submit" onClick="\$j('#SearchString').val(''); {$reset_selection}" class="btn btn-default" title="{$safe_clear_label}"><i class="glyphicon glyphicon-remove-circle"></i></button>
			</span>
		</div>
EOT;
		return $html;
	}

	#########################################################

	function getLookupFields($skipPermissions = false, $filterByPermission = 'view') {
		$pcConfig = [
			'Protocol' => [
			],
			'Incoming_Files' => [
				'protocol_id' => [
					'parent-table' => 'Protocol',
					'parent-primary-key' => 'id',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => '&#917;&#953;&#963;&#949;&#961;&#967;&#972;&#956;&#949;&#957;&#945; &#913;&#961;&#967;&#949;&#943;&#945; <span class="hidden child-label-Incoming_Files child-field-caption">(&#913;&#961;. &#928;&#961;&#969;&#964;&#959;&#954;&#972;&#955;&#955;&#959;&#965;)</span>',
					'auto-close' => true,
					'table-icon' => 'resources/table_icons/align_left.png',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => [1 => '&#913;&#961;. &#928;&#961;&#969;&#964;&#959;&#954;&#972;&#955;&#955;&#959;&#965;', 2 => '&#920;&#941;&#956;&#945;', 3 => '&#913;&#961;&#967;&#949;&#943;&#959;'],
					'display-field-names' => [1 => 'protocol_id', 2 => 'title', 3 => 'file'],
					'sortable-fields' => [0 => '`Incoming_Files`.`id`', 1 => 2, 2 => 3, 3 => 4],
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-Incoming_Files',
					'template-printable' => 'children-Incoming_Files-printable',
					'query' => "SELECT `Incoming_Files`.`id` as 'id', IF(    CHAR_LENGTH(`Protocol1`.`serial_number`) || CHAR_LENGTH(if(`Protocol1`.`receipt_date`,date_format(`Protocol1`.`receipt_date`,'%d-%m-%Y'),'')), CONCAT_WS('',   `Protocol1`.`serial_number`, '/', if(`Protocol1`.`receipt_date`,date_format(`Protocol1`.`receipt_date`,'%d-%m-%Y'),'')), '') as 'protocol_id', `Incoming_Files`.`title` as 'title', `Incoming_Files`.`file` as 'file' FROM `Incoming_Files` LEFT JOIN `Protocol` as Protocol1 ON `Protocol1`.`id`=`Incoming_Files`.`protocol_id` "
				],
			],
			'Outcoming_Files' => [
				'protocol_id' => [
					'parent-table' => 'Protocol',
					'parent-primary-key' => 'id',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => '&#917;&#958;&#949;&#961;&#967;&#972;&#956;&#949;&#957;&#945; &#913;&#961;&#967;&#949;&#943;&#945; <span class="hidden child-label-Outcoming_Files child-field-caption">(&#913;&#961;. &#928;&#961;&#969;&#964;&#959;&#954;&#972;&#955;&#955;&#959;&#965;)</span>',
					'auto-close' => true,
					'table-icon' => 'resources/table_icons/align_right.png',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => [1 => '&#913;&#961;. &#928;&#961;&#969;&#964;&#959;&#954;&#972;&#955;&#955;&#959;&#965;', 2 => '&#920;&#941;&#956;&#945;', 5 => '&#917;&#958;&#949;&#961;&#967;&#972;&#956;&#949;&#957;&#959;'],
					'display-field-names' => [1 => 'protocol_id', 2 => 'title', 5 => 'file'],
					'sortable-fields' => [0 => '`Outcoming_Files`.`id`', 1 => 2, 2 => 3, 3 => '`Documents_templates1`.`title`', 4 => 5, 5 => 6],
					'records-per-page' => 10,
					'default-sort-by' => false,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-Outcoming_Files',
					'template-printable' => 'children-Outcoming_Files-printable',
					'query' => "SELECT `Outcoming_Files`.`id` as 'id', IF(    CHAR_LENGTH(`Protocol1`.`serial_number`) || CHAR_LENGTH(if(`Protocol1`.`outcoming_date`,date_format(`Protocol1`.`outcoming_date`,'%d-%m-%Y'),'')), CONCAT_WS('',   `Protocol1`.`serial_number`, '/', if(`Protocol1`.`outcoming_date`,date_format(`Protocol1`.`outcoming_date`,'%d-%m-%Y'),'')), '') as 'protocol_id', `Outcoming_Files`.`title` as 'title', IF(    CHAR_LENGTH(`Documents_templates1`.`title`), CONCAT_WS('',   `Documents_templates1`.`title`), '') as 'doc_template_id', `Outcoming_Files`.`hex_doc` as 'hex_doc', `Outcoming_Files`.`file` as 'file' FROM `Outcoming_Files` LEFT JOIN `Protocol` as Protocol1 ON `Protocol1`.`id`=`Outcoming_Files`.`protocol_id` LEFT JOIN `Documents_templates` as Documents_templates1 ON `Documents_templates1`.`id`=`Outcoming_Files`.`doc_template_id` "
				],
			],
			'Documents_templates' => [
			],
			'TeachersName' => [
			],
			'Assignments' => [
				'ClassID' => [
					'parent-table' => 'Classes',
					'parent-primary-key' => 'id',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962; <span class="hidden child-label-Assignments child-field-caption">(&#932;&#956;&#942;&#956;&#945;)</span>',
					'auto-close' => true,
					'table-icon' => 'resources/table_icons/connect.png',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => [1 => '&#932;&#956;&#942;&#956;&#945;', 3 => '&#924;&#940;&#952;&#951;&#956;&#945;', 4 => '&#913; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 5 => '&#914; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 6 => '&#915; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 7 => '&#920;&#949;&#969;&#961;./&#917;&#961;&#947;.', 8 => '&#911;&#961;&#949;&#962;', 9 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#913;', 10 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#914;', 11 => '&#932;&#949;&#955;&#949;&#965;&#964;&#945;&#943;&#945; &#964;&#961;&#959;&#960;&#959;&#960;&#959;&#943;&#951;&#963;&#951;'],
					'display-field-names' => [1 => 'ClassID', 3 => 'LessonName', 4 => 'Lesson_A_Assignment', 5 => 'Lesson_B_Assignment', 6 => 'Lesson_C_Assignment', 7 => 'LessonType', 8 => 'LessonHours', 9 => 'TeacherID', 10 => 'TeacherID2', 11 => 'datetime'],
					'sortable-fields' => [0 => '`Assignments`.`id`', 1 => '`Classes1`.`Class`', 2 => '`Lessons1`.`NameTypeHours`', 3 => 4, 4 => 5, 5 => 6, 6 => 7, 7 => 8, 8 => '`Assignments`.`LessonHours`', 9 => '`Teachers1`.`Name_Sector`', 10 => '`Teachers2`.`Name_Sector`', 11 => '`Assignments`.`datetime`'],
					'records-per-page' => 10,
					'default-sort-by' => 1,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-Assignments',
					'template-printable' => 'children-Assignments-printable',
					'query' => "SELECT `Assignments`.`id` as 'id', IF(    CHAR_LENGTH(`Classes1`.`Class`), CONCAT_WS('',   `Classes1`.`Class`), '') as 'ClassID', IF(    CHAR_LENGTH(`Lessons1`.`NameTypeHours`), CONCAT_WS('',   `Lessons1`.`NameTypeHours`), '') as 'LessonID', `Assignments`.`LessonName` as 'LessonName', `Assignments`.`Lesson_A_Assignment` as 'Lesson_A_Assignment', `Assignments`.`Lesson_B_Assignment` as 'Lesson_B_Assignment', `Assignments`.`Lesson_C_Assignment` as 'Lesson_C_Assignment', `Assignments`.`LessonType` as 'LessonType', `Assignments`.`LessonHours` as 'LessonHours', IF(    CHAR_LENGTH(`Teachers1`.`Name_Sector`), CONCAT_WS('',   `Teachers1`.`Name_Sector`), '') as 'TeacherID', IF(    CHAR_LENGTH(`Teachers2`.`Name_Sector`), CONCAT_WS('',   `Teachers2`.`Name_Sector`), '') as 'TeacherID2', if(`Assignments`.`datetime`,date_format(`Assignments`.`datetime`,'%d-%m-%Y %H:%i'),'') as 'datetime' FROM `Assignments` LEFT JOIN `Classes` as Classes1 ON `Classes1`.`id`=`Assignments`.`ClassID` LEFT JOIN `Lessons` as Lessons1 ON `Lessons1`.`id`=`Assignments`.`LessonID` LEFT JOIN `Teachers` as Teachers1 ON `Teachers1`.`id`=`Assignments`.`TeacherID` LEFT JOIN `Teachers` as Teachers2 ON `Teachers2`.`id`=`Assignments`.`TeacherID2` "
				],
				'LessonID' => [
					'parent-table' => 'Lessons',
					'parent-primary-key' => 'id',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962; <span class="hidden child-label-Assignments child-field-caption">(&#924;&#940;&#952;&#951;&#956;&#945;:)</span>',
					'auto-close' => true,
					'table-icon' => 'resources/table_icons/connect.png',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => [1 => '&#932;&#956;&#942;&#956;&#945;', 3 => '&#924;&#940;&#952;&#951;&#956;&#945;', 4 => '&#913; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 5 => '&#914; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 6 => '&#915; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 7 => '&#920;&#949;&#969;&#961;./&#917;&#961;&#947;.', 8 => '&#911;&#961;&#949;&#962;', 9 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#913;', 10 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#914;', 11 => '&#932;&#949;&#955;&#949;&#965;&#964;&#945;&#943;&#945; &#964;&#961;&#959;&#960;&#959;&#960;&#959;&#943;&#951;&#963;&#951;'],
					'display-field-names' => [1 => 'ClassID', 3 => 'LessonName', 4 => 'Lesson_A_Assignment', 5 => 'Lesson_B_Assignment', 6 => 'Lesson_C_Assignment', 7 => 'LessonType', 8 => 'LessonHours', 9 => 'TeacherID', 10 => 'TeacherID2', 11 => 'datetime'],
					'sortable-fields' => [0 => '`Assignments`.`id`', 1 => '`Classes1`.`Class`', 2 => '`Lessons1`.`NameTypeHours`', 3 => 4, 4 => 5, 5 => 6, 6 => 7, 7 => 8, 8 => '`Assignments`.`LessonHours`', 9 => '`Teachers1`.`Name_Sector`', 10 => '`Teachers2`.`Name_Sector`', 11 => '`Assignments`.`datetime`'],
					'records-per-page' => 10,
					'default-sort-by' => 1,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-Assignments',
					'template-printable' => 'children-Assignments-printable',
					'query' => "SELECT `Assignments`.`id` as 'id', IF(    CHAR_LENGTH(`Classes1`.`Class`), CONCAT_WS('',   `Classes1`.`Class`), '') as 'ClassID', IF(    CHAR_LENGTH(`Lessons1`.`NameTypeHours`), CONCAT_WS('',   `Lessons1`.`NameTypeHours`), '') as 'LessonID', `Assignments`.`LessonName` as 'LessonName', `Assignments`.`Lesson_A_Assignment` as 'Lesson_A_Assignment', `Assignments`.`Lesson_B_Assignment` as 'Lesson_B_Assignment', `Assignments`.`Lesson_C_Assignment` as 'Lesson_C_Assignment', `Assignments`.`LessonType` as 'LessonType', `Assignments`.`LessonHours` as 'LessonHours', IF(    CHAR_LENGTH(`Teachers1`.`Name_Sector`), CONCAT_WS('',   `Teachers1`.`Name_Sector`), '') as 'TeacherID', IF(    CHAR_LENGTH(`Teachers2`.`Name_Sector`), CONCAT_WS('',   `Teachers2`.`Name_Sector`), '') as 'TeacherID2', if(`Assignments`.`datetime`,date_format(`Assignments`.`datetime`,'%d-%m-%Y %H:%i'),'') as 'datetime' FROM `Assignments` LEFT JOIN `Classes` as Classes1 ON `Classes1`.`id`=`Assignments`.`ClassID` LEFT JOIN `Lessons` as Lessons1 ON `Lessons1`.`id`=`Assignments`.`LessonID` LEFT JOIN `Teachers` as Teachers1 ON `Teachers1`.`id`=`Assignments`.`TeacherID` LEFT JOIN `Teachers` as Teachers2 ON `Teachers2`.`id`=`Assignments`.`TeacherID2` "
				],
				'TeacherID' => [
					'parent-table' => 'Teachers',
					'parent-primary-key' => 'id',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962; <span class="hidden child-label-Assignments child-field-caption">(&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#913;)</span>',
					'auto-close' => true,
					'table-icon' => 'resources/table_icons/connect.png',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => [1 => '&#932;&#956;&#942;&#956;&#945;', 3 => '&#924;&#940;&#952;&#951;&#956;&#945;', 4 => '&#913; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 5 => '&#914; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 6 => '&#915; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 7 => '&#920;&#949;&#969;&#961;./&#917;&#961;&#947;.', 8 => '&#911;&#961;&#949;&#962;', 9 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#913;', 10 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#914;', 11 => '&#932;&#949;&#955;&#949;&#965;&#964;&#945;&#943;&#945; &#964;&#961;&#959;&#960;&#959;&#960;&#959;&#943;&#951;&#963;&#951;'],
					'display-field-names' => [1 => 'ClassID', 3 => 'LessonName', 4 => 'Lesson_A_Assignment', 5 => 'Lesson_B_Assignment', 6 => 'Lesson_C_Assignment', 7 => 'LessonType', 8 => 'LessonHours', 9 => 'TeacherID', 10 => 'TeacherID2', 11 => 'datetime'],
					'sortable-fields' => [0 => '`Assignments`.`id`', 1 => '`Classes1`.`Class`', 2 => '`Lessons1`.`NameTypeHours`', 3 => 4, 4 => 5, 5 => 6, 6 => 7, 7 => 8, 8 => '`Assignments`.`LessonHours`', 9 => '`Teachers1`.`Name_Sector`', 10 => '`Teachers2`.`Name_Sector`', 11 => '`Assignments`.`datetime`'],
					'records-per-page' => 10,
					'default-sort-by' => 1,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-Assignments',
					'template-printable' => 'children-Assignments-printable',
					'query' => "SELECT `Assignments`.`id` as 'id', IF(    CHAR_LENGTH(`Classes1`.`Class`), CONCAT_WS('',   `Classes1`.`Class`), '') as 'ClassID', IF(    CHAR_LENGTH(`Lessons1`.`NameTypeHours`), CONCAT_WS('',   `Lessons1`.`NameTypeHours`), '') as 'LessonID', `Assignments`.`LessonName` as 'LessonName', `Assignments`.`Lesson_A_Assignment` as 'Lesson_A_Assignment', `Assignments`.`Lesson_B_Assignment` as 'Lesson_B_Assignment', `Assignments`.`Lesson_C_Assignment` as 'Lesson_C_Assignment', `Assignments`.`LessonType` as 'LessonType', `Assignments`.`LessonHours` as 'LessonHours', IF(    CHAR_LENGTH(`Teachers1`.`Name_Sector`), CONCAT_WS('',   `Teachers1`.`Name_Sector`), '') as 'TeacherID', IF(    CHAR_LENGTH(`Teachers2`.`Name_Sector`), CONCAT_WS('',   `Teachers2`.`Name_Sector`), '') as 'TeacherID2', if(`Assignments`.`datetime`,date_format(`Assignments`.`datetime`,'%d-%m-%Y %H:%i'),'') as 'datetime' FROM `Assignments` LEFT JOIN `Classes` as Classes1 ON `Classes1`.`id`=`Assignments`.`ClassID` LEFT JOIN `Lessons` as Lessons1 ON `Lessons1`.`id`=`Assignments`.`LessonID` LEFT JOIN `Teachers` as Teachers1 ON `Teachers1`.`id`=`Assignments`.`TeacherID` LEFT JOIN `Teachers` as Teachers2 ON `Teachers2`.`id`=`Assignments`.`TeacherID2` "
				],
				'TeacherID2' => [
					'parent-table' => 'Teachers',
					'parent-primary-key' => 'id',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962; <span class="hidden child-label-Assignments child-field-caption">(&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#914;)</span>',
					'auto-close' => true,
					'table-icon' => 'resources/table_icons/connect.png',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => [1 => '&#932;&#956;&#942;&#956;&#945;', 3 => '&#924;&#940;&#952;&#951;&#956;&#945;', 4 => '&#913; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 5 => '&#914; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 6 => '&#915; &#913;&#957;&#940;&#952;&#949;&#963;&#951;', 7 => '&#920;&#949;&#969;&#961;./&#917;&#961;&#947;.', 8 => '&#911;&#961;&#949;&#962;', 9 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#913;', 10 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962; &#914;', 11 => '&#932;&#949;&#955;&#949;&#965;&#964;&#945;&#943;&#945; &#964;&#961;&#959;&#960;&#959;&#960;&#959;&#943;&#951;&#963;&#951;'],
					'display-field-names' => [1 => 'ClassID', 3 => 'LessonName', 4 => 'Lesson_A_Assignment', 5 => 'Lesson_B_Assignment', 6 => 'Lesson_C_Assignment', 7 => 'LessonType', 8 => 'LessonHours', 9 => 'TeacherID', 10 => 'TeacherID2', 11 => 'datetime'],
					'sortable-fields' => [0 => '`Assignments`.`id`', 1 => '`Classes1`.`Class`', 2 => '`Lessons1`.`NameTypeHours`', 3 => 4, 4 => 5, 5 => 6, 6 => 7, 7 => 8, 8 => '`Assignments`.`LessonHours`', 9 => '`Teachers1`.`Name_Sector`', 10 => '`Teachers2`.`Name_Sector`', 11 => '`Assignments`.`datetime`'],
					'records-per-page' => 10,
					'default-sort-by' => 1,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-Assignments',
					'template-printable' => 'children-Assignments-printable',
					'query' => "SELECT `Assignments`.`id` as 'id', IF(    CHAR_LENGTH(`Classes1`.`Class`), CONCAT_WS('',   `Classes1`.`Class`), '') as 'ClassID', IF(    CHAR_LENGTH(`Lessons1`.`NameTypeHours`), CONCAT_WS('',   `Lessons1`.`NameTypeHours`), '') as 'LessonID', `Assignments`.`LessonName` as 'LessonName', `Assignments`.`Lesson_A_Assignment` as 'Lesson_A_Assignment', `Assignments`.`Lesson_B_Assignment` as 'Lesson_B_Assignment', `Assignments`.`Lesson_C_Assignment` as 'Lesson_C_Assignment', `Assignments`.`LessonType` as 'LessonType', `Assignments`.`LessonHours` as 'LessonHours', IF(    CHAR_LENGTH(`Teachers1`.`Name_Sector`), CONCAT_WS('',   `Teachers1`.`Name_Sector`), '') as 'TeacherID', IF(    CHAR_LENGTH(`Teachers2`.`Name_Sector`), CONCAT_WS('',   `Teachers2`.`Name_Sector`), '') as 'TeacherID2', if(`Assignments`.`datetime`,date_format(`Assignments`.`datetime`,'%d-%m-%Y %H:%i'),'') as 'datetime' FROM `Assignments` LEFT JOIN `Classes` as Classes1 ON `Classes1`.`id`=`Assignments`.`ClassID` LEFT JOIN `Lessons` as Lessons1 ON `Lessons1`.`id`=`Assignments`.`LessonID` LEFT JOIN `Teachers` as Teachers1 ON `Teachers1`.`id`=`Assignments`.`TeacherID` LEFT JOIN `Teachers` as Teachers2 ON `Teachers2`.`id`=`Assignments`.`TeacherID2` "
				],
			],
			'Teachers' => [
				'SectorID' => [
					'parent-table' => 'Sectors',
					'parent-primary-key' => 'id',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943; <span class="hidden child-label-Teachers child-field-caption">(&#932;&#959;&#956;&#941;&#945;&#962;)</span>',
					'auto-close' => true,
					'table-icon' => 'resources/table_icons/group.png',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => [1 => '&#932;&#959;&#956;&#941;&#945;&#962;', 2 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962;', 4 => 'Name2', 5 => '&#932;&#959;&#960;&#959;&#952;&#941;&#964;&#951;&#963;&#951;', 6 => '&#913;&#957;&#940;&#955;&#951;&#968;&#951; &#933;&#960;&#951;&#961;&#949;&#963;&#943;&#945;&#962;', 7 => '&#933;&#960;&#959;&#967;&#961;&#949;&#969;&#964;&#953;&#954;&#941;&#962;', 9 => '&#920;&#949;&#969;&#961;&#943;&#949;&#962;', 10 => '&#917;&#961;&#947;&#945;&#963;&#964;&#942;&#961;&#953;&#945;', 11 => '&#931;&#973;&#957;&#959;&#955;&#959;', 12 => '&#916;&#953;&#945;&#966;&#959;&#961;&#940;'],
					'display-field-names' => [1 => 'SectorID', 2 => 'Name_Sector', 4 => 'Name2', 5 => 'Placement', 6 => 'Assumption_Date', 7 => 'Mandatory_Hours', 9 => 'Assigned_Hours_Theory', 10 => 'Assigned_Hours_Lab', 11 => 'Assigned_Hours', 12 => 'Diff'],
					'sortable-fields' => [0 => '`Teachers`.`id`', 1 => '`Sectors1`.`Sector`', 2 => 3, 3 => 4, 4 => '`TeachersName1`.`Name`', 5 => 6, 6 => '`Teachers`.`Assumption_Date`', 7 => '`Teachers`.`Mandatory_Hours`', 8 => 9, 9 => '`Teachers`.`Assigned_Hours_Theory`', 10 => '`Teachers`.`Assigned_Hours_Lab`', 11 => '`Teachers`.`Assigned_Hours`', 12 => '`Teachers`.`Diff`'],
					'records-per-page' => 10,
					'default-sort-by' => 3,
					'default-sort-direction' => 'asc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-Teachers',
					'template-printable' => 'children-Teachers-printable',
					'query' => "SELECT `Teachers`.`id` as 'id', IF(    CHAR_LENGTH(`Sectors1`.`Sector`), CONCAT_WS('',   `Sectors1`.`Sector`), '') as 'SectorID', `Teachers`.`Name_Sector` as 'Name_Sector', `Teachers`.`Name` as 'Name', IF(    CHAR_LENGTH(`TeachersName1`.`Name`), CONCAT_WS('',   `TeachersName1`.`Name`), '') as 'Name2', `Teachers`.`Placement` as 'Placement', DATE_FORMAT(`Teachers`.`Assumption_Date`, '%d/%m/%Y') as 'Assumption_Date', `Teachers`.`Mandatory_Hours` as 'Mandatory_Hours', `Teachers`.`Main_Sector` as 'Main_Sector', `Teachers`.`Assigned_Hours_Theory` as 'Assigned_Hours_Theory', `Teachers`.`Assigned_Hours_Lab` as 'Assigned_Hours_Lab', `Teachers`.`Assigned_Hours` as 'Assigned_Hours', `Teachers`.`Diff` as 'Diff' FROM `Teachers` LEFT JOIN `Sectors` as Sectors1 ON `Sectors1`.`id`=`Teachers`.`SectorID` LEFT JOIN `TeachersName` as TeachersName1 ON `TeachersName1`.`id`=`Teachers`.`Name2` "
				],
			],
			'Lessons' => [
			],
			'Classes' => [
			],
			'Sectors' => [
			],
			'Heads' => [
			],
			'Projectors_timetable' => [
				'teacher_id' => [
					'parent-table' => 'TeachersName',
					'parent-primary-key' => 'id',
					'child-primary-key' => 'id',
					'child-primary-key-index' => 0,
					'tab-label' => '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957;  <span class="hidden child-label-Projectors_timetable child-field-caption">(&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962;)</span>',
					'auto-close' => true,
					'table-icon' => 'resources/table_icons/timetable.png',
					'display-refresh' => true,
					'display-add-new' => true,
					'forced-where' => '',
					'display-fields' => [1 => '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962;', 2 => '&#919;&#956;&#941;&#961;&#945;', 3 => '&#911;&#961;&#945;', 4 => '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#945;&#962;'],
					'display-field-names' => [1 => 'teacher_id', 2 => 'date', 3 => 'hour_id', 4 => 'projector_id'],
					'sortable-fields' => [0 => '`Projectors_timetable`.`id`', 1 => '`TeachersName1`.`Name`', 2 => '`Projectors_timetable`.`date`', 3 => '`Hours1`.`name`', 4 => '`Projectors1`.`name`', 5 => '`Projectors_timetable`.`star_time`', 6 => '`Projectors_timetable`.`end_time`'],
					'records-per-page' => 10,
					'default-sort-by' => 2,
					'default-sort-direction' => 'desc',
					'open-detail-view-on-click' => true,
					'display-page-selector' => true,
					'show-page-progress' => true,
					'template' => 'children-Projectors_timetable',
					'template-printable' => 'children-Projectors_timetable-printable',
					'query' => "SELECT `Projectors_timetable`.`id` as 'id', IF(    CHAR_LENGTH(`TeachersName1`.`Name`), CONCAT_WS('',   `TeachersName1`.`Name`), '') as 'teacher_id', if(`Projectors_timetable`.`date`,date_format(`Projectors_timetable`.`date`,'%d-%m-%Y'),'') as 'date', IF(    CHAR_LENGTH(`Hours1`.`name`), CONCAT_WS('',   `Hours1`.`name`), '') as 'hour_id', IF(    CHAR_LENGTH(`Projectors1`.`name`), CONCAT_WS('',   `Projectors1`.`name`), '') as 'projector_id', `Projectors_timetable`.`star_time` as 'star_time', `Projectors_timetable`.`end_time` as 'end_time' FROM `Projectors_timetable` LEFT JOIN `TeachersName` as TeachersName1 ON `TeachersName1`.`id`=`Projectors_timetable`.`teacher_id` LEFT JOIN `Hours` as Hours1 ON `Hours1`.`id`=`Projectors_timetable`.`hour_id` LEFT JOIN `Projectors` as Projectors1 ON `Projectors1`.`id`=`Projectors_timetable`.`projector_id` "
				],
			],
			'Hours' => [
			],
			'Projectors' => [
			],
			'Tests' => [
			],
			'Folders' => [
			],
			'Subfolders' => [
			],
			'Examinations' => [
			],
		];

		if($skipPermissions) return $pcConfig;

		if(!in_array($filterByPermission, ['access', 'insert', 'edit', 'delete'])) $filterByPermission = 'view';

		/**
		* dynamic configuration based on current user's permissions
		* $userPCConfig array is populated only with parent tables where the user has access to
		* at least one child table
		*/
		$userPCConfig = [];
		foreach($pcConfig as $tn => $lookupFields) {
			$perm = getTablePermissions($tn);
			if(!$perm[$filterByPermission]) continue;

			foreach($lookupFields as $fn => $ChildConfig) {
				$permParent = getTablePermissions($ChildConfig['parent-table']);
				if(!$permParent[$filterByPermission]) continue;

				$userPCConfig[$tn][$fn] = $pcConfig[$tn][$fn];
				// show add new only if configured above AND the user has insert permission
				$userPCConfig[$tn][$fn]['display-add-new'] = ($perm['insert'] && $pcConfig[$tn][$fn]['display-add-new']);
			}
		}

		return $userPCConfig;
	}

	#########################################################

	function getChildTables($parentTable, $skipPermissions = false, $filterByPermission = 'view') {
		$pcConfig = getLookupFields($skipPermissions, $filterByPermission);
		$childTables = [];
		foreach($pcConfig as $tn => $lookupFields)
			foreach($lookupFields as $fn => $ChildConfig)
				if($ChildConfig['parent-table'] == $parentTable)
					$childTables[$tn][$fn] = $ChildConfig;

		return $childTables;
	}
