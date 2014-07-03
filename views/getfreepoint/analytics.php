<?php

  if(!isset($_COOKIE) || !isset($_COOKIE["FreePointSecureDashboard"])){
    header("Location: sync-login");
  }

?>


  <a href="sync-logout" style="position: fixed; right: 15px; top: 15px;">Logout</a>

<div class="container">
  <div class="row" style="padding: 15px;">
    <label for="selCompany">Select Company:</label>
    <select id="selCompany" style="width: 100%; padding: 7.5px; opacity: 0.6;">
    </select>
  </div>

  <div class="row" style="padding: 15px;">
    <label for="selCell">Select a Cell:</label>
    <select id="selCell" style="width: 100%; padding: 7.5px; opacity: 0.6;">
    </select>
  </div>

  <div class="row" style="padding: 15px;">
    <label for="selMach">Select a Machine:</label>
    <select id="selMach" style="width: 100%; padding: 7.5px; opacity: 0.6;">
    </select>
  </div>

  <div class="row" style="padding: 15px;">
    <label for="selFlist">Select a File:</label><br />
    <select id="selFlist" style="width: 50%; padding: 7.5px; opacity: 0.6;">
    </select>

    <a id="aDownloadLauncher" href="#">
      <button id="btnDownload" style="width: 12%; float: right; padding: 7.5px;" class="btn btn-primary">Download</button>
    </a>
    <form method="POST" action="sync-report" style="margin: 0; padding: 0; display: inline-block; width: 12%; margin-right: 7.5px; float: right;">
      <input id="formfpath" type="hidden" name="path" />
      <button type="submit" id="btnReport" class="btn btn-default" style="display: none; padding: 7.5px; width: 100%;">Report</button>
    </form>  
  </div>

</div>

<script type="text/javascript">

Array.prototype.contains = function ( needle ) {
   for (i in this) {
       if (this[i] == needle) return true;
   }
   return false;
}

/* Application data */
var User = {

  Select: {
      Company: "", 
      Cell: "",
      Machine: ""
  }

};

var Server = {

  Companies: {},
  Cells: {},
  Machines: {}

}

/* Disable buttons on start */

DisableFileButtons();

function DisableFileButtons(){

  $("#btnDownload").attr("disabled", "disabled");
  $("#btnReport").attr("disabled", "disabled");

}


/* company list */
function displayCompanyList(){
  $.ajax({
    type: "POST",
    url: "http://54.213.13.56/sync-api",
    data: {'lib': "company", 'software_key': '<?php echo $_COOKIE["FreePointSecureDashboard"]; ?>' },
    dataType: "json",
    success: function(resp){
      Server.Companies = resp;
      displayList(resp);
    },
    error: function(e){
    }
  });
}
function displayList(list){
  $("#selCell").html("");
  $("#selMach").html("");
  $("#selFlist").html("");
  // Clear celllist, machlist...

  for (var i = 0; i < list.length; i++){
    var self = $("#selCompany");
    self.html(self.html() + "<option data-key='"+list[i].software_key+"'>"+list[i].name+"</option>");
  }
}

$("#selCompany").change(function(){
  User.Select.Company = $(this).val();
  User.Select.SoftwareKey = $(this).find(":selected").data("key");

  getCellList(User.Select.SoftwareKey);
}).click(function(){
  User.Select.Company = $(this).val();
  User.Select.SoftwareKey = $(this).find(":selected").data("key");

  getCellList(User.Select.SoftwareKey);
});

displayCompanyList();

/* cell list */
function getCellList( SoftwareKey ){
  $.ajax({
    type: "GET",
    url: "http://54.213.13.56/sync-api",
    data: {key: SoftwareKey},
    dataType: "json",
    success: function(resp){
      console.log(resp);
      Server.Cells = resp;
      displayCellList(Server.Cells);
    }
  });
}

function displayCellList( Cells ){
  
  $("#selCell").html("");
  $("#selMach").html("");
  $("#selFlist").html("");
  // Clear celllist, machlist...

  var cellList = [];
  for(var i = 0; i < Cells.length; i++){
    if( typeof(Cells[i].cell) != "undefined" ){
      var locInArray = $.inArray(Cells[i].cell, cellList);
      if (locInArray < 0){
        cellList.push(Cells[i].cell);
      }
    }
  }
  // We get a list of unique Cell names.

  for (var i = 0; i < cellList.length; i++){
    var a = cellList[i];
    $("#selCell").html(
      $("#selCell").html() + "<option>" + a + "</option>"
    );
  }
  // We display them in the GUI.
}
function selCell_Change(){
  User.Select.Cell = $(this).val();
  displayMachineList(Server.Cells);
}

$("#selCell").on("click", selCell_Change).on("change", selCell_Change);

/* Machines */
function displayMachineList(Cells){
  var selMach = $("#selMach");
  var machList = [];

  selMach.html("");
  // Clear selMach.

  for (var i = 0; i < Cells.length; i ++){

    // Row contains a server sync-file record.
    var row = Cells[i];

    // Filter: by correct cell and machine that isn't undefined.
    if (row.cell == User.Select.Cell && typeof(row.machine) != "undefined"){
      
        // Assemble array of only unique machines.
        if (!machList.contains(row.machine)){
          machList.push(row.machine);
        }
        
    }
    
  }

  // Display machList in UI
  for(var i = 0; i < machList.length; i ++){
    selMach.html( selMach.html() + "<option>" + machList[i] + "</option>" );
  }
}

$("#selMach").on("click", selMach_Change).on("change", selMach_Change);

// Set the User.Selected var
function selMach_Change(){
  User.Select.Machine = $(this).val();
  getFileList();
}

/* File List */
function getFileList(){

  var selFlist = $("#selFlist");
  selFlist.html("");

  for(var i = 0; i < Server.Cells.length; i++){

      // Store a row.
      var row = Server.Cells[i];

      // Trim the row (Correction made for Precision MFG - cell with trailing spaces)
      if (row.machine !== undefined) row.machine = row.machine.trim();

      // Display the files.
      if (row.cell == User.Select.Cell && row.machine == User.Select.Machine){
          
          if (row.file_pointer != null){
            selFlist.html(selFlist.html() + "<option data-pointer='" + row.file_pointer + "'>" + row.name + "</option>");
          } else {
            selFlist.html(selFlist.html() + "<option style='color: red;' data-pointer='" + row.file_pointer + "'>" + row.name + "</option>");
          }
      }
  }
}

$("#selFlist").on("click", selFlist_Change).on("change", selFlist_Change);

function selFlist_Change(){

    // Pick the file.
    var $selectedFile = $("#selFlist").find(":selected");

    // Check if it's valid.
    if (typeof($selectedFile) == "undefined"){
      return false;
    }

    // Choose the button.
    var btn = $("#aDownloadLauncher");

    // Only make the download link if it's not null.
    if($selectedFile.data("pointer") == null){
      btn.attr("disabled", "disabled");
      $("#btnDownload").attr("disabled", "disabled");
      $("#btnReport").attr("disabled", "disabled");
    } else {
      btn.removeAttr("disabled");
      $("#btnReport").removeAttr("disabled");
      $("#btnDownload").removeAttr("disabled");
    }


    // Get pertinent info for download link.
    var filePointer = "http://54.213.13.56/uploads/" + $selectedFile.data("pointer");
    var fileName = $selectedFile.val();

    // Place the info on the anchor.
    btn.attr("href", filePointer);
    btn.attr("download", fileName);

    // Place the info on the hidden form input.
    $("#formfpath").attr("value", filePointer);
   
}

/* Button: Download */
$("#btnDownload").on("click", function(){

    console.log($("#selFlist").find(":selected").val());

});


</script>
