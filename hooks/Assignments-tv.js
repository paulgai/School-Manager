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

/* Teacher A command */
function massUpdateCommand_l7f96jv6ah5nnuc8bqy0(tn, ids) {
  /* Ask user for new value */
  modal_window({
    id: "mass-update-new-value-modal",
    message: '<div id="mass-update-new-value"></div>',
    title: '<i class="glyphicon glyphicon-plus"></i> ' + "Teacher A",
    footer: [
      {
        label: massUpdateTranslation.confirm,
        bs_class: "primary",
        click: function () {
          var newValue = $j("#mass-update-new-value").select2("val");

          /* send update request */
          massUpdateAlert(massUpdateTranslation.pleaseWait);
          $j.ajax({
            url: "hooks/ajax-mass-update-Assignments-TeacherID-l7f96jv6ah5nnuc8bqy0.php",
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

  /* prepare select2 drop-down inside modal */
  $j("#mass-update-new-value-modal").on("shown.bs.modal", function () {
    $j("#mass-update-new-value")
      .select2({
        width: "100%",
        formatNoMatches: function (term) {
          return massUpdateTranslation.noMatches;
        },
        minimumResultsForSearch: 5,
        loadMorePadding: 200,
        ajax: {
          url: "ajax_combo.php",
          dataType: "json",
          cache: true,
          data: function (term, page) {
            return {
              t: "Assignments",
              f: "TeacherID",
              s: term,
              p: page,
              json: 1,
            };
          },
          results: function (resp, page) {
            return resp;
          },
        },
        escapeMarkup: function (str) {
          return str;
        },
      })
      .select2("focus");
  });
}

/* Teacher B command */
function massUpdateCommand_sxhn8ezhhm2uhmsdv4v4(tn, ids) {
  /* Ask user for new value */
  modal_window({
    id: "mass-update-new-value-modal",
    message: '<div id="mass-update-new-value"></div>',
    title: '<i class="glyphicon glyphicon-plus"></i> ' + "Teacher B",
    footer: [
      {
        label: massUpdateTranslation.confirm,
        bs_class: "primary",
        click: function () {
          var newValue = $j("#mass-update-new-value").select2("val");

          /* send update request */
          massUpdateAlert(massUpdateTranslation.pleaseWait);
          $j.ajax({
            url: "hooks/ajax-mass-update-Assignments-TeacherID2-sxhn8ezhhm2uhmsdv4v4.php",
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

  /* prepare select2 drop-down inside modal */
  $j("#mass-update-new-value-modal").on("shown.bs.modal", function () {
    $j("#mass-update-new-value")
      .select2({
        width: "100%",
        formatNoMatches: function (term) {
          return massUpdateTranslation.noMatches;
        },
        minimumResultsForSearch: 5,
        loadMorePadding: 200,
        ajax: {
          url: "ajax_combo.php",
          dataType: "json",
          cache: true,
          data: function (term, page) {
            return {
              t: "Assignments",
              f: "TeacherID2",
              s: term,
              p: page,
              json: 1,
            };
          },
          results: function (resp, page) {
            return resp;
          },
        },
        escapeMarkup: function (str) {
          return str;
        },
      })
      .select2("focus");
  });
}

/* Lesson Type command */
function massUpdateCommand_zu9sk69g55s5es702kr5(tn, ids) {
  /* Ask user for new value */
  modal_window({
    id: "mass-update-new-value-modal",
    message:
      '<div class="form-group"><label for="mass-update-new-value">' +
      massUpdateTranslation.newValue +
      '</label><input type="text" class="form-control" id="mass-update-new-value"></div>',
    title: '<i class="glyphicon glyphicon-cog"></i> ' + "Lesson Type",
    footer: [
      {
        label: massUpdateTranslation.confirm,
        bs_class: "primary",
        click: function () {
          var newValue = $j("#mass-update-new-value").val();

          /* send update request */
          massUpdateAlert(massUpdateTranslation.pleaseWait);
          $j.ajax({
            url: "hooks/ajax-mass-update-Assignments-LessonType-zu9sk69g55s5es702kr5.php",
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

$j(document).ready(function () {
  AppGiniHelper.TV.setWidth(2, 200);
  $j("#top_buttons").append(
    '<a href="https://6epal-esp-thess.gr/assignments/Assignments_view.php?SortField=&SortDirection=&FilterAnd%5B1%5D=and&FilterField%5B1%5D=10&FilterOperator%5B1%5D=is-empty&FilterValue%5B1%5D=">Αναθέσεις χωρίς Α εκπαιδευτικό</a>'
  );
  $j("#top_buttons").append(
    '<br><a href="https://6epal-esp-thess.gr/assignments/Assignments_view.php?SortField=&SortDirection=&FilterAnd%5B1%5D=and&FilterField%5B1%5D=11&FilterOperator%5B1%5D=is-empty&FilterValue%5B1%5D=">Αναθέσεις χωρίς Β εκπαιδευτικό</a>'
  );
});

/*function remove_A_teacher(table_name, ids) {
  alert("IDs selected from " + table_name + ": " + ids);
}*/
