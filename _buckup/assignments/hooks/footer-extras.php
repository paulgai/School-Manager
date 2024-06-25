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
if (isset($x) && strpos($x->HTML, 'selected_records_more') !== false) {
	if (strpos($x->HTML, 'nicEdit.js') === false) echo '<script src="nicEdit.js"></script>';
	echo '<script src="hooks/language-mass-update.js"></script>';
}

?>
<!-- end of mass update plugin code -->

<script>
	// Επιλέγουμε το <tr> στοιχείο με το συγκεκριμένο data-time attribute και βρίσκουμε το <span> εντός του
	var spanElement = $('tr[data-time="18:00:00"]').find('span');

	// Αντικαθιστούμε το κείμενο του <span> στοιχείου με "1η Ώρα"
	spanElement.text('1η Ώρα');
</script>