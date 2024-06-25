<?php
// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks
require 'text_vars.php';
function Documents_templates_init(&$options, $memberInfo, &$args)
{

	return TRUE;
}

function Documents_templates_header($contentType, $memberInfo, &$args)
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

function Documents_templates_footer($contentType, $memberInfo, &$args)
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

function Documents_templates_before_insert(&$data, $memberInfo, &$args)
{

	return TRUE;
}

function Documents_templates_after_insert($data, $memberInfo, &$args)
{

	return TRUE;
}

function Documents_templates_before_update(&$data, $memberInfo, &$args)
{

	return TRUE;
}

function Documents_templates_after_update($data, $memberInfo, &$args)
{

	return TRUE;
}

function Documents_templates_before_delete($selectedID, &$skipChecks, $memberInfo, &$args)
{

	return TRUE;
}

function Documents_templates_after_delete($selectedID, $memberInfo, &$args)
{
}

function Documents_templates_dv($selectedID, $memberInfo, &$html, &$args)
{
	global $ministry;
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
				\$j('.form-group.Documents_templates-hex_doc').after(newTextarea);
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
				setTimeout(function() {
					var iframeBody = \$j('#doc_ifr').contents().find('body#tinymce');
					iframeBody.html(decodedText);
					//console.log('hi');
				}, 2000); // Καθυστέρηση 1000 milliseconds (1 δευτερόλεπτο)
				
				\$j('.Documents_templates-hex_doc').hide();
	
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
			});			
		</script>";
}

function Documents_templates_csv($query, $memberInfo, &$args)
{

	return $query;
}
function Documents_templates_batch_actions(&$args)
{

	return [];
}
