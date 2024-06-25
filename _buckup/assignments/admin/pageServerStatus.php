<?php
	$appgini_version = '23.15.1484';
	$generated_ts = '7/10/2023 2:16:06 ��';

	require(__DIR__ . '/incCommon.php');

	$GLOBALS['page_title'] = $Translation['server status'];
	include(__DIR__ . '/incHeader.php');

	// get phpinfo() and remove HTML wrapping
	ob_start();
	phpinfo();
	$phpinfo_raw = ob_get_clean();
	preg_match('/<table.*<\/table>/s', $phpinfo_raw, $phpinfo);

	// AppGini version and gen date/time
	$admin_config = config('adminConfig');
	$gen_info = str_replace(
		array(
			'<VERSION>', 
			'<DATETIME>'
		), 
		array(
			"<span class=\"label label-info\">{$appgini_version}</span>", 
			$generated_ts
		), 
		$Translation['generated by']
	);

	$eo = ['silentErrors' => true];

	// uploads storage
	$num_uploads = $uploads_size = 0;
	// if uploads folder path is absolute, don't prepend app path
	if(getUploadDir('')[0] == '/')
		$uploads_path = getUploadDir('');
	else
		$uploads_path = __DIR__ . '/../' . getUploadDir('');

	$uploads_path = rtrim($uploads_path, '\\/') . '/';

	$uf = dir(rtrim($uploads_path));
	while(false !== ($entry = $uf->read())) {
		// entries to skip
		if(in_array($entry, [
			'.', 
			'..', 
			'blank.gif', 
			'blank_dv.gif', 
			'blank_tv.gif', 
			'index.html'
		])) continue;

		if(@is_dir($uploads_path . $entry)) continue;

		$num_uploads++;
		$uploads_size += @filesize($uploads_path . $entry);
	}
	$uf->close();

	// DB storage
	$db_name = makeSafe(config('dbDatabase'));
	$db_storage = [];
	$total_storage = 0;
	$res = sql(
		"SELECT 
			table_name, ROUND((data_length + index_length) / 1024 , 1) 
		FROM information_schema.tables 
		WHERE table_schema='{$db_name}' AND table_type='BASE TABLE' 
		ORDER BY 2 DESC", 
		$eo
	);
	while($row = db_fetch_row($res)) {
		$db_storage[$row[0]] = $row[1];
		$total_storage += $row[1];
	}

	// MySQL status
	$db_status = [];
	$res = sql('SHOW STATUS', $eo);
	while($row = db_fetch_array($res)) $db_status[$row[0]] = $row[1];
	$res = sql('SHOW VARIABLES', $eo);
	while($row = db_fetch_array($res)) $db_status[$row[0]] = $row[1];
?>

<div class="page-header"><h1><?php echo $Translation['server status']; ?></h1></div>

<h4><?php echo $gen_info; ?></h4>
<hr>

<div class="row">
	<div class="col-lg-4">
		<h3><?php echo $Translation['uploads info']; ?></h3>
		<span class="label label-info big-number">
			<?php echo $num_uploads; ?>
			<?php echo $Translation['files']; ?>
		</span>
		<span class="label label-info big-number"><?php echo number_format($uploads_size / 1024 / 1024, 2); ?> MB</span>

		<h3><?php echo $Translation['db storage']; ?></h3>
		<div class="db-status scrollable">
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th><?php echo $Translation['column table name']; ?></th>
						<th class="text-center"><?php echo $Translation['column size kb']; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($db_storage as $table => $storage) { ?>
						<tr>
							<th><?php echo $table; ?></th>
							<td class="text-right"><?php echo number_format($storage); ?></td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<th><?php echo $Translation['total']; ?></th>
						<th class="text-right"><?php echo number_format($total_storage); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
		<hr>
	</div>

	<div class="col-lg-4">
		<h3><?php echo $Translation['db status']; ?></h3>
		<div class="db-status scrollable">
			<pre>SHOW STATUS; SHOW VARIABLES;</pre>
			<table class="table table-striped table-hover table-bordered">
				<?php foreach($db_status as $var => $val) { ?>
					<tr>
						<th><?php echo $var; ?></th>
						<td><?php echo $val; ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
		<hr>
	</div>

	<div class="col-lg-4" id="phpinfo">
		<h3><?php echo $Translation['php info']; ?></h3>
		<div class="db-status scrollable">
			<?php echo $phpinfo[0]; ?>
		</div>
	</div>
</div>

<style>
	#phpinfo table {
		width: 100%;
	}
	#phpinfo td {
		border: solid 1px #888;
		padding: 3px 10px;
		color: #000;
	}
	#phpinfo table tr:nth-child(even) {
		background-color: #ddd;
	}
	#phpinfo table tr:nth-child(odd) {
		background-color: #fff;
	}
	.db-status.scrollable {
		max-height: 60vh;
		overflow-y: auto;
	}
	.big-number {
		font-size: 5rem;
		line-height: 10rem;
		margin: 0 1rem;
	}
	.cursor-pointer {
		cursor: pointer;
	}

</style>

<script>
	$j(function() {
		// apply a zoom-in button to each grid cell
		$j('<i class="glyphicon glyphicon-zoom-in resizer text-primary hspacer-sm cursor-pointer"></i>').prependTo('.col-lg-4 > h3:first-child');

		// and for every zoom-in button, add a class 'zoomable-cell' to its grid cell
		$j('.resizer').parents('.col-lg-4').addClass('zoomable-cell');

		// on clicking a zoom button, maximize/restore its parent grid cell
		$j('.row').on('click', '.resizer', function() {
			var toggler = $j(this),
				cell = toggler.parents('.zoomable-cell'),
				maximized = cell.hasClass('col-lg-12');

			toggler.toggleClass('glyphicon-zoom-in glyphicon-zoom-out');
			cell.toggleClass('col-lg-12 col-lg-4');
			cell.children('.db-status').toggleClass('scrollable')

			if(maximized) {
				// restore default size
				cell.parents('.row').children('.col-lg-4').removeClass('hidden');
			} else {
				// otherwise, maximize
				cell.parents('.row').children('.col-lg-4').addClass('hidden');
			}
		})
	})
</script>

<?php
	include(__DIR__ . '/incFooter.php');
