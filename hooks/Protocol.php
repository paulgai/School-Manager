<?php
// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

function Protocol_init(&$options, $memberInfo, &$args)
{

	return TRUE;
}

function Protocol_header($contentType, $memberInfo, &$args)
{
	$header = '';

	switch ($contentType) {
		case 'tableview':

			$header = '';
			break;

		case 'detailview':
			$header = '';
			break;

		case 'tableview+detailview':
			$header = '';
			break;

		case 'print-tableview':
			$header = '';
			break;

		case 'print-detailview':
			$header = '';
			break;

		case 'filters':
			$header = '';
			break;
	}

	return $header;
}

function Protocol_footer($contentType, $memberInfo, &$args)
{
	$footer = '';

	switch ($contentType) {
		case 'tableview':
			echo "<style>.select2-chosen {
				max-width: 120px; 
				white-space: nowrap; 
				overflow: hidden; 
				text-overflow: ellipsis; 
			}</style>";
			$footer = '';
			break;

		case 'detailview':
			$footer = '';
			break;

		case 'tableview+detailview':
			$footer = '';
			break;

		case 'print-tableview':
			$footer = '';
			break;

		case 'print-detailview':
			$footer = '';
			break;

		case 'filters':
			$footer = '';
			break;
	}

	return $footer;
}

function Protocol_before_insert(&$data, $memberInfo, &$args)
{
	sql("LOCK TABLES `Protocol` WRITE", $eo);
	$max = sqlValue("SELECT MAX(`Protocol`.`serial_number`) FROM `Protocol`", $eo);
	if ($max > 0) {
		$data["serial_number"] = $max + 1;
	} else {
		$data["serial_number"] = 1;
	}


	return TRUE;
}

function Protocol_after_insert($data, $memberInfo, &$args)
{
	sql("UNLOCK TABLES", $eo);
	return TRUE;
}

function Protocol_before_update(&$data, $memberInfo, &$args)
{

	return TRUE;
}

function Protocol_after_update($data, $memberInfo, &$args)
{

	return TRUE;
}

function Protocol_before_delete($selectedID, &$skipChecks, $memberInfo, &$args)
{

	return TRUE;
}

function Protocol_after_delete($selectedID, $memberInfo, &$args)
{
}

function Protocol_dv($selectedID, $memberInfo, &$html, &$args)
{
	$html .= "<style>.select2-chosen {
		max-width: 240px; 
		white-space: nowrap; 
		overflow: hidden; 
		text-overflow: ellipsis; 
	}</style>";
}

function Protocol_csv($query, $memberInfo, &$args)
{

	return $query;
}
function Protocol_batch_actions(&$args)
{

	return [];
}
