<?php
/* mass_update: Applying command: Lesson Type */
$allowed_groups = ["Admins"];

include(__DIR__ . "/../lib.php");

// check permissions
$user = getMemberInfo();
if($allowed_groups == '*') {
	// allow any signed user
	if(!$user['username'] || $user['username'] == 'guest') {
		@header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
		exit;
	}
} elseif(!in_array($user['group'], $allowed_groups)) {
	@header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
	exit;
}

/* receive and validate calling parameters */
$ids = Request::val('ids');
if(empty($ids) || !is_array($ids)) {
	@header($_SERVER['SERVER_PROTOCOL'] . ' 501 Not Implemented');
	exit;
}


$new_value = makeSafe(Request::val('newValue'));

/* prepare a safe comma-separated list of IDs to use in the query */
$cs_ids = [];
foreach($ids as $id) $cs_ids[] = "'" . makeSafe($id) . "'";
$cs_ids = implode(', ', $cs_ids);

$tn = 'Lessons';
$field = 'Type';
$pk = getPKFieldName($tn);

$query = "UPDATE `{$tn}` SET `{$field}`='{$new_value}' WHERE `{$pk}` IN ({$cs_ids})";

if($new_value == 'MASS_UPDATE_TOGGLE_CHECKBOX')
	$query = "UPDATE `{$tn}` SET
		`{$field}` = IF(ISNULL(`{$field}`), '1', IF(`{$field}`, '0', '1')) 
		WHERE `{$pk}` IN ({$cs_ids})";

$e = ['silentErrors' => true];
sql($query, $e);

if($e['error']) {
	@header($_SERVER['SERVER_PROTOCOL'] . ' 501 Not Implemented');
}

