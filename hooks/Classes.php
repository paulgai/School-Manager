<?php
// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

function Classes_init(&$options, $memberInfo, &$args)
{

	return TRUE;
}

function Classes_header($contentType, $memberInfo, &$args)
{
	$header = '';

	switch ($contentType) {
		case 'tableview':
			$header = '';
			break;

		case 'detailview':
			$header = '';
			echo '<script>
				var checkExist = setInterval(function () {
					//console.log("ready!");
					if ($j(".Assignments-Lesson_A_Assignment").length) {
					  console.log("Table Loaded");
					  $j(".Assignments-Lesson_A_Assignment").remove();
					  //clearInterval(checkExist);
					}
					if ($j(".Assignments-Lesson_B_Assignment").length) {
					  console.log("Table Loaded");
					  $j(".Assignments-Lesson_B_Assignment").remove();
					  //clearInterval(checkExist);
					}
					if ($j(".Assignments-Lesson_C_Assignment").length) {
					  console.log("Table Loaded");
					  $j(".Assignments-Lesson_C_Assignment").remove();
					  //clearInterval(checkExist);
					}
				  }, 100);
				  
				</script>';
			break;

		case 'tableview+detailview':
			$header = '';
			break;

		case 'print-tableview':
			$header = '';
			break;

		case 'print-detailview':
			$header = '';
			echo '<script>
				var checkExist = setInterval(function () {
					//console.log("ready!");
					if ($j(".Assignments-Lesson_A_Assignment").length) {
					  console.log("Table Loaded");
					  $j(".Assignments-Lesson_A_Assignment").remove();
					  //clearInterval(checkExist);
					}
					if ($j(".Assignments-Lesson_B_Assignment").length) {
					  console.log("Table Loaded");
					  $j(".Assignments-Lesson_B_Assignment").remove();
					  //clearInterval(checkExist);
					}
					if ($j(".Assignments-Lesson_C_Assignment").length) {
					  console.log("Table Loaded");
					  $j(".Assignments-Lesson_C_Assignment").remove();
					  //clearInterval(checkExist);
					}
				  }, 100);
				  
				</script>';
			break;

		case 'filters':
			$header = '';
			break;
	}

	return $header;
}

function Classes_footer($contentType, $memberInfo, &$args)
{
	$footer = '';

	switch ($contentType) {
		case 'tableview':
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

function Classes_before_insert(&$data, $memberInfo, &$args)
{

	return TRUE;
}

function Classes_after_insert($data, $memberInfo, &$args)
{

	return TRUE;
}

function Classes_before_update(&$data, $memberInfo, &$args)
{

	return TRUE;
}

function Classes_after_update($data, $memberInfo, &$args)
{

	return TRUE;
}

function Classes_before_delete($selectedID, &$skipChecks, $memberInfo, &$args)
{

	return TRUE;
}

function Classes_after_delete($selectedID, $memberInfo, &$args)
{
}

function Classes_dv($selectedID, $memberInfo, &$html, &$args)
{
}

function Classes_csv($query, $memberInfo, &$args)
{

	return $query;
}
function Classes_batch_actions(&$args)
{
	/* Inserted by Mass Update on 2022-11-28 07:40:06 */

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

	if (!isset($custom_actions_top) || !is_array($custom_actions_top))
		$custom_actions_top = [];

	if (!isset($custom_actions_bottom) || !is_array($custom_actions_bottom))
		$custom_actions_bottom = [];

	$command = [
		'stly0mfa8sqnoq4hegzb' => [
			'title' => "test",
			'function' => 'massUpdateCommand_stly0mfa8sqnoq4hegzb',
			'icon' => 'asterisk'
		],
	];

	$mi = getMemberInfo();
	switch ($mi['group']) {
		default:
			/* for all other logged users, enable the following commands */
			if ($mi['username'] && $mi['username'] != 'guest')
				return array_merge(
					$custom_actions_top,
					[
						$command['stly0mfa8sqnoq4hegzb']
					],
					$custom_actions_bottom
				);
	}


	/* End of Mass Update code */


	return [];
}
