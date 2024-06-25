<?php
	$rdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'Protocol';

		/* data for selected record, or defaults if none is selected */
		var data = {
			folder_id: <?php echo json_encode(['id' => $rdata['folder_id'], 'value' => $rdata['folder_id'], 'text' => $jdata['folder_id']]); ?>,
			subfolder_id: <?php echo json_encode(['id' => $rdata['subfolder_id'], 'value' => $rdata['subfolder_id'], 'text' => $jdata['subfolder_id']]); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for folder_id */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'folder_id' && d.id == data.folder_id.id)
				return { results: [ data.folder_id ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for subfolder_id */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'subfolder_id' && d.id == data.subfolder_id.id)
				return { results: [ data.subfolder_id ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

