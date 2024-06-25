// file: hooks/TABLENAME-dv.js
var dv = AppGiniHelper.DV;

//var tabIncoming = dv.addTab("tabIncoming", "Εισερχόμενο", "log-in");

var row1 = dv
  .addLayout([4, 4, 4])
  .add(1, ["receipt_date"])
  .add(2, ["doc_number"])
  .add(3, ["place"])
  .wrapLabels();

var row2 = dv
  .addLayout([4, 4, 4])
  .add(1, ["authority_issuing"])
  .add(2, ["date_issuing"])
  .add(3, ["to_whom"])
  .wrapLabels();

var row3 = dv.addLayout([4, 8]).add(1, ["summary_incoming"]).wrapLabels();

dv.addTab("tabIncoming", "Εισερχόμενο", "log-in").add(row1).add(row2).add(row3);

var row4 = dv
  .addLayout([4, 4, 4])
  .add(1, ["authority_outcoming"])
  .add(2, ["outcoming_date"])
  .add(3, ["summary_outcoming"])
  .wrapLabels();

var row5 = dv
  .addLayout([4, 4, 4])
  .add(1, ["processing_date"])
  .add(2, ["folder_id"])
  .add(3, ["subfolder_id"])
  .wrapLabels();

var row6 = dv.addLayout([4, 8]).add(1, ["comments"]).wrapLabels();

var tabOutcoming = dv
  .addTab("tabOutcoming", "Εξερχόμενο", "log-out")
  .add(row4)
  .add(row5)
  .add(row6);

dv.getTabs().setPosition(TabPosition.Bottom);
dv.getField("serial_number").insertBelow().hr();

$j(".control-label.col-lg-3")
  .filter(function () {
    return $j(this).text().trim() === "Αριθμός Πρωτοκόλλου:";
  })
  .css({
    "font-size": "larger",
    "font-weight": "bold",
  });

$j("#serial_number").css({
  "font-size": "larger",
  "font-weight": "bold",
});
