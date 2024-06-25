<?php
	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function Folders_init(&$options, $memberInfo, &$args) {

		return TRUE;
	}

	function Folders_header($contentType, $memberInfo, &$args) {
		$header='';

		switch($contentType) {
			case 'tableview':
				$header='';
				break;

			case 'detailview':
				$header='';
				break;

			case 'tableview+detailview':
				$header='';
				break;

			case 'print-tableview':
				$header='';
				break;

			case 'print-detailview':
				$header='';
				break;

			case 'filters':
				$header='';
				break;
		}

		return $header;
	}

	function Folders_footer($contentType, $memberInfo, &$args) {
		$footer='';

		switch($contentType) {
			case 'tableview':
				$footer='';
				break;

			case 'detailview':
				$footer='';
				break;

			case 'tableview+detailview':
				$footer='';
				break;

			case 'print-tableview':
				$footer='';
				break;

			case 'print-detailview':
				$footer='';
				break;

			case 'filters':
				$footer='';
				break;
		}

		return $footer;
	}

	function Folders_before_insert(&$data, $memberInfo, &$args) {

		return TRUE;
	}

	function Folders_after_insert($data, $memberInfo, &$args) {

		return TRUE;
	}

	function Folders_before_update(&$data, $memberInfo, &$args) {

		return TRUE;
	}

	function Folders_after_update($data, $memberInfo, &$args) {

		return TRUE;
	}

	function Folders_before_delete($selectedID, &$skipChecks, $memberInfo, &$args) {

		return TRUE;
	}

	function Folders_after_delete($selectedID, $memberInfo, &$args) {

	}

	function Folders_dv($selectedID, $memberInfo, &$html, &$args) {

	}

	function Folders_csv($query, $memberInfo, &$args) {

		return $query;
	}
	function Folders_batch_actions(&$args) {

		return [];
	}
