//new AppGiniField("Assumption_Date").toDatepicker();

//AppGiniHelper.dv.getField("Assumption_Date").toDatepicker();

new AppGiniLayout([2, 4, 4, 2])
  .add(2, ["Name"])
  .add(3, ["SectorID"])
  .wrapLabels();

new AppGiniLayout([2, 4, 4, 2])
  .add(2, ["Placement"])
  .add(3, ["Assumption_Date"])
  .wrapLabels();

new AppGiniLayout([2, 4, 4, 2])
  //.add(2, ["Assumption_Date"])
  .add(2, ["Mandatory_Hours"])
  .add(3, ["Main_Sector"])
  .wrapLabels();

new AppGiniLayout([2, 2, 2, 2, 2, 2])
  .add(2, ["Assigned_Hours_Theory"])
  .add(3, ["Assigned_Hours_Lab"])
  .add(4, ["Assigned_Hours"])
  .add(5, ["Diff"])
  .wrapLabels();

$j("#Assigned_Hours_Theory").css("font-weight", "bold");
$j("#Assigned_Hours_Lab").css("font-weight", "bold");
$j("#Assigned_Hours").css("font-weight", "bold");
$j("#Diff").css("font-weight", "bold");

//new AppGiniField("Assumption_Date").toDatepicker();
//new AppGiniField("Assumption_Date").toDatepicker();
