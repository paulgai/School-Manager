<?php
	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function Lessons_init(&$options, $memberInfo, &$args) {
		/* Inserted by Search Page Maker for AppGini on 2022-11-18 09:27:19 */
		$options->FilterPage = 'hooks/Lessons_filter.php';
		/* End of Search Page Maker for AppGini code */


		return TRUE;
	}

	function Lessons_header($contentType, $memberInfo, &$args) {
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

	function Lessons_footer($contentType, $memberInfo, &$args) {
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

	function Lessons_before_insert(&$data, $memberInfo, &$args) {

		return TRUE;
	}

	function Lessons_after_insert($data, $memberInfo, &$args) {

		return TRUE;
	}

	function Lessons_before_update(&$data, $memberInfo, &$args) {

		return TRUE;
	}

	function Lessons_after_update($data, $memberInfo, &$args) {

		return TRUE;
	}

	function Lessons_before_delete($selectedID, &$skipChecks, $memberInfo, &$args) {

		return TRUE;
	}

	function Lessons_after_delete($selectedID, $memberInfo, &$args) {

	}

	function Lessons_dv($selectedID, $memberInfo, &$html, &$args) {

	}

	function Lessons_csv($query, $memberInfo, &$args) {

		return $query;
	}
	function Lessons_batch_actions(&$args) {
		/* Inserted by Mass Update on 2023-09-11 08:59:29 */
		
		/*
		 * Q: How do I return other custom batch commands not defined in mass_update plugin?
		 * 
		 * A: Define your commands ABOVE the 'Inserted by Mass Update' comment above 
		 * in an array named $custom_actions_top to display them above the commands 
		 * created by the mass_update plugin.
		 * 
		 * You can also define commands in an array named $custom_actions_bottom
		 * (also ABOVE the 'Inserted by Mass Update' comment block) to display them 
		 * below the commands created by the mass_update plugin.
		 * 
		*/

		if(!isset($custom_actions_top) || !is_array($custom_actions_top))
			$custom_actions_top = [];

		if(!isset($custom_actions_bottom) || !is_array($custom_actions_bottom))
			$custom_actions_bottom = [];

		$command = [
			'aw1zcpxp49k645zk327n' => [
				'title' => "Lesson Type",
				'function' => 'massUpdateCommand_aw1zcpxp49k645zk327n',
				'icon' => 'plus'
			],
		];

		$mi = getMemberInfo();
		switch($mi['group']) {
			case 'Admins':
				return array_merge(
					$custom_actions_top,
					[
						$command['aw1zcpxp49k645zk327n']
					],
					$custom_actions_bottom
				);
		}


		/* End of Mass Update code */


		return [];
	}
