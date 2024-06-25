/* start of mass_update code */
var massUpdateAlert = function (msg, showOk, okClass) {
  if (showOk == undefined) showOk = false;
  if (okClass == undefined) okClass = "default";

  var footer = [];
  if (showOk)
    footer.push({ label: massUpdateTranslation.ok, bs_class: okClass });

  $j(".modal").modal("hide");
  var mId = modal_window({ message: "", title: msg, footer: footer });
  $j("#" + mId)
    .find(".modal-body")
    .remove();
  if (!footer.length)
    $j("#" + mId)
      .find(".modal-footer")
      .remove();
};

/* test command */
function massUpdateCommand_stly0mfa8sqnoq4hegzb(tn, ids) {
  /* Ask user for new value */
  modal_window({
    id: "mass-update-new-value-modal",
    message:
      '<div class="form-group"><label for="mass-update-new-value">' +
      massUpdateTranslation.newValue +
      '</label><input type="text" class="form-control" id="mass-update-new-value"></div>',
    title: '<i class="glyphicon glyphicon-asterisk"></i> ' + "test",
    footer: [
      {
        label: massUpdateTranslation.confirm,
        bs_class: "primary",
        click: function () {
          var newValue = $j("#mass-update-new-value").val();

          /* ask user for confirmation before applying updates */
          if (!confirm(massUpdateTranslation.areYouSureApply)) return;

          /* send update request */
          massUpdateAlert(massUpdateTranslation.pleaseWait);
          $j.ajax({
            url: "hooks/ajax-mass-update-Classes-Class-stly0mfa8sqnoq4hegzb.php",
            data: { ids: ids, newValue: newValue },
            success: function () {
              location.reload();
            },
            error: function () {
              massUpdateAlert(
                '<span class="text-danger">' +
                  massUpdateTranslation.error +
                  "</span>",
                true,
                "danger"
              );
            },
          });
        },
      },
    ],
  });

  $j("#mass-update-new-value-modal").on("shown.bs.modal", function () {
    // focus new value
    $j("#mass-update-new-value").focus();
  });
}
/* end of mass_update code */
