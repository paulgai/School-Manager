new AppGiniLayout([1, 1, 5, 3, 1, 1])
  .add(2, ["Class"])
  .add(3, ["Name"])
  .add(4, ["Type"])
  .add(5, ["Hours"])
  .wrapLabels();

new AppGiniLayout([4, 4, 4])
  .add(1, ["A_assignment"])
  .add(2, ["B_assignment"])
  .add(3, ["C_assignment"])
  .wrapLabels();

$j(".form-control-static").css("padding", 0);
$j("#Name").height("22px");
$j("#Hours").height("20px");
