$(document).ready(function() {
  $('table.highchart').highchartTable();
  $("#languages").bind("change", function() {
    $("#languagesForm").submit();
  });  
});