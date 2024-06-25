<?php
	define('PREPEND_PATH', '');
	include_once(__DIR__ . '/lib.php');

	// accept a record as an assoc array, return transformed row ready to insert to table
	$transformFunctions = [
		'Protocol' => function($data, $options = []) {
			if(isset($data['receipt_date'])) $data['receipt_date'] = guessMySQLDateTime($data['receipt_date']);
			if(isset($data['date_issuing'])) $data['date_issuing'] = guessMySQLDateTime($data['date_issuing']);
			if(isset($data['outcoming_date'])) $data['outcoming_date'] = guessMySQLDateTime($data['outcoming_date']);
			if(isset($data['processing_date'])) $data['processing_date'] = guessMySQLDateTime($data['processing_date']);
			if(isset($data['folder_id'])) $data['folder_id'] = pkGivenLookupText($data['folder_id'], 'Protocol', 'folder_id');
			if(isset($data['subfolder_id'])) $data['subfolder_id'] = pkGivenLookupText($data['subfolder_id'], 'Protocol', 'subfolder_id');

			return $data;
		},
		'Incoming_Files' => function($data, $options = []) {
			if(isset($data['protocol_id'])) $data['protocol_id'] = pkGivenLookupText($data['protocol_id'], 'Incoming_Files', 'protocol_id');

			return $data;
		},
		'Outcoming_Files' => function($data, $options = []) {
			if(isset($data['protocol_id'])) $data['protocol_id'] = pkGivenLookupText($data['protocol_id'], 'Outcoming_Files', 'protocol_id');
			if(isset($data['doc_template_id'])) $data['doc_template_id'] = pkGivenLookupText($data['doc_template_id'], 'Outcoming_Files', 'doc_template_id');

			return $data;
		},
		'Documents_templates' => function($data, $options = []) {

			return $data;
		},
		'TeachersName' => function($data, $options = []) {

			return $data;
		},
		'Assignments' => function($data, $options = []) {
			if(isset($data['ClassID'])) $data['ClassID'] = pkGivenLookupText($data['ClassID'], 'Assignments', 'ClassID');
			if(isset($data['LessonID'])) $data['LessonID'] = pkGivenLookupText($data['LessonID'], 'Assignments', 'LessonID');
			if(isset($data['TeacherID'])) $data['TeacherID'] = pkGivenLookupText($data['TeacherID'], 'Assignments', 'TeacherID');
			if(isset($data['TeacherID2'])) $data['TeacherID2'] = pkGivenLookupText($data['TeacherID2'], 'Assignments', 'TeacherID2');
			if(isset($data['datetime'])) $data['datetime'] = guessMySQLDateTime($data['datetime']);

			return $data;
		},
		'Teachers' => function($data, $options = []) {
			if(isset($data['SectorID'])) $data['SectorID'] = pkGivenLookupText($data['SectorID'], 'Teachers', 'SectorID');
			if(isset($data['Name2'])) $data['Name2'] = pkGivenLookupText($data['Name2'], 'Teachers', 'Name2');
			if(isset($data['Assumption_Date'])) $data['Assumption_Date'] = guessMySQLDateTime($data['Assumption_Date']);

			return $data;
		},
		'Lessons' => function($data, $options = []) {

			return $data;
		},
		'Classes' => function($data, $options = []) {

			return $data;
		},
		'Sectors' => function($data, $options = []) {

			return $data;
		},
		'Heads' => function($data, $options = []) {
			if(isset($data['teacher_id'])) $data['teacher_id'] = pkGivenLookupText($data['teacher_id'], 'Heads', 'teacher_id');
			if(isset($data['class_id'])) $data['class_id'] = pkGivenLookupText($data['class_id'], 'Heads', 'class_id');

			return $data;
		},
		'Projectors_timetable' => function($data, $options = []) {
			if(isset($data['teacher_id'])) $data['teacher_id'] = pkGivenLookupText($data['teacher_id'], 'Projectors_timetable', 'teacher_id');
			if(isset($data['date'])) $data['date'] = guessMySQLDateTime($data['date']);
			if(isset($data['hour_id'])) $data['hour_id'] = pkGivenLookupText($data['hour_id'], 'Projectors_timetable', 'hour_id');
			if(isset($data['projector_id'])) $data['projector_id'] = pkGivenLookupText($data['projector_id'], 'Projectors_timetable', 'projector_id');

			return $data;
		},
		'Hours' => function($data, $options = []) {

			return $data;
		},
		'Projectors' => function($data, $options = []) {

			return $data;
		},
		'Tests' => function($data, $options = []) {
			if(isset($data['teacher_id'])) $data['teacher_id'] = pkGivenLookupText($data['teacher_id'], 'Tests', 'teacher_id');
			if(isset($data['assignments_id'])) $data['assignments_id'] = pkGivenLookupText($data['assignments_id'], 'Tests', 'assignments_id');
			if(isset($data['date'])) $data['date'] = guessMySQLDateTime($data['date']);
			if(isset($data['hour_id'])) $data['hour_id'] = pkGivenLookupText($data['hour_id'], 'Tests', 'hour_id');

			return $data;
		},
		'Folders' => function($data, $options = []) {

			return $data;
		},
		'Subfolders' => function($data, $options = []) {
			if(isset($data['folder_id'])) $data['folder_id'] = pkGivenLookupText($data['folder_id'], 'Subfolders', 'folder_id');

			return $data;
		},
		'Examinations' => function($data, $options = []) {

			return $data;
		},
	];

	// accept a record as an assoc array, return a boolean indicating whether to import or skip record
	$filterFunctions = [
		'Protocol' => function($data, $options = []) { return true; },
		'Incoming_Files' => function($data, $options = []) { return true; },
		'Outcoming_Files' => function($data, $options = []) { return true; },
		'Documents_templates' => function($data, $options = []) { return true; },
		'TeachersName' => function($data, $options = []) { return true; },
		'Assignments' => function($data, $options = []) { return true; },
		'Teachers' => function($data, $options = []) { return true; },
		'Lessons' => function($data, $options = []) { return true; },
		'Classes' => function($data, $options = []) { return true; },
		'Sectors' => function($data, $options = []) { return true; },
		'Heads' => function($data, $options = []) { return true; },
		'Projectors_timetable' => function($data, $options = []) { return true; },
		'Hours' => function($data, $options = []) { return true; },
		'Projectors' => function($data, $options = []) { return true; },
		'Tests' => function($data, $options = []) { return true; },
		'Folders' => function($data, $options = []) { return true; },
		'Subfolders' => function($data, $options = []) { return true; },
		'Examinations' => function($data, $options = []) { return true; },
	];

	/*
	Hook file for overwriting/amending $transformFunctions and $filterFunctions:
	hooks/import-csv.php
	If found, it's included below

	The way this works is by either completely overwriting any of the above 2 arrays,
	or, more commonly, overwriting a single function, for example:
		$transformFunctions['tablename'] = function($data, $options = []) {
			// new definition here
			// then you must return transformed data
			return $data;
		};

	Another scenario is transforming a specific field and leaving other fields to the default
	transformation. One possible way of doing this is to store the original transformation function
	in GLOBALS array, calling it inside the custom transformation function, then modifying the
	specific field:
		$GLOBALS['originalTransformationFunction'] = $transformFunctions['tablename'];
		$transformFunctions['tablename'] = function($data, $options = []) {
			$data = call_user_func_array($GLOBALS['originalTransformationFunction'], [$data, $options]);
			$data['fieldname'] = 'transformed value';
			return $data;
		};
	*/

	@include(__DIR__ . '/hooks/import-csv.php');

	$ui = new CSVImportUI($transformFunctions, $filterFunctions);
