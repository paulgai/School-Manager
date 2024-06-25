<?php
// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks
require 'text_vars.php';
function Outcoming_Files_init(&$options, $memberInfo, &$args)
{

	return TRUE;
}

function Outcoming_Files_header($contentType, $memberInfo, &$args)
{
	$header = '';

	switch ($contentType) {
		case 'tableview':
			$header = '';
			break;

		case 'detailview':
			$header = '';
			break;

		case 'tableview+detailview':
			$header = '';
			break;

		case 'print-tableview':
			$header = '';
			break;

		case 'print-detailview':
			$header = '';
			break;

		case 'filters':
			$header = '';
			break;
	}

	return $header;
}

function Outcoming_Files_footer($contentType, $memberInfo, &$args)
{
	$footer = '';

	switch ($contentType) {
		case 'tableview':
			$footer = '';
			break;

		case 'detailview':
			echo '<script src="hooks/tinymce/tinymce.min.js"></script>';
			$footer = '';
			break;

		case 'tableview+detailview':
			$footer = '';
			break;

		case 'print-tableview':
			$footer = '';
			break;

		case 'print-detailview':
			$footer = '';
			break;

		case 'filters':
			$footer = '';
			break;
	}

	return $footer;
}

function Outcoming_Files_before_insert(&$data, $memberInfo, &$args)
{
	$doc_template_id = $data['doc_template_id'];
	if ($doc_template_id > 0) {
		$hex = sqlValue("SELECT `Documents_templates`.`hex_doc` FROM `Documents_templates` WHERE `Documents_templates`.`id` = {$doc_template_id}", $eo);
		$data['hex_doc'] = $hex;
	}
	return TRUE;
}

function Outcoming_Files_after_insert($data, $memberInfo, &$args)
{

	return TRUE;
}

function Outcoming_Files_before_update(&$data, $memberInfo, &$args)
{
	$doc_template_id = $data['doc_template_id'];
	if ($doc_template_id > 0 && $args['old_data']['doc_template_id'] != $doc_template_id) {
		$hex = sqlValue("SELECT `Documents_templates`.`hex_doc` FROM `Documents_templates` WHERE `Documents_templates`.`id` = {$doc_template_id}", $eo);
		$data['hex_doc'] = $hex;
	}
	return TRUE;
}

function Outcoming_Files_after_update($data, $memberInfo, &$args)
{

	return TRUE;
}

function Outcoming_Files_before_delete($selectedID, &$skipChecks, $memberInfo, &$args)
{

	return TRUE;
}

function Outcoming_Files_after_delete($selectedID, $memberInfo, &$args)
{
}

function Outcoming_Files_dv($selectedID, $memberInfo, &$html, &$args)
{
	global $ministry, $address, $city, $manager, $manager_name;
	$html .= "<script>
		function base64EncodeUnicode(str) {
			// Κωδικοποίηση του string σε UTF-8, μετατροπή σε percent-encoding, και στη συνέχεια σε base64
			return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
				return String.fromCharCode('0x' + p1);
			}));
		}
		
		function base64DecodeUnicode(str) {
			// Αποκωδικοποίηση από base64, μετατροπή percent-encoding σε raw binary string, και στη συνέχεια διακωδίκοποίηση UTF-8 σε χαρακτήρες
			return decodeURIComponent(Array.prototype.map.call(atob(str), function(c) {
				return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
			}).join(''));
		}
		
		\$j(document).ready(function() 
			{
				var newTextarea = \$j('<textarea id=\"doc\" style=\"height: 250px; width: 100%;\"></textarea>');
				\$j('.form-group.Outcoming_Files-hex_doc').after(newTextarea);
				tinymce.init({
					selector: '#doc',
					license_key: 'gpl',
					language: 'el',
					height: 842,
					entity_encoding: 'raw' ,
					newline_behavior: 'linebreak',
					content_css: \"hooks/tinymce/A4.css\",
					body_class: 'page',
					plugins: [
						'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
						'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
						'insertdatetime', 'media', 'table', 'help', 'wordcount'
					  ],
					  toolbar: 'undo redo | blocks | ' +
					  'bold italic backcolor | alignleft aligncenter ' +
					  'alignright alignjustify | bullist numlist outdent indent | ' +
					  'removeformat | help fullscreen',
					  content_style: 'body { font-family: Helvetica, Arial, sans-serif; font-size:12px }'
				});
				var encodedText = \$j('#hex_doc').val();
				var decodedText =base64DecodeUnicode(encodedText);
				decodedText = decodedText.replace('-ministry-', " . json_encode($ministry) . ");
				decodedText = decodedText.replace('-address-', " . json_encode($address) . ");
				decodedText = decodedText.replace('-city-', " . json_encode($city) . ");
				decodedText = decodedText.replace('-manager-', " . json_encode($manager) . ");
				decodedText = decodedText.replace('-manager_name-', " . json_encode($manager_name) . ");
				setTimeout(function() {
					var iframeBody = \$j('#doc_ifr').contents().find('body#tinymce');
					iframeBody.html(decodedText);
					//console.log('hi');
				}, 2000); // Καθυστέρηση 1000 milliseconds (1 δευτερόλεπτο)
				
				\$j('.Outcoming_Files-hex_doc').hide();
	
				\$j('#update').mouseenter(function(event) {
					// Προσωρινή διακοπή του event για να προλάβουμε να κάνουμε τις απαραίτητες ενέργειες
					event.preventDefault();
			
					// Εκτέλεση του base64EncodeUnicode στο περιεχόμενο του textarea με id=\"doc\"
					var iframeContent = \$j('#doc_ifr').contents();
					var originalContent = iframeContent.find('body#tinymce').html();
					//console.log('originalContent:' + originalContent);
					var base64EncodedContent = base64EncodeUnicode(originalContent);
			
					// Πέρασμα του encoded περιεχομένου στο textarea με id=\"hex_doc\"
					\$j('#hex_doc').val(base64EncodedContent);
					//console.log('originalContent:' + originalContent);
					
				});

				var previousValue = \$j('#doc_template_id').val(); // Initial value

				setInterval(function() {
					var currentValue = \$j('#doc_template_id').val();
					if (currentValue !== previousValue) {
						var userConfirmation = confirm(\"Είστε σίγουροι ότι θέλετε να αλλάξετε πρότυπο; Αν ναι, θα χαθούν οι αλλαγές που έχουν γίνει στο έγγραφο.\");
						if (userConfirmation) {
							// Ο χρήστης επιβεβαίωσε την αλλαγή
							// Ελέγξτε αν υπάρχει το κουμπί 'insert' και πατήστε το
							if (\$j('#insert').length > 0) {
								\$j('#insert').click();
							}
							// Ελέγξτε αν υπάρχει το κουμπί 'update' και πατήστε το
							if (\$j('#update').length > 0) {
								\$j('#update').click();
							}
							previousValue = currentValue;
						} else {
							// Ο χρήστης ακύρωσε την αλλαγή, επιστρέψτε στην προηγούμενη τιμή
							currentValue = previousValue;
						}
						
						 // Update the previous value
					}
				}, 100); // Check every 1000 milliseconds (1 second)

			});			
		</script>";
}

function Outcoming_Files_csv($query, $memberInfo, &$args)
{

	return $query;
}
function Outcoming_Files_batch_actions(&$args)
{

	return [];
}
