<!-- start of mass update plugin code -->
<?php
if (isset($x) && strpos($x->HTML, 'selected_records_more') !== false) {
	if (strpos($x->HTML, 'nicEdit.js') === false) echo '<script src="nicEdit.js"></script>';
	echo '<script src="hooks/language-mass-update.js"></script>';
}

?>
<!-- end of mass update plugin code -->


<script>
	_noShortcutsReference = true;
</script>
<!-- start of mass update plugin code -->
<?php
	if(isset($x) && strpos($x->HTML, 'selected_records_more') !== false) {
		if(strpos($x->HTML, 'nicEdit.js') === false) echo '<script src="nicEdit.js"></script>';
		echo '<script src="hooks/language-mass-update.js"></script>';
	}

?>
<!-- end of mass update plugin code -->
