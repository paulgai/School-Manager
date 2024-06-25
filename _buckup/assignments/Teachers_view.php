<?php
// This script and data application were generated by AppGini 23.15
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/Teachers.php');
	include_once(__DIR__ . '/Teachers_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('Teachers');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'Teachers';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`Teachers`.`id`" => "id",
		"IF(    CHAR_LENGTH(`Sectors1`.`Sector`), CONCAT_WS('',   `Sectors1`.`Sector`), '') /* &#932;&#959;&#956;&#941;&#945;&#962; */" => "SectorID",
		"`Teachers`.`Name_Sector`" => "Name_Sector",
		"`Teachers`.`Name`" => "Name",
		"`Teachers`.`Placement`" => "Placement",
		"DATE_FORMAT(`Teachers`.`Assumption_Date`, '%d/%m/%Y')" => "Assumption_Date",
		"`Teachers`.`Mandatory_Hours`" => "Mandatory_Hours",
		"`Teachers`.`Main_Sector`" => "Main_Sector",
		"`Teachers`.`Assigned_Hours_Theory`" => "Assigned_Hours_Theory",
		"`Teachers`.`Assigned_Hours_Lab`" => "Assigned_Hours_Lab",
		"`Teachers`.`Assigned_Hours`" => "Assigned_Hours",
		"`Teachers`.`Diff`" => "Diff",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`Teachers`.`id`',
		2 => '`Sectors1`.`Sector`',
		3 => 3,
		4 => 4,
		5 => 5,
		6 => '`Teachers`.`Assumption_Date`',
		7 => '`Teachers`.`Mandatory_Hours`',
		8 => 8,
		9 => '`Teachers`.`Assigned_Hours_Theory`',
		10 => '`Teachers`.`Assigned_Hours_Lab`',
		11 => '`Teachers`.`Assigned_Hours`',
		12 => '`Teachers`.`Diff`',
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`Teachers`.`id`" => "id",
		"IF(    CHAR_LENGTH(`Sectors1`.`Sector`), CONCAT_WS('',   `Sectors1`.`Sector`), '') /* &#932;&#959;&#956;&#941;&#945;&#962; */" => "SectorID",
		"`Teachers`.`Name_Sector`" => "Name_Sector",
		"`Teachers`.`Name`" => "Name",
		"`Teachers`.`Placement`" => "Placement",
		"DATE_FORMAT(`Teachers`.`Assumption_Date`, '%d/%m/%Y')" => "Assumption_Date",
		"`Teachers`.`Mandatory_Hours`" => "Mandatory_Hours",
		"`Teachers`.`Main_Sector`" => "Main_Sector",
		"`Teachers`.`Assigned_Hours_Theory`" => "Assigned_Hours_Theory",
		"`Teachers`.`Assigned_Hours_Lab`" => "Assigned_Hours_Lab",
		"`Teachers`.`Assigned_Hours`" => "Assigned_Hours",
		"`Teachers`.`Diff`" => "Diff",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`Teachers`.`id`" => "ID",
		"IF(    CHAR_LENGTH(`Sectors1`.`Sector`), CONCAT_WS('',   `Sectors1`.`Sector`), '') /* &#932;&#959;&#956;&#941;&#945;&#962; */" => "&#932;&#959;&#956;&#941;&#945;&#962;",
		"`Teachers`.`Name_Sector`" => "&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962;",
		"`Teachers`.`Name`" => "&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962;:",
		"`Teachers`.`Placement`" => "&#932;&#959;&#960;&#959;&#952;&#941;&#964;&#951;&#963;&#951;",
		"`Teachers`.`Assumption_Date`" => "&#913;&#957;&#940;&#955;&#951;&#968;&#951; &#933;&#960;&#951;&#961;&#949;&#963;&#943;&#945;&#962;",
		"`Teachers`.`Mandatory_Hours`" => "&#933;&#960;&#959;&#967;&#961;&#949;&#969;&#964;&#953;&#954;&#941;&#962;",
		"`Teachers`.`Main_Sector`" => "&#922;&#973;&#961;&#953;&#945; &#917;&#953;&#948;&#953;&#954;&#972;&#964;&#951;&#964;&#945;",
		"`Teachers`.`Assigned_Hours_Theory`" => "&#920;&#949;&#969;&#961;&#943;&#949;&#962;",
		"`Teachers`.`Assigned_Hours_Lab`" => "&#917;&#961;&#947;&#945;&#963;&#964;&#942;&#961;&#953;&#945;",
		"`Teachers`.`Assigned_Hours`" => "&#931;&#973;&#957;&#959;&#955;&#959;",
		"`Teachers`.`Diff`" => "&#916;&#953;&#945;&#966;&#959;&#961;&#940;",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`Teachers`.`id`" => "id",
		"IF(    CHAR_LENGTH(`Sectors1`.`Sector`), CONCAT_WS('',   `Sectors1`.`Sector`), '') /* &#932;&#959;&#956;&#941;&#945;&#962; */" => "SectorID",
		"`Teachers`.`Name_Sector`" => "Name_Sector",
		"`Teachers`.`Name`" => "Name",
		"`Teachers`.`Placement`" => "Placement",
		"DATE_FORMAT(`Teachers`.`Assumption_Date`, '%d/%m/%Y')" => "Assumption_Date",
		"`Teachers`.`Mandatory_Hours`" => "Mandatory_Hours",
		"`Teachers`.`Main_Sector`" => "Main_Sector",
		"`Teachers`.`Assigned_Hours_Theory`" => "Assigned_Hours_Theory",
		"`Teachers`.`Assigned_Hours_Lab`" => "Assigned_Hours_Lab",
		"`Teachers`.`Assigned_Hours`" => "Assigned_Hours",
		"`Teachers`.`Diff`" => "Diff",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = ['SectorID' => '&#932;&#959;&#956;&#941;&#945;&#962;', ];

	$x->QueryFrom = "`Teachers` LEFT JOIN `Sectors` as Sectors1 ON `Sectors1`.`id`=`Teachers`.`SectorID` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm['view'] == 0 ? 1 : 0);
	$x->AllowDelete = $perm['delete'];
	$x->AllowMassDelete = (getLoggedAdmin() !== false);
	$x->AllowInsert = $perm['insert'];
	$x->AllowUpdate = $perm['edit'];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = (getLoggedAdmin() !== false);
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 100;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation['quick search'];
	$x->ScriptFileName = 'Teachers_view.php';
	$x->TableTitle = '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;';
	$x->TableIcon = 'resources/table_icons/group.png';
	$x->PrimaryKey = '`Teachers`.`id`';
	$x->DefaultSortField = '4';
	$x->DefaultSortDirection = 'asc';

	$x->ColWidth = [150, 150, 150, 150, 150, 150, 150, 150, 150, 100, ];
	$x->ColCaption = ['&#932;&#959;&#956;&#941;&#945;&#962;', '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962;', '&#932;&#959;&#960;&#959;&#952;&#941;&#964;&#951;&#963;&#951;', '&#913;&#957;&#940;&#955;&#951;&#968;&#951; &#933;&#960;&#951;&#961;&#949;&#963;&#943;&#945;&#962;', '&#933;&#960;&#959;&#967;&#961;&#949;&#969;&#964;&#953;&#954;&#941;&#962;', '&#920;&#949;&#969;&#961;&#943;&#949;&#962;', '&#917;&#961;&#947;&#945;&#963;&#964;&#942;&#961;&#953;&#945;', '&#931;&#973;&#957;&#959;&#955;&#959;', '&#916;&#953;&#945;&#966;&#959;&#961;&#940;', 'Αναθέσεις', ];
	$x->ColFieldName = ['SectorID', 'Name_Sector', 'Placement', 'Assumption_Date', 'Mandatory_Hours', 'Assigned_Hours_Theory', 'Assigned_Hours_Lab', 'Assigned_Hours', 'Diff', '%Assignments.TeacherID%', ];
	$x->ColNumber  = [2, 3, 5, 6, 7, 9, 10, 11, 12, -1, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/Teachers_templateTV.html';
	$x->SelectedTemplate = 'templates/Teachers_templateTVS.html';
	$x->TemplateDV = 'templates/Teachers_templateDV.html';
	$x->TemplateDVP = 'templates/Teachers_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = true;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: Teachers_init
	$render = true;
	if(function_exists('Teachers_init')) {
		$args = [];
		$render = Teachers_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// column sums
	if(strpos($x->HTML, '<!-- tv data below -->')) {
		// if printing multi-selection TV, calculate the sum only for the selected records
		$record_selector = Request::val('record_selector');
		if(Request::val('Print_x') && is_array($record_selector)) {
			$QueryWhere = '';
			foreach($record_selector as $id) {   // get selected records
				if($id != '') $QueryWhere .= "'" . makeSafe($id) . "',";
			}
			if($QueryWhere != '') {
				$QueryWhere = 'where `Teachers`.`id` in ('.substr($QueryWhere, 0, -1).')';
			} else { // if no selected records, write the where clause to return an empty result
				$QueryWhere = 'where 1=0';
			}
		} else {
			$QueryWhere = $x->QueryWhere;
		}

		$sumQuery = "SELECT SUM(`Teachers`.`Mandatory_Hours`), SUM(`Teachers`.`Assigned_Hours_Theory`), SUM(`Teachers`.`Assigned_Hours_Lab`), SUM(`Teachers`.`Assigned_Hours`), SUM(`Teachers`.`Diff`) FROM {$x->QueryFrom} {$QueryWhere}";
		$res = sql($sumQuery, $eo);
		if($row = db_fetch_row($res)) {
			$sumRow = '<tr class="success sum">';
			if(!Request::val('Print_x')) $sumRow .= '<th class="text-center sum">&sum;</th>';
			$sumRow .= '<td class="Teachers-SectorID sum"></td>';
			$sumRow .= '<td class="Teachers-Name_Sector sum"></td>';
			$sumRow .= '<td class="Teachers-Placement sum"></td>';
			$sumRow .= '<td class="Teachers-Assumption_Date sum"></td>';
			$sumRow .= "<td class=\"Teachers-Mandatory_Hours text-right sum locale-int\">{$row[0]}</td>";
			$sumRow .= "<td class=\"Teachers-Assigned_Hours_Theory text-right sum locale-int\">{$row[1]}</td>";
			$sumRow .= "<td class=\"Teachers-Assigned_Hours_Lab text-right sum locale-int\">{$row[2]}</td>";
			$sumRow .= "<td class=\"Teachers-Assigned_Hours text-right sum locale-int\">{$row[3]}</td>";
			$sumRow .= "<td class=\"Teachers-Diff text-right sum locale-int\">{$row[4]}</td>";
			$sumRow .= '</tr>';

			$x->HTML = str_replace('<!-- tv data below -->', '', $x->HTML);
			$x->HTML = str_replace('<!-- tv data above -->', $sumRow, $x->HTML);
		}
	}

	// hook: Teachers_header
	$headerCode = '';
	if(function_exists('Teachers_header')) {
		$args = [];
		$headerCode = Teachers_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: Teachers_footer
	$footerCode = '';
	if(function_exists('Teachers_footer')) {
		$args = [];
		$footerCode = Teachers_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
