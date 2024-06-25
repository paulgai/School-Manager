<?php
	$rdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'Teachers';

		/* data for selected record, or defaults if none is selected */
		var data = {
			SectorID: <?php echo json_encode(['id' => $rdata['SectorID'], 'value' => $rdata['SectorID'], 'text' => $jdata['SectorID']]); ?>,
			Name2: <?php echo json_encode(['id' => $rdata['Name2'], 'value' => $rdata['Name2'], 'text' => $jdata['Name2']]); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for SectorID */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'SectorID' && d.id == data.SectorID.id)
				return { results: [ data.SectorID ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for Name2 */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'Name2' && d.id == data.Name2.id)
				return { results: [ data.Name2 ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

