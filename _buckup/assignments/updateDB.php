<?php
	// check this file's MD5 to make sure it wasn't called before
	$tenantId = Authentication::tenantIdPadded();
	$setupHash = __DIR__ . "/setup{$tenantId}.md5";

	$prevMD5 = @file_get_contents($setupHash);
	$thisMD5 = md5_file(__FILE__);

	// check if this setup file already run
	if($thisMD5 != $prevMD5) {
		// set up tables
		setupTable(
			'Assignments', " 
			CREATE TABLE IF NOT EXISTS `Assignments` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`ClassID` INT UNSIGNED NOT NULL,
				`LessonID` INT UNSIGNED NOT NULL,
				`LessonName` VARCHAR(130) NULL,
				`Lesson_A_Assignment` VARCHAR(255) NULL,
				`Lesson_B_Assignment` VARCHAR(255) NULL,
				`Lesson_C_Assignment` VARCHAR(255) NULL,
				`LessonType` VARCHAR(12) NULL,
				`LessonHours` INT NULL,
				`TeacherID` INT UNSIGNED NULL,
				`TeacherID2` INT UNSIGNED NULL,
				`datetime` DATETIME NULL
			) CHARSET utf8mb4"
		);
		setupIndexes('Assignments', ['ClassID','LessonID','TeacherID','TeacherID2',]);

		setupTable(
			'Teachers', " 
			CREATE TABLE IF NOT EXISTS `Teachers` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`SectorID` INT UNSIGNED NULL,
				`Name_Sector` VARCHAR(50) NULL,
				`Name` VARCHAR(40) NULL,
				`Placement` VARCHAR(50) NULL,
				`Assumption_Date` DATE NULL,
				`Mandatory_Hours` INT NULL DEFAULT '0',
				`Main_Sector` VARCHAR(100) NULL,
				`Assigned_Hours_Theory` INT NULL,
				`Assigned_Hours_Lab` INT NULL,
				`Assigned_Hours` INT NULL,
				`Diff` INT NULL
			) CHARSET utf8mb4"
		);
		setupIndexes('Teachers', ['SectorID',]);

		setupTable(
			'Lessons', " 
			CREATE TABLE IF NOT EXISTS `Lessons` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`Class` VARCHAR(1) NOT NULL,
				`Name` VARCHAR(100) NOT NULL,
				`Type` VARCHAR(20) NOT NULL,
				`Hours` INT NOT NULL,
				`NameTypeHours` VARCHAR(130) NULL,
				`A_assignment` TEXT NULL,
				`B_assignment` TEXT NULL,
				`C_assignment` TEXT NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'Classes', " 
			CREATE TABLE IF NOT EXISTS `Classes` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`Class` VARCHAR(50) NULL,
				`Students_Number` INT NULL,
				`Type` VARCHAR(40) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'Sectors', " 
			CREATE TABLE IF NOT EXISTS `Sectors` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`Sector` VARCHAR(50) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'Heads', " 
			CREATE TABLE IF NOT EXISTS `Heads` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`teacher_id` INT UNSIGNED NULL,
				`class_id` INT UNSIGNED NULL
			) CHARSET utf8mb4"
		);
		setupIndexes('Heads', ['teacher_id','class_id',]);

		setupTable(
			'Projectors_timetable', " 
			CREATE TABLE IF NOT EXISTS `Projectors_timetable` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`teacher_id` INT UNSIGNED NOT NULL,
				`date` DATE NOT NULL,
				`hour_id` INT UNSIGNED NOT NULL,
				`projector_id` INT UNSIGNED NULL,
				`star_time` TIME NULL,
				`end_time` TIME NULL
			) CHARSET utf8mb4"
		);
		setupIndexes('Projectors_timetable', ['teacher_id','hour_id','projector_id',]);

		setupTable(
			'Hours', " 
			CREATE TABLE IF NOT EXISTS `Hours` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`name` VARCHAR(40) NULL,
				`start_time` TIME NULL,
				`end_time` TIME NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'Projectors', " 
			CREATE TABLE IF NOT EXISTS `Projectors` ( 
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`),
				`img` VARCHAR(40) NULL,
				`name` VARCHAR(40) NULL
			) CHARSET utf8mb4"
		);



		// save MD5
		@file_put_contents($setupHash, $thisMD5);
	}


	function setupIndexes($tableName, $arrFields) {
		if(!is_array($arrFields) || !count($arrFields)) return false;

		foreach($arrFields as $fieldName) {
			if(!$res = @db_query("SHOW COLUMNS FROM `$tableName` like '$fieldName'")) continue;
			if(!$row = @db_fetch_assoc($res)) continue;
			if($row['Key']) continue;

			@db_query("ALTER TABLE `$tableName` ADD INDEX `$fieldName` (`$fieldName`)");
		}
	}


	function setupTable($tableName, $createSQL = '', $arrAlter = '') {
		global $Translation;
		$oldTableName = '';
		ob_start();

		echo '<div style="padding: 5px; border-bottom:solid 1px silver; font-family: verdana, arial; font-size: 10px;">';

		// is there a table rename query?
		if(is_array($arrAlter)) {
			$matches = [];
			if(preg_match("/ALTER TABLE `(.*)` RENAME `$tableName`/i", $arrAlter[0], $matches)) {
				$oldTableName = $matches[1];
			}
		}

		if($res = @db_query("SELECT COUNT(1) FROM `$tableName`")) { // table already exists
			if($row = @db_fetch_array($res)) {
				echo str_replace(['<TableName>', '<NumRecords>'], [$tableName, $row[0]], $Translation['table exists']);
				if(is_array($arrAlter)) {
					echo '<br>';
					foreach($arrAlter as $alter) {
						if($alter != '') {
							echo "$alter ... ";
							if(!@db_query($alter)) {
								echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
								echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
							} else {
								echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
							}
						}
					}
				} else {
					echo $Translation['table uptodate'];
				}
			} else {
				echo str_replace('<TableName>', $tableName, $Translation['couldnt count']);
			}
		} else { // given tableName doesn't exist

			if($oldTableName != '') { // if we have a table rename query
				if($ro = @db_query("SELECT COUNT(1) FROM `$oldTableName`")) { // if old table exists, rename it.
					$renameQuery = array_shift($arrAlter); // get and remove rename query

					echo "$renameQuery ... ";
					if(!@db_query($renameQuery)) {
						echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
						echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
					} else {
						echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
					}

					if(is_array($arrAlter)) setupTable($tableName, $createSQL, false, $arrAlter); // execute Alter queries on renamed table ...
				} else { // if old tableName doesn't exist (nor the new one since we're here), then just create the table.
					setupTable($tableName, $createSQL, false); // no Alter queries passed ...
				}
			} else { // tableName doesn't exist and no rename, so just create the table
				echo str_replace("<TableName>", $tableName, $Translation["creating table"]);
				if(!@db_query($createSQL)) {
					echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
					echo '<div class="text-danger">' . $Translation['mysql said'] . db_error(db_link()) . '</div>';

					// create table with a dummy field
					@db_query("CREATE TABLE IF NOT EXISTS `$tableName` (`_dummy_deletable_field` TINYINT)");
				} else {
					echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
				}
			}

			// set Admin group permissions for newly created table if membership_grouppermissions exists
			if($ro = @db_query("SELECT COUNT(1) FROM `membership_grouppermissions`")) {
				// get Admins group id
				$ro = @db_query("SELECT `groupID` FROM `membership_groups` WHERE `name`='Admins'");
				if($ro) {
					$adminGroupID = intval(db_fetch_row($ro)[0]);
					if($adminGroupID) @db_query("INSERT IGNORE INTO `membership_grouppermissions` SET
						`groupID`='$adminGroupID',
						`tableName`='$tableName',
						`allowInsert`=1, `allowView`=1, `allowEdit`=1, `allowDelete`=1
					");
				}
			}
		}

		echo '</div>';

		$out = ob_get_clean();
		if(defined('APPGINI_SETUP') && APPGINI_SETUP) echo $out;
	}
