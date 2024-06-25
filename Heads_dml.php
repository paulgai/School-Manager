<?php

// Data functions (insert, update, delete, form) for table Heads

// This script and data application was generated by AppGini, https://bigprof.com/appgini
// Download AppGini for free from https://bigprof.com/appgini/download/

function Heads_insert(&$error_message = '') {
	global $Translation;

	// mm: can member insert record?
	$arrPerm = getTablePermissions('Heads');
	if(!$arrPerm['insert']) return false;

	$data = [
		'teacher_id' => Request::lookup('teacher_id', ''),
		'class_id' => Request::lookup('class_id', ''),
	];


	// hook: Heads_before_insert
	if(function_exists('Heads_before_insert')) {
		$args = [];
		if(!Heads_before_insert($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$error = '';
	// set empty fields to NULL
	$data = array_map(function($v) { return ($v === '' ? NULL : $v); }, $data);
	insert('Heads', backtick_keys_once($data), $error);
	if($error) {
		$error_message = $error;
		return false;
	}

	$recID = db_insert_id(db_link());

	update_calc_fields('Heads', $recID, calculated_fields()['Heads']);

	// hook: Heads_after_insert
	if(function_exists('Heads_after_insert')) {
		$res = sql("SELECT * FROM `Heads` WHERE `id`='" . makeSafe($recID, false) . "' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) {
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args = [];
		if(!Heads_after_insert($data, getMemberInfo(), $args)) { return $recID; }
	}

	// mm: save ownership data
	// record owner is current user
	$recordOwner = getLoggedMemberID();
	set_record_owner('Heads', $recID, $recordOwner);

	// if this record is a copy of another record, copy children if applicable
	if(strlen(Request::val('SelectedID'))) Heads_copy_children($recID, Request::val('SelectedID'));

	return $recID;
}

function Heads_copy_children($destination_id, $source_id) {
	global $Translation;
	$requests = []; // array of curl handlers for launching insert requests
	$eo = ['silentErrors' => true];
	$safe_sid = makeSafe($source_id);

	// launch requests, asynchronously
	curl_batch($requests);
}

function Heads_delete($selected_id, $AllowDeleteOfParents = false, $skipChecks = false) {
	// insure referential integrity ...
	global $Translation;
	$selected_id = makeSafe($selected_id);

	// mm: can member delete record?
	if(!check_record_permission('Heads', $selected_id, 'delete')) {
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: Heads_before_delete
	if(function_exists('Heads_before_delete')) {
		$args = [];
		if(!Heads_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'] . (
				!empty($args['error_message']) ?
					'<div class="text-bold">' . strip_tags($args['error_message']) . '</div>'
					: '' 
			);
	}

	sql("DELETE FROM `Heads` WHERE `id`='{$selected_id}'", $eo);

	// hook: Heads_after_delete
	if(function_exists('Heads_after_delete')) {
		$args = [];
		Heads_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("DELETE FROM `membership_userrecords` WHERE `tableName`='Heads' AND `pkValue`='{$selected_id}'", $eo);
}

function Heads_update(&$selected_id, &$error_message = '') {
	global $Translation;

	// mm: can member edit record?
	if(!check_record_permission('Heads', $selected_id, 'edit')) return false;

	$data = [
		'teacher_id' => Request::lookup('teacher_id', ''),
		'class_id' => Request::lookup('class_id', ''),
	];

	// get existing values
	$old_data = getRecord('Heads', $selected_id);
	if(is_array($old_data)) {
		$old_data = array_map('makeSafe', $old_data);
		$old_data['selectedID'] = makeSafe($selected_id);
	}

	$data['selectedID'] = makeSafe($selected_id);

	// hook: Heads_before_update
	if(function_exists('Heads_before_update')) {
		$args = ['old_data' => $old_data];
		if(!Heads_before_update($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$set = $data; unset($set['selectedID']);
	foreach ($set as $field => $value) {
		$set[$field] = ($value !== '' && $value !== NULL) ? $value : NULL;
	}

	if(!update(
		'Heads', 
		backtick_keys_once($set), 
		['`id`' => $selected_id], 
		$error_message
	)) {
		echo $error_message;
		echo '<a href="Heads_view.php?SelectedID=' . urlencode($selected_id) . "\">{$Translation['< back']}</a>";
		exit;
	}


	$eo = ['silentErrors' => true];

	update_calc_fields('Heads', $data['selectedID'], calculated_fields()['Heads']);

	// hook: Heads_after_update
	if(function_exists('Heads_after_update')) {
		$res = sql("SELECT * FROM `Heads` WHERE `id`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) $data = array_map('makeSafe', $row);

		$data['selectedID'] = $data['id'];
		$args = ['old_data' => $old_data];
		if(!Heads_after_update($data, getMemberInfo(), $args)) return;
	}

	// mm: update record update timestamp
	set_record_owner('Heads', $selected_id);
}

function Heads_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $separateDV = 0, $TemplateDV = '', $TemplateDVP = '') {
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;
	$eo = ['silentErrors' => true];
	$noUploads = null;
	$row = $urow = $jsReadOnly = $jsEditable = $lookups = null;

	$noSaveAsCopy = true;

	// mm: get table permissions
	$arrPerm = getTablePermissions('Heads');
	if(!$arrPerm['insert'] && $selected_id == '')
		// no insert permission and no record selected
		// so show access denied error unless TVDV
		return $separateDV ? $Translation['tableAccessDenied'] : '';
	$AllowInsert = ($arrPerm['insert'] ? true : false);
	// print preview?
	$dvprint = false;
	if(strlen($selected_id) && Request::val('dvprint_x') != '') {
		$dvprint = true;
	}

	$filterer_teacher_id = Request::val('filterer_teacher_id');
	$filterer_class_id = Request::val('filterer_class_id');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: teacher_id
	$combo_teacher_id = new DataCombo;
	// combobox: class_id
	$combo_class_id = new DataCombo;

	if($selected_id) {
		if(!check_record_permission('Heads', $selected_id, 'view'))
			return $Translation['tableAccessDenied'];

		// can edit?
		$AllowUpdate = check_record_permission('Heads', $selected_id, 'edit');

		// can delete?
		$AllowDelete = check_record_permission('Heads', $selected_id, 'delete');

		$res = sql("SELECT * FROM `Heads` WHERE `id`='" . makeSafe($selected_id) . "'", $eo);
		if(!($row = db_fetch_array($res))) {
			return error_message($Translation['No records found'], 'Heads_view.php', false);
		}
		$combo_teacher_id->SelectedData = $row['teacher_id'];
		$combo_class_id->SelectedData = $row['class_id'];
		$urow = $row; /* unsanitized data */
		$row = array_map('safe_html', $row);
	} else {
		$filterField = Request::val('FilterField');
		$filterOperator = Request::val('FilterOperator');
		$filterValue = Request::val('FilterValue');
		$combo_teacher_id->SelectedData = $filterer_teacher_id;
		$combo_class_id->SelectedData = $filterer_class_id;
	}
	$combo_teacher_id->HTML = '<span id="teacher_id-container' . $rnd1 . '"></span><input type="hidden" name="teacher_id" id="teacher_id' . $rnd1 . '" value="' . html_attr($combo_teacher_id->SelectedData) . '">';
	$combo_teacher_id->MatchText = '<span id="teacher_id-container-readonly' . $rnd1 . '"></span><input type="hidden" name="teacher_id" id="teacher_id' . $rnd1 . '" value="' . html_attr($combo_teacher_id->SelectedData) . '">';
	$combo_class_id->HTML = '<span id="class_id-container' . $rnd1 . '"></span><input type="hidden" name="class_id" id="class_id' . $rnd1 . '" value="' . html_attr($combo_class_id->SelectedData) . '">';
	$combo_class_id->MatchText = '<span id="class_id-container-readonly' . $rnd1 . '"></span><input type="hidden" name="class_id" id="class_id' . $rnd1 . '" value="' . html_attr($combo_class_id->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_teacher_id__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['teacher_id'] : htmlspecialchars($filterer_teacher_id, ENT_QUOTES)); ?>"};
		AppGini.current_class_id__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['class_id'] : htmlspecialchars($filterer_class_id, ENT_QUOTES)); ?>"};

		jQuery(function() {
			setTimeout(function() {
				if(typeof(teacher_id_reload__RAND__) == 'function') teacher_id_reload__RAND__();
				if(typeof(class_id_reload__RAND__) == 'function') class_id_reload__RAND__();
			}, 50); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function teacher_id_reload__RAND__() {
		<?php if(($AllowUpdate || ($arrPerm['insert'] && !$selected_id)) && !$dvprint) { ?>

			$j("#teacher_id-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_teacher_id__RAND__.value, t: 'Heads', f: 'teacher_id' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="teacher_id"]').val(resp.results[0].id);
							$j('[id=teacher_id-container-readonly__RAND__]').html('<span class="match-text" id="teacher_id-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=TeachersName_view_parent]').hide(); } else { $j('.btn[id=TeachersName_view_parent]').show(); }


							if(typeof(teacher_id_update_autofills__RAND__) == 'function') teacher_id_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'Heads', f: 'teacher_id' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_teacher_id__RAND__.value = e.added.id;
				AppGini.current_teacher_id__RAND__.text = e.added.text;
				$j('[name="teacher_id"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=TeachersName_view_parent]').hide(); } else { $j('.btn[id=TeachersName_view_parent]').show(); }


				if(typeof(teacher_id_update_autofills__RAND__) == 'function') teacher_id_update_autofills__RAND__();
			});

			if(!$j("#teacher_id-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_teacher_id__RAND__.value, t: 'Heads', f: 'teacher_id' },
					success: function(resp) {
						$j('[name="teacher_id"]').val(resp.results[0].id);
						$j('[id=teacher_id-container-readonly__RAND__]').html('<span class="match-text" id="teacher_id-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=TeachersName_view_parent]').hide(); } else { $j('.btn[id=TeachersName_view_parent]').show(); }

						if(typeof(teacher_id_update_autofills__RAND__) == 'function') teacher_id_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_teacher_id__RAND__.value, t: 'Heads', f: 'teacher_id' },
				success: function(resp) {
					$j('[id=teacher_id-container__RAND__], [id=teacher_id-container-readonly__RAND__]').html('<span class="match-text" id="teacher_id-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=TeachersName_view_parent]').hide(); } else { $j('.btn[id=TeachersName_view_parent]').show(); }

					if(typeof(teacher_id_update_autofills__RAND__) == 'function') teacher_id_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
		function class_id_reload__RAND__() {
		<?php if(($AllowUpdate || ($arrPerm['insert'] && !$selected_id)) && !$dvprint) { ?>

			$j("#class_id-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_class_id__RAND__.value, t: 'Heads', f: 'class_id' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="class_id"]').val(resp.results[0].id);
							$j('[id=class_id-container-readonly__RAND__]').html('<span class="match-text" id="class_id-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Classes_view_parent]').hide(); } else { $j('.btn[id=Classes_view_parent]').show(); }


							if(typeof(class_id_update_autofills__RAND__) == 'function') class_id_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'Heads', f: 'class_id' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_class_id__RAND__.value = e.added.id;
				AppGini.current_class_id__RAND__.text = e.added.text;
				$j('[name="class_id"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Classes_view_parent]').hide(); } else { $j('.btn[id=Classes_view_parent]').show(); }


				if(typeof(class_id_update_autofills__RAND__) == 'function') class_id_update_autofills__RAND__();
			});

			if(!$j("#class_id-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_class_id__RAND__.value, t: 'Heads', f: 'class_id' },
					success: function(resp) {
						$j('[name="class_id"]').val(resp.results[0].id);
						$j('[id=class_id-container-readonly__RAND__]').html('<span class="match-text" id="class_id-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Classes_view_parent]').hide(); } else { $j('.btn[id=Classes_view_parent]').show(); }

						if(typeof(class_id_update_autofills__RAND__) == 'function') class_id_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_class_id__RAND__.value, t: 'Heads', f: 'class_id' },
				success: function(resp) {
					$j('[id=class_id-container__RAND__], [id=class_id-container-readonly__RAND__]').html('<span class="match-text" id="class_id-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=Classes_view_parent]').hide(); } else { $j('.btn[id=Classes_view_parent]').show(); }

					if(typeof(class_id_update_autofills__RAND__) == 'function') class_id_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_clean());


	// code for template based detail view forms

	// open the detail view template
	$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/Heads_templateDV.html';
	$templateCode = @file_get_contents($template_file);

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', '&#933;&#960;&#941;&#965;&#952;&#965;&#957;&#959;&#962; &#932;&#956;&#942;&#956;&#945;&#964;&#959;&#962;', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', (Request::val('Embedded') ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($arrPerm['insert'] && !$selected_id) { // allow insert and no record selected?
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return Heads_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return Heads_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if(Request::val('Embedded')) {
		$backAction = 'AppGini.closeParentModal(); return false;';
	} else {
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id) {
		if($AllowUpdate)
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return Heads_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);

		if($AllowDelete)
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		// if not in embedded mode and user has insert only but no view/update/delete,
		// remove 'back' button
		if(
			$arrPerm['insert']
			&& !$arrPerm['update'] && !$arrPerm['delete'] && !$arrPerm['view']
			&& !Request::val('Embedded')
		)
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
		elseif($separateDV)
			$templateCode = str_replace(
				'<%%DESELECT_BUTTON%%>', 
				'<button
					type="submit" 
					class="btn btn-default" 
					id="deselect" 
					name="deselect_x" 
					value="1" 
					onclick="' . $backAction . '" 
					title="' . html_attr($Translation['Back']) . '">
						<i class="glyphicon glyphicon-chevron-left"></i> ' .
						$Translation['Back'] .
				'</button>',
				$templateCode
			);
		else
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate) || (!$selected_id && !$AllowInsert)) {
		$jsReadOnly = '';
		$jsReadOnly .= "\tjQuery('#teacher_id').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#teacher_id_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#class_id').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#class_id_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	} elseif(($AllowInsert && !$selected_id) || ($AllowUpdate && $selected_id)) {
		$jsEditable = "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(teacher_id)%%>', $combo_teacher_id->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(teacher_id)%%>', $combo_teacher_id->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(teacher_id)%%>', urlencode($combo_teacher_id->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(class_id)%%>', $combo_class_id->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(class_id)%%>', $combo_class_id->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(class_id)%%>', urlencode($combo_class_id->MatchText), $templateCode);

	/* lookup fields array: 'lookup field name' => ['parent table name', 'lookup field caption'] */
	$lookup_fields = ['teacher_id' => ['TeachersName', '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#972;&#962;'], 'class_id' => ['Classes', '&#932;&#956;&#942;&#956;&#945;'], ];
	foreach($lookup_fields as $luf => $ptfc) {
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']) {
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] /* && !Request::val('Embedded')*/) {
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-default add_new_parent" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus text-success"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(id)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(teacher_id)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(class_id)%%>', '', $templateCode);

	// process values
	if($selected_id) {
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', safe_html($urow['id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id)%%>', html_attr($row['id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode($urow['id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(teacher_id)%%>', safe_html($urow['teacher_id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(teacher_id)%%>', html_attr($row['teacher_id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(teacher_id)%%>', urlencode($urow['teacher_id']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(class_id)%%>', safe_html($urow['class_id']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(class_id)%%>', html_attr($row['class_id']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(class_id)%%>', urlencode($urow['class_id']), $templateCode);
	} else {
		$templateCode = str_replace('<%%VALUE(id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(teacher_id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(teacher_id)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(class_id)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(class_id)%%>', urlencode(''), $templateCode);
	}

	// process translations
	$templateCode = parseTemplate($templateCode);

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if(Request::val('dvprint_x') == '') {
		$templateCode .= "\n\n<script>\$j(function() {\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption) {
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id) {
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields
	$filterField = Request::val('FilterField');
	$filterOperator = Request::val('FilterOperator');
	$filterValue = Request::val('FilterValue');

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('Heads');
	if($selected_id) {
		$jdata = get_joined_record('Heads', $selected_id);
		if($jdata === false) $jdata = get_defaults('Heads');
		$rdata = $row;
	}
	$templateCode .= loadView('Heads-ajax-cache', ['rdata' => $rdata, 'jdata' => $jdata]);

	// hook: Heads_dv
	if(function_exists('Heads_dv')) {
		$args = [];
		Heads_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}