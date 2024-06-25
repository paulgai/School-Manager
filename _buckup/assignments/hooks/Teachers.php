<?php
// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

function Teachers_init(&$options, $memberInfo, &$args)
{
	/* Inserted by Search Page Maker for AppGini on 2022-11-28 07:14:24 */
	$options->FilterPage = 'hooks/Teachers_filter.php';
	/* End of Search Page Maker for AppGini code */


	return TRUE;
}



function Teachers_header($contentType, $memberInfo, &$args)
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

function Teachers_footer($contentType, $memberInfo, &$args)
{
	$script = '<script>var checkExist = setInterval(function () {
		//console.log("ready!");
		if ($j(".Assignments-Lesson_A_Assignment").length) {
		  //console.log("Table Loaded");
		  $j(".Assignments-Lesson_A_Assignment").remove();
		  //clearInterval(checkExist);
		}
		if ($j(".Assignments-Lesson_B_Assignment").length) {
		  //console.log("Table Loaded");
		  $j(".Assignments-Lesson_B_Assignment").remove();
		  //clearInterval(checkExist);
		}
		if ($j(".Assignments-Lesson_C_Assignment").length) {
		  //console.log("Table Loaded");
		  $j(".Assignments-Lesson_C_Assignment").remove();
		  //clearInterval(checkExist);//Assignments-datetime
		}
		if ($j(".Assignments-datetime").length) {
			//console.log("Table Loaded");
			$j(".Assignments-datetime").remove();
			//clearInterval(checkExist);//Assignments-datetime
		  }
	  }, 100);</script>';
	$footer = '';

	switch ($contentType) {
		case 'tableview':
			$footer = '';
			break;

		case 'detailview':
			$footer = '';
			echo strval($script);
			break;

		case 'tableview+detailview':
			$footer = '';
			break;

		case 'print-tableview':
			$footer = '';
			break;

		case 'print-detailview':
			$footer = '';
			echo strval($script);
			break;

		case 'filters':
			$footer = '';
			break;
	}

	return $footer;
}

function Teachers_before_insert(&$data, $memberInfo, &$args)
{

	return TRUE;
}

function Teachers_after_insert($data, $memberInfo, &$args)
{

	return TRUE;
}

function Teachers_before_update(&$data, $memberInfo, &$args)
{

	return TRUE;
}

function Teachers_after_update($data, $memberInfo, &$args)
{

	return TRUE;
}

function Teachers_before_delete($selectedID, &$skipChecks, $memberInfo, &$args)
{

	return TRUE;
}

function Teachers_after_delete($selectedID, $memberInfo, &$args)
{
}

function Teachers_dv($selectedID, $memberInfo, &$html, &$args)
{
}

function Teachers_csv($query, $memberInfo, &$args)
{

	return $query;
}
function Teachers_batch_actions(&$args)
{
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
			'0g0a7sq3f00f1kl3sedy' => [
				'title' => "Topothetisi",
				'function' => 'massUpdateCommand_0g0a7sq3f00f1kl3sedy',
				'icon' => 'plus'
			],
		];

		$mi = getMemberInfo();
		switch($mi['group']) {
			case 'Admins':
				return array_merge(
					$custom_actions_top,
					[
						$command['0g0a7sq3f00f1kl3sedy']
					],
					$custom_actions_bottom
				);
		}


		/* End of Mass Update code */


	return [];
}
