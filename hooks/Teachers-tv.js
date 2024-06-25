/* start of mass_update code */
var massUpdateAlert = function(msg, showOk, okClass) {
	if(showOk == undefined) showOk = false;
	if(okClass == undefined) okClass = 'default';

	var footer = [];
	if(showOk) footer.push({ label: massUpdateTranslation.ok, bs_class: okClass });

	$j('.modal').modal('hide');
	var mId = modal_window({ message: '', title: msg, footer: footer });
	$j('#' + mId).find('.modal-body').remove();
	if(!footer.length) $j('#' + mId).find('.modal-footer').remove();
}


/* Topothetisi command */
function massUpdateCommand_0g0a7sq3f00f1kl3sedy(tn, ids) {

	/* Ask user for new value */
	modal_window({
		id: 'mass-update-new-value-modal',
		message: "<div id=\"mass-update-new-value\"><\/div>",
		title: '<i class="glyphicon glyphicon-plus"></i> ' + 
			"Topothetisi",
		footer :[{
			label: massUpdateTranslation.confirm,
			bs_class: 'primary',
			click: function () {
				var newValue = $j('#mass-update-new-value').select2('val')

				/* send update request */
				massUpdateAlert(massUpdateTranslation.pleaseWait);
				$j.ajax({
					url: "hooks\/ajax-mass-update-Teachers-Placement-0g0a7sq3f00f1kl3sedy.php",
					data: { ids: ids, newValue: newValue },
					success: function() { location.reload(); },
					error: function() {
						massUpdateAlert('<span class="text-danger">' + massUpdateTranslation.error + '</span>', true, 'danger');
					}
				});
			}
		}]
	});


	/* prepare select2 drop-down inside modal */
	$j('#mass-update-new-value-modal').on('shown.bs.modal', function () {
		$j("#mass-update-new-value").select2({
			width: '100%',
			formatNoMatches: function(term){ return massUpdateTranslation.noMatches; },
			minimumResultsForSearch: 5,
			loadMorePadding: 200,
			data: [{"id":"\u039f\u03a1\u0393\u0391\u039d\u0399\u039a\u0397","text":"\u039f\u03a1\u0393\u0391\u039d\u0399\u039a\u0397"},{"id":"\u0391\u039d\u0391\u03a0\u039b\u0397\u03a1\u03a9\u03a4\u0397\u03a3","text":"\u0391\u039d\u0391\u03a0\u039b\u0397\u03a1\u03a9\u03a4\u0397\u03a3"},{"id":"\u0391\u03a0\u039f\u03a3\u03a0\u0391\u03a3\u0397","text":"\u0391\u03a0\u039f\u03a3\u03a0\u0391\u03a3\u0397"},{"id":"\u0394\u0399\u0391\u0398\u0395\u03a3\u0397","text":"\u0394\u0399\u0391\u0398\u0395\u03a3\u0397"}],
			escapeMarkup: function(str){ return str; }
		}).select2('focus');
	});

}
/* end of mass_update code */

$j(document).ready(function () {
  $j("#top_buttons").append(
    '<a href="https://6epal-esp-thess.gr/assignments/Teachers_view.php?SortField=&SortDirection=&FilterAnd%5B1%5D=and&FilterField%5B1%5D=12&FilterOperator%5B1%5D=greater-than-or-equal-to&FilterValue%5B1%5D=1&FilterAnd%5B2%5D=or&FilterField%5B2%5D=12&FilterOperator%5B2%5D=less-than-or-equal-to&FilterValue%5B2%5D=-1">Εκπαιδευτικοί με μη μηδενική διαφορά</a>'
  );
});
