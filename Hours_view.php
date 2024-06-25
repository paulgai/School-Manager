<?php
// This script and data application was generated by AppGini, https://bigprof.com/appgini
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/Hours.php');
	include_once(__DIR__ . '/Hours_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('Hours');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'Hours';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`Hours`.`id`" => "id",
		"`Hours`.`name`" => "name",
		"`Hours`.`start_time`" => "start_time",
		"`Hours`.`end_time`" => "end_time",
		"`Hours`.`start_end_text`" => "start_end_text",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`Hours`.`id`',
		2 => 2,
		3 => '`Hours`.`start_time`',
		4 => '`Hours`.`end_time`',
		5 => 5,
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`Hours`.`id`" => "id",
		"`Hours`.`name`" => "name",
		"`Hours`.`start_time`" => "start_time",
		"`Hours`.`end_time`" => "end_time",
		"`Hours`.`start_end_text`" => "start_end_text",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`Hours`.`id`" => "ID",
		"`Hours`.`name`" => "&#911;&#961;&#945;",
		"`Hours`.`start_time`" => "&#904;&#957;&#945;&#961;&#958;&#951;",
		"`Hours`.`end_time`" => "&#923;&#942;&#958;&#951;",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`Hours`.`id`" => "id",
		"`Hours`.`name`" => "name",
		"`Hours`.`start_time`" => "start_time",
		"`Hours`.`end_time`" => "end_time",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = [];

	$x->QueryFrom = "`Hours` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 0;
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
	$x->AllowPrintingDV = (getLoggedAdmin() !== false);
	$x->AllowCSV = 1;
	$x->AllowAdminShowSQL = 0;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation['quick search'];
	$x->ScriptFileName = 'Hours_view.php';
	$x->TableTitle = '&#911;&#961;&#949;&#962;';
	$x->TableIcon = 'resources/table_icons/clock_.png';
	$x->PrimaryKey = '`Hours`.`id`';
	$x->DefaultSortField = '2';
	$x->DefaultSortDirection = 'asc';

	$x->ColWidth = [150, 150, 150, ];
	$x->ColCaption = ['&#911;&#961;&#945;', '&#904;&#957;&#945;&#961;&#958;&#951;', '&#923;&#942;&#958;&#951;', ];
	$x->ColFieldName = ['name', 'start_time', 'end_time', ];
	$x->ColNumber  = [2, 3, 4, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/Hours_templateTV.html';
	$x->SelectedTemplate = 'templates/Hours_templateTVS.html';
	$x->TemplateDV = 'templates/Hours_templateDV.html';
	$x->TemplateDVP = 'templates/Hours_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = true;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: Hours_init
	$render = true;
	if(function_exists('Hours_init')) {
		$args = [];
		$render = Hours_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: Hours_header
	$headerCode = '';
	if(function_exists('Hours_header')) {
		$args = [];
		$headerCode = Hours_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: Hours_footer
	$footerCode = '';
	if(function_exists('Hours_footer')) {
		$args = [];
		$footerCode = Hours_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}