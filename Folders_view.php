<?php
// This script and data application was generated by AppGini, https://bigprof.com/appgini
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/Folders.php');
	include_once(__DIR__ . '/Folders_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('Folders');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'Folders';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`Folders`.`id`" => "id",
		"`Folders`.`folder`" => "folder",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`Folders`.`id`',
		2 => 2,
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`Folders`.`id`" => "id",
		"`Folders`.`folder`" => "folder",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`Folders`.`id`" => "ID",
		"`Folders`.`folder`" => "&#934;&#940;&#954;&#949;&#955;&#959;&#962;",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`Folders`.`id`" => "id",
		"`Folders`.`folder`" => "folder",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = [];

	$x->QueryFrom = "`Folders` ";
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
	$x->AllowAdminShowSQL = 0;
	$x->RecordsPerPage = 50;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation['quick search'];
	$x->ScriptFileName = 'Folders_view.php';
	$x->TableTitle = '&#934;&#940;&#954;&#949;&#955;&#959;&#953;';
	$x->TableIcon = 'resources/table_icons/folder.png';
	$x->PrimaryKey = '`Folders`.`id`';
	$x->DefaultSortField = '1';
	$x->DefaultSortDirection = 'asc';

	$x->ColWidth = [150, ];
	$x->ColCaption = ['&#934;&#940;&#954;&#949;&#955;&#959;&#962;', ];
	$x->ColFieldName = ['folder', ];
	$x->ColNumber  = [2, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/Folders_templateTV.html';
	$x->SelectedTemplate = 'templates/Folders_templateTVS.html';
	$x->TemplateDV = 'templates/Folders_templateDV.html';
	$x->TemplateDVP = 'templates/Folders_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: Folders_init
	$render = true;
	if(function_exists('Folders_init')) {
		$args = [];
		$render = Folders_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: Folders_header
	$headerCode = '';
	if(function_exists('Folders_header')) {
		$args = [];
		$headerCode = Folders_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: Folders_footer
	$footerCode = '';
	if(function_exists('Folders_footer')) {
		$args = [];
		$footerCode = Folders_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
