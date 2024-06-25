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


/* Lesson Type command */
function massUpdateCommand_aw1zcpxp49k645zk327n(tn, ids) {

	/* Ask user for new value */
	modal_window({
		id: 'mass-update-new-value-modal',
		message: "<div id=\"mass-update-new-value\"><\/div>",
		title: '<i class="glyphicon glyphicon-plus"></i> ' + 
			"Lesson Type",
		footer :[{
			label: massUpdateTranslation.confirm,
			bs_class: 'primary',
			click: function () {
				var newValue = $j('#mass-update-new-value').select2('val')

				/* send update request */
				massUpdateAlert(massUpdateTranslation.pleaseWait);
				$j.ajax({
					url: "hooks\/ajax-mass-update-Lessons-Type-aw1zcpxp49k645zk327n.php",
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
			data: [{"id":"\u0393\u0395\u039d\u0399\u039a\u0397\u03a3 \u03a0\u0391\u0399\u0394\u0395\u0399\u0391\u03a3","text":"\u0393\u0395\u039d\u0399\u039a\u0397\u03a3 \u03a0\u0391\u0399\u0394\u0395\u0399\u0391\u03a3"},{"id":"\u0398\u0395\u03a9\u03a1\u0399\u0391","text":"\u0398\u0395\u03a9\u03a1\u0399\u0391"},{"id":"\u0395\u03a1\u0393\u0391\u03a3\u03a4\u0397\u03a1\u0399\u039f","text":"\u0395\u03a1\u0393\u0391\u03a3\u03a4\u0397\u03a1\u0399\u039f"}],
			escapeMarkup: function(str){ return str; }
		}).select2('focus');
	});

}
/* end of mass_update code */