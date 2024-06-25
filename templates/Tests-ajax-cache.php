<?php
	$rdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'Tests';

		/* data for selected record, or defaults if none is selected */
		var data = {
			teacher_id: <?php echo json_encode(['id' => $rdata['teacher_id'], 'value' => $rdata['teacher_id'], 'text' => $jdata['teacher_id']]); ?>,
			assignments_id: <?php echo json_encode(['id' => $rdata['assignments_id'], 'value' => $rdata['assignments_id'], 'text' => $jdata['assignments_id']]); ?>,
			hour_id: <?php echo json_encode(['id' => $rdata['hour_id'], 'value' => $rdata['hour_id'], 'text' => $jdata['hour_id']]); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for teacher_id */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'teacher_id' && d.id == data.teacher_id.id)
				return { results: [ data.teacher_id ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for assignments_id */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'assignments_id' && d.id == data.assignments_id.id)
				return { results: [ data.assignments_id ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for hour_id */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'hour_id' && d.id == data.hour_id.id)
				return { results: [ data.hour_id ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

