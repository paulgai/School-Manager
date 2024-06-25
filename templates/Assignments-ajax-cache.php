<?php
	$rdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'Assignments';

		/* data for selected record, or defaults if none is selected */
		var data = {
			ClassID: <?php echo json_encode(['id' => $rdata['ClassID'], 'value' => $rdata['ClassID'], 'text' => $jdata['ClassID']]); ?>,
			LessonID: <?php echo json_encode(['id' => $rdata['LessonID'], 'value' => $rdata['LessonID'], 'text' => $jdata['LessonID']]); ?>,
			TeacherID: <?php echo json_encode(['id' => $rdata['TeacherID'], 'value' => $rdata['TeacherID'], 'text' => $jdata['TeacherID']]); ?>,
			TeacherID2: <?php echo json_encode(['id' => $rdata['TeacherID2'], 'value' => $rdata['TeacherID2'], 'text' => $jdata['TeacherID2']]); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for ClassID */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'ClassID' && d.id == data.ClassID.id)
				return { results: [ data.ClassID ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for LessonID */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'LessonID' && d.id == data.LessonID.id)
				return { results: [ data.LessonID ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for TeacherID */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'TeacherID' && d.id == data.TeacherID.id)
				return { results: [ data.TeacherID ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for TeacherID2 */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'TeacherID2' && d.id == data.TeacherID2.id)
				return { results: [ data.TeacherID2 ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

